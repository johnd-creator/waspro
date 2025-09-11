<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ExpirySettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (Auth::user()->role !== 'Super Admin') {
                abort(403, 'Unauthorized access');
            }
            return $next($request);
        });
    }

    /**
     * Display the expiry settings form
     */
    public function index()
    {
        $settings = $this->getExpirySettings();
        
        // Calculate percentages for progress bars
        $criticalPercentage = round(($settings['critical_days'] / 365) * 100, 2);
        $warningPercentage = round(($settings['warning_days'] / 365) * 100, 2);
        
        return view('expiry-settings.index', compact('settings', 'criticalPercentage', 'warningPercentage'));
    }

    /**
     * Update expiry settings
     */
    public function update(Request $request)
    {
        $request->validate([
            'critical_days' => 'required|integer|min:1|max:365',
            'warning_days' => 'required|integer|min:1|max:365',
        ]);

        // Validate that warning_days > critical_days
        if ($request->warning_days <= $request->critical_days) {
            return back()->withErrors([
                'warning_days' => 'Hari peringatan harus lebih besar dari hari kritis'
            ]);
        }

        try {
            DB::beginTransaction();

            // Update or create critical_days setting
            $this->updateOrCreateSetting('critical_days', $request->critical_days, 'Jumlah hari untuk status kritis sebelum kadaluarsa');
            
            // Update or create warning_days setting
            $this->updateOrCreateSetting('warning_days', $request->warning_days, 'Jumlah hari untuk status peringatan sebelum kadaluarsa');

            DB::commit();

            return redirect()->route('expiry-settings.index')
                ->with('success', 'Pengaturan expiry berhasil diperbarui');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Gagal memperbarui pengaturan: ' . $e->getMessage()]);
        }
    }

    /**
     * Get current expiry settings
     */
    private function getExpirySettings()
    {
        $criticalDays = DB::table('app_settings')
            ->where('key', 'critical_days')
            ->value('value') ?? 7; // Default 7 days

        $warningDays = DB::table('app_settings')
            ->where('key', 'warning_days')
            ->value('value') ?? 30; // Default 30 days

        return [
            'critical_days' => (int) $criticalDays,
            'warning_days' => (int) $warningDays,
        ];
    }

    /**
     * Update or create a setting
     */
    private function updateOrCreateSetting($key, $value, $description)
    {
        DB::table('app_settings')->updateOrInsert(
            ['key' => $key],
            [
                'value' => $value,
                'type' => 'integer',
                'description' => $description,
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );
    }

    /**
     * Reset settings to default
     */
    public function reset()
    {
        try {
            DB::beginTransaction();

            $this->updateOrCreateSetting('critical_days', 7, 'Jumlah hari untuk status kritis sebelum kadaluarsa');
            $this->updateOrCreateSetting('warning_days', 30, 'Jumlah hari untuk status peringatan sebelum kadaluarsa');

            DB::commit();

            return redirect()->route('expiry-settings.index')
                ->with('success', 'Pengaturan expiry berhasil direset ke default');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Gagal mereset pengaturan: ' . $e->getMessage()]);
        }
    }
}
