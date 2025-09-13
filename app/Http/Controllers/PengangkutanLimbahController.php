<?php

namespace App\Http\Controllers;

use App\Models\JenisLimbah;
use App\Models\LogPenyimpananLimbah;
use App\Models\PenggunaSistem;
use App\Models\PerusahaanPenghasil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengangkutanLimbahController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('unit.access');
    }

    /**
     * Tampilkan semua limbah kecuali yang berstatus 'diangkut'
     */
    public function index(Request $request)
    {
        /** @var PenggunaSistem|null $user */
        $user = Auth::guard('web')->user();

        // Hanya Supervisor dan Admin yang bisa mengakses
        if (! $user || (! $user->isSupervisor() && ! $user->isAdmin())) {
            abort(403, 'Unauthorized access');
        }

        // Query dasar untuk log penyimpanan limbah
        $query = LogPenyimpananLimbah::with([
            'jenisLimbah',
            'perusahaanPenghasil',
            'unitPembangkit',
            'penggunaSistem',
        ]);

        // Filter berdasarkan unit jika bukan Super Admin
        if (! $user->isAdmin()) {
            $query->where('unit_id', $user->unit_id);
        }

        // Tampilkan semua status kecuali 'diangkut'
        $query->where('status_log', '!=', 'diangkut');

        // Filter berdasarkan jenis limbah jika ada
        if ($request->filled('jenis_limbah')) {
            $query->where('kode_limbah', $request->jenis_limbah);
        }

        // Filter berdasarkan perusahaan jika ada
        if ($request->filled('perusahaan')) {
            $query->where('perusahaan_id', $request->perusahaan);
        }

        // Filter berdasarkan status jika ada
        if ($request->filled('status')) {
            $query->where('status_log', $request->status);
        }

        // Filter berdasarkan tanggal jika ada
        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('tanggal_limbah_masuk', '>=', $request->tanggal_mulai);
        }
        if ($request->filled('tanggal_akhir')) {
            $query->whereDate('tanggal_limbah_masuk', '<=', $request->tanggal_akhir);
        }

        // Filter berdasarkan kode identitas jika ada
        if ($request->filled('kode_identitas')) {
            $query->where('kode_identitas', 'like', '%'.$request->kode_identitas.'%');
        }

        $logPenyimpanan = $query->orderBy('tanggal_limbah_masuk', 'desc')->paginate(15);

        // Data untuk filter dropdown
        $jenisLimbah = JenisLimbah::where('is_active', true)->get();
        $perusahaan = PerusahaanPenghasil::where('is_active', true)->get();
        $statusOptions = ['tersimpan', 'kadaluarsa', 'hampir_kadaluarsa'];

        return view('pengangkutan-limbah.index', compact(
            'logPenyimpanan',
            'jenisLimbah',
            'perusahaan',
            'statusOptions'
        ));
    }

    /**
     * Tampilkan semua limbah yang berstatus 'diangkut'
     */
    public function diangkut(Request $request)
    {
        /** @var PenggunaSistem|null $user */
        $user = Auth::guard('web')->user();

        // Hanya Supervisor dan Admin yang bisa mengakses
        if (! $user || (! $user->isSupervisor() && ! $user->isAdmin())) {
            abort(403, 'Unauthorized access');
        }

        // Query dasar untuk log penyimpanan limbah yang sudah diangkut
        $query = LogPenyimpananLimbah::with([
            'jenisLimbah',
            'perusahaanPenghasil',
            'unitPembangkit',
            'penggunaSistem',
        ]);

        // Filter berdasarkan unit jika bukan Super Admin
        if (! $user->isAdmin()) {
            $query->where('unit_id', $user->unit_id);
        }

        // Hanya tampilkan yang berstatus 'diangkut'
        $query->where('status_log', 'diangkut');

        // Filter berdasarkan jenis limbah jika ada
        if ($request->filled('jenis_limbah')) {
            $query->where('kode_limbah', $request->jenis_limbah);
        }

        // Filter berdasarkan perusahaan jika ada
        if ($request->filled('perusahaan')) {
            $query->where('perusahaan_id', $request->perusahaan);
        }

        // Filter berdasarkan tanggal pengangkutan jika ada
        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('tanggal_pengangkutan', '>=', $request->tanggal_mulai);
        }
        if ($request->filled('tanggal_akhir')) {
            $query->whereDate('tanggal_pengangkutan', '<=', $request->tanggal_akhir);
        }

        // Filter berdasarkan kode identitas jika ada
        if ($request->filled('kode_identitas')) {
            $query->where('kode_identitas', 'like', '%'.$request->kode_identitas.'%');
        }

        $logPenyimpanan = $query->orderBy('tanggal_pengangkutan', 'desc')->paginate(15);

        // Data untuk filter dropdown
        $jenisLimbah = JenisLimbah::where('is_active', true)->get();
        $perusahaan = PerusahaanPenghasil::where('is_active', true)->get();

        return view('pengangkutan-limbah.diangkut', compact(
            'logPenyimpanan',
            'jenisLimbah',
            'perusahaan'
        ));
    }

    /**
     * Setujui pengangkutan limbah (untuk Supervisor)
     */
    public function approve(Request $request, $id)
    {
        /** @var PenggunaSistem|null $user */
        $user = Auth::guard('web')->user();

        // Hanya Supervisor dan Super Admin yang bisa approve
        if (! $user || (! $user->isSupervisor() && ! $user->isAdmin())) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk menyetujui pengangkutan limbah.');
        }

        $log = LogPenyimpananLimbah::findOrFail($id);

        // Pastikan limbah belum diangkut
        if ($log->status_log === 'diangkut') {
            return redirect()->back()->with('error', 'Limbah sudah dalam status diangkut.');
        }

        // Update status menjadi diangkut
        $log->update([
            'status_log' => 'diangkut',
            'tanggal_pengangkutan' => now(),
            'jumlah_diangkut' => $log->jumlah_limbah_masuk,
        ]);

        return redirect()->back()->with('success', 'Pengangkutan limbah berhasil disetujui.');
    }

    /**
     * Setujui pengangkutan limbah secara bulk (untuk Supervisor)
     */
    public function bulkApprove(Request $request)
    {
        /** @var PenggunaSistem|null $user */
        $user = Auth::guard('web')->user();

        // Hanya Supervisor dan Super Admin yang bisa approve
        if (! $user || (! $user->isSupervisor() && ! $user->isAdmin())) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk menyetujui pengangkutan limbah.');
        }

        $request->validate([
            'selected_logs' => 'required|array',
            'selected_logs.*' => 'exists:log_penyimpanan_limbah,log_id',
        ]);

        $logs = LogPenyimpananLimbah::whereIn('log_id', $request->selected_logs)
            ->where('status_log', '!=', 'diangkut')
            ->get();

        $approvedCount = 0;
        foreach ($logs as $log) {
            $log->update([
                'status_log' => 'diangkut',
                'tanggal_pengangkutan' => now(),
                'jumlah_diangkut' => $log->jumlah_limbah_masuk,
            ]);
            $approvedCount++;
        }

        return redirect()->back()->with('success', "Berhasil menyetujui pengangkutan {$approvedCount} limbah.");
    }
}
