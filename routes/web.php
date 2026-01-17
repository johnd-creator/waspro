<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }

    return redirect()->route('login');
});

// Authentication Routes
Route::get('login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [App\Http\Controllers\Auth\LoginController::class, 'login']);
Route::post('logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

// Registration Routes (if needed)
Route::get('register', [App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [App\Http\Controllers\Auth\RegisterController::class, 'register']);

// Home
Route::get('/home', function () {
    return redirect()->route('dashboard');
})->name('home');

// Dashboard Route
Route::middleware(['auth', 'unit.access'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/chart-data', [App\Http\Controllers\DashboardController::class, 'getChartData'])->name('dashboard.chart-data');
    Route::get('/dashboard/near-expiry', [App\Http\Controllers\DashboardController::class, 'nearExpiryList'])->name('dashboard.near-expiry');
});

// Profile Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.password');
});

// Audit Log Routes
Route::middleware(['auth', 'unit.access'])->prefix('audit-log')->group(function () {
    Route::get('/', [App\Http\Controllers\AuditLogController::class, 'index'])->name('audit-log.index');
    Route::get('/export/csv', [App\Http\Controllers\AuditLogController::class, 'exportCsv'])->name('audit-log.export.csv');
    Route::get('/export/excel', [App\Http\Controllers\AuditLogController::class, 'exportExcel'])->name('audit-log.export.excel');
});

// Resource Routes
Route::middleware(['auth', 'unit.access'])->group(function () {
    // Log Penyimpanan Limbah
    Route::resource('log-penyimpanan', App\Http\Controllers\LogPenyimpananLimbahController::class);
    Route::post('log-penyimpanan/{id}/approve', [App\Http\Controllers\LogPenyimpananLimbahController::class, 'approve'])->name('log-penyimpanan.approve');
    Route::post('log-penyimpanan/{id}/reject', [App\Http\Controllers\LogPenyimpananLimbahController::class, 'reject'])->name('log-penyimpanan.reject');
    Route::get('log-penyimpanan/export', [App\Http\Controllers\LogPenyimpananLimbahController::class, 'export'])->name('log-penyimpanan.export');

    // Pengangkutan Limbah
    Route::resource('pengangkutan-limbah', App\Http\Controllers\PengangkutanLimbahController::class);
    Route::get('pengangkutan-limbah/diangkut', [App\Http\Controllers\PengangkutanLimbahController::class, 'diangkut'])->name('pengangkutan-limbah.diangkut');
    Route::post('pengangkutan-limbah/bulk-approve', [App\Http\Controllers\PengangkutanLimbahController::class, 'bulkApprove'])->name('pengangkutan-limbah.bulk-approve');

    // Reports
    Route::get('/reports', [App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/monthly', [App\Http\Controllers\ReportController::class, 'monthly'])->name('reports.monthly');
    Route::get('/reports/status', [App\Http\Controllers\ReportController::class, 'status'])->name('reports.status');
    Route::get('/reports/waste-type', [App\Http\Controllers\ReportController::class, 'wasteType'])->name('reports.waste-type');
    Route::get('/reports/company', [App\Http\Controllers\ReportController::class, 'company'])->name('reports.company');
    Route::get('/reports/unit', [App\Http\Controllers\ReportController::class, 'unit'])->name('reports.unit');
    Route::get('/reports/export', [App\Http\Controllers\ReportController::class, 'export'])->name('reports.export');
    Route::post('/reports/clear-cache', [App\Http\Controllers\ReportController::class, 'clearReportCache'])->name('reports.clear-cache');

    // Expiry Reports
    Route::prefix('expiry-reports')->name('expiry-reports.')->group(function () {
        Route::get('/', [App\Http\Controllers\ExpiryReportController::class, 'index'])->name('index');
        Route::get('/export', [App\Http\Controllers\ExpiryReportController::class, 'export'])->name('export');
    });

    // Master Data - Perusahaan Penghasil
    Route::resource('perusahaan-penghasil', App\Http\Controllers\PerusahaanPenghasilController::class);

    // Master Data - Unit Pembangkit
    Route::resource('unit-pembangkit', App\Http\Controllers\UnitPembangkitController::class);

    // Master Data - Pengguna Sistem
    Route::resource('pengguna-sistem', App\Http\Controllers\PenggunaSistemController::class);
    Route::post('pengguna-sistem/{pengguna_sistem}/toggle-status', [App\Http\Controllers\PenggunaSistemController::class, 'toggleStatus'])->name('pengguna-sistem.toggle-status');

    // Master Data - Peran Pengguna
    Route::resource('peran-pengguna', App\Http\Controllers\PeranPenggunaController::class);
    Route::post('peran-pengguna/{peran_pengguna}/toggle-status', [App\Http\Controllers\PeranPenggunaController::class, 'toggleStatus'])->name('peran-pengguna.toggle-status');

    // Limbah - Jenis Limbah
    Route::resource('jenis-limbah', App\Http\Controllers\JenisLimbahController::class);

    // Limbah - Karakteristik Limbah
    Route::resource('karakteristik-limbah', App\Http\Controllers\KarakteristikLimbahController::class);

    // Limbah - Kategori Kegiatan Sumber
    Route::resource('kategori-kegiatan-sumber', App\Http\Controllers\KategoriKegiatanSumberController::class);

    // Workflow Settings
    Route::get('workflow-settings', [App\Http\Controllers\WorkflowSettingsController::class, 'index'])->name('workflow-settings.index');
    Route::put('workflow-settings', [App\Http\Controllers\WorkflowSettingsController::class, 'update'])->name('workflow-settings.update');

    // Upload Settings
    Route::get('upload-settings', [App\Http\Controllers\UploadSettingsController::class, 'index'])->name('upload-settings.index');
    Route::put('upload-settings', [App\Http\Controllers\UploadSettingsController::class, 'update'])->name('upload-settings.update');

    // Expiry Settings
    Route::get('expiry-settings', [App\Http\Controllers\ExpirySettingsController::class, 'index'])->name('expiry-settings.index');
    Route::put('expiry-settings', [App\Http\Controllers\ExpirySettingsController::class, 'update'])->name('expiry-settings.update');

    // Report Settings
    Route::get('report-settings', [App\Http\Controllers\ReportSettingsController::class, 'index'])->name('report-settings.index');
    Route::put('report-settings', [App\Http\Controllers\ReportSettingsController::class, 'update'])->name('report-settings.update');

    // System Settings (Super Admin only) - No create/store/destroy, settings are permanent
    Route::post('application-settings/clear-cache', [App\Http\Controllers\ApplicationSettingController::class, 'clearCache'])->name('application-settings.clear-cache');
    Route::get('application-settings', [App\Http\Controllers\ApplicationSettingController::class, 'index'])->name('application-settings.index');
    Route::get('application-settings/{applicationSetting}', [App\Http\Controllers\ApplicationSettingController::class, 'show'])->name('application-settings.show');
    Route::get('application-settings/{applicationSetting}/edit', [App\Http\Controllers\ApplicationSettingController::class, 'edit'])->name('application-settings.edit');
    Route::put('application-settings/{applicationSetting}', [App\Http\Controllers\ApplicationSettingController::class, 'update'])->name('application-settings.update');
    Route::get('settings/audit', [App\Http\Controllers\SettingsAuditController::class, 'index'])->name('settings.audit.index');
    Route::get('settings/audit/{id}', [App\Http\Controllers\SettingsAuditController::class, 'show'])->name('settings.audit.show');

    // Notifications
    Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/get-count', [App\Http\Controllers\NotificationController::class, 'getCount'])->name('notifications.count');
    Route::get('/notifications/get-expiry-notifications', [App\Http\Controllers\NotificationController::class, 'getExpiryNotifications'])->name('notifications.expiry');
    Route::post('/notifications/mark-all-read', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
});
