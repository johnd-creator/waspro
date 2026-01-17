<?php

namespace App\Http\Controllers;

use App\Models\ApplicationSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ExpirySettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!Auth::user()->isSuperAdmin()) {
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
        $urgentPercentage = round(($settings['urgent_days'] / 365) * 100, 2);
        $criticalPercentage = round(($settings['critical_days'] / 365) * 100, 2);
        $warningPercentage = round(($settings['warning_days'] / 365) * 100, 2);

        return view('expiry-settings.index', compact('settings', 'urgentPercentage', 'criticalPercentage', 'warningPercentage'));
    }

    /**
     * Update expiry settings
     */
    public function update(Request $request)
    {
        $request->validate([
            'urgent_days' => 'required|integer|min:1|max:365',
            'critical_days' => 'required|integer|min:1|max:365',
            'warning_days' => 'required|integer|min:1|max:365',
        ]);

        // Validate logic: urgent < critical < warning
        if ($request->critical_days <= $request->urgent_days) {
            return back()->withErrors([
                'critical_days' => 'Hari kritis harus lebih besar dari hari urgent',
            ]);
        }

        if ($request->warning_days <= $request->critical_days) {
            return back()->withErrors([
                'warning_days' => 'Hari peringatan harus lebih besar dari hari kritis',
            ]);
        }

        try {
            DB::beginTransaction();

            // Update or create urgent_days setting
            $this->updateOrCreateSetting('expiry.urgent_days', $request->urgent_days, 'Jumlah hari untuk status peringatan urgent sebelum kadaluarsa');

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
        $urgentDays = (int) ApplicationSetting::getValue('expiry.urgent_days', 3);
        $criticalDays = (int) ApplicationSetting::getValue('critical_days', 7);
        $warningDays = (int) ApplicationSetting::getValue('warning_days', 30);

        return [
            'urgent_days' => $urgentDays,
            'critical_days' => $criticalDays,
            'warning_days' => $warningDays,
        ];
    }

    /**
     * Update or create a setting
     */
    private function updateOrCreateSetting($key, $value, $description)
    {
        ApplicationSetting::setValue($key, (int) $value, 'integer', 'expiry', $description);
    }

    /**
     * Reset settings to default
     */
    public function reset()
    {
        try {
            DB::beginTransaction();

            $this->updateOrCreateSetting('expiry.urgent_days', 3, 'Jumlah hari untuk status peringatan urgent sebelum kadaluarsa');
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
