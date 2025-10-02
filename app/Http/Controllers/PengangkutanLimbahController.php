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
     * Tampilkan semua limbah kecuali yang berstatus 'Diangkut'
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

        // Tampilkan semua status kecuali 'Diangkut' (case-insensitive)
        $query->whereRaw("LOWER(status_log) != 'diangkut'");

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
        $statusOptions = ['Tersimpan', 'Kadaluarsa', 'Hampir Kadaluarsa'];

        return view('pengangkutan-limbah.index', compact(
            'logPenyimpanan',
            'jenisLimbah',
            'perusahaan',
            'statusOptions'
        ));
    }

    /**
     * Tampilkan semua limbah yang berstatus 'Diangkut'
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

        // Hanya tampilkan yang berstatus 'Diangkut' (case-insensitive)
        $query->whereRaw("LOWER(status_log) = 'diangkut'");

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

        // Pastikan limbah belum diangkut (case-insensitive)
        if (strtolower($log->status_log) === 'diangkut') {
            return redirect()->back()->with('error', 'Limbah sudah dalam status diangkut.');
        }

        // Update status menjadi Diangkut
        $log->update([
            'status_log' => 'Diangkut',
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
            ->whereRaw("LOWER(status_log) != 'diangkut'")
            ->get();

        $approvedCount = 0;
        foreach ($logs as $log) {
            $log->update([
                'status_log' => 'Diangkut',
                'tanggal_pengangkutan' => now(),
                'jumlah_diangkut' => $log->jumlah_limbah_masuk,
            ]);
            $approvedCount++;
        }

        return redirect()->back()->with('success', "Berhasil menyetujui pengangkutan {$approvedCount} limbah.");
    }

    /**
     * Tampilkan form untuk membuat data pengangkutan limbah baru
     */
    public function create()
    {
        /** @var PenggunaSistem|null $user */
        $user = Auth::guard('web')->user();

        // Hanya Supervisor dan Admin yang bisa mengakses
        if (! $user || (! $user->isSupervisor() && ! $user->isAdmin())) {
            abort(403, 'Unauthorized access');
        }

        // Data untuk dropdown
        $jenisLimbah = JenisLimbah::where('is_active', true)->get();
        $perusahaan = PerusahaanPenghasil::where('is_active', true)->get();

        return view('pengangkutan-limbah.create', compact(
            'jenisLimbah',
            'perusahaan'
        ));
    }

    /**
     * Simpan data pengangkutan limbah baru
     */
    public function store(Request $request)
    {
        /** @var PenggunaSistem|null $user */
        $user = Auth::guard('web')->user();

        // Hanya Supervisor dan Admin yang bisa mengakses
        if (! $user || (! $user->isSupervisor() && ! $user->isAdmin())) {
            abort(403, 'Unauthorized access');
        }

        $request->validate([
            'kode_limbah' => 'required|exists:jenis_limbah,kode_limbah',
            'perusahaan_id' => 'required|exists:perusahaan_penghasil,perusahaan_id',
            'jumlah_limbah_masuk' => 'required|numeric|min:0',
            'tanggal_limbah_masuk' => 'required|date',
            'keterangan' => 'nullable|string|max:255',
        ]);

        // Buat log penyimpanan baru dengan status Diangkut
        $log = new LogPenyimpananLimbah;
        $log->kode_limbah = $request->kode_limbah;
        $log->perusahaan_id = $request->perusahaan_id;
        $log->unit_id = $user->unit_id;
        $log->jumlah_limbah_masuk = $request->jumlah_limbah_masuk;
        $log->tanggal_limbah_masuk = $request->tanggal_limbah_masuk;
        $log->keterangan = $request->keterangan;
        $log->status_log = 'Diangkut';
        $log->tanggal_pengangkutan = now();
        $log->jumlah_diangkut = $request->jumlah_limbah_masuk;
        $log->user_id = $user->user_id;
        $log->kode_identitas = 'ANG-'.date('YmdHis').'-'.rand(1000, 9999);
        $log->save();

        return redirect()->route('pengangkutan-limbah.diangkut')
            ->with('success', 'Data pengangkutan limbah berhasil ditambahkan.');
    }
}
