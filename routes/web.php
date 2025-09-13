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
// Route::get('register', [App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
// Route::post('register', [App\Http\Controllers\Auth\RegisterController::class, 'register']);

Route::get('/home', function () {
    return redirect()->route('dashboard');
})->name('home');

// Profile routes
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::middleware('unit.access')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard/chart-data', [App\Http\Controllers\DashboardController::class, 'getChartData'])->name('dashboard.chart-data');
        Route::get('/dashboard/near-expiry', [App\Http\Controllers\DashboardController::class, 'nearExpiryList'])->name('dashboard.near-expiry');
    });

    // Profile Management
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.password');

    // Log Penyimpanan Limbah
    Route::middleware('unit.access')->group(function () {
        Route::resource('log-penyimpanan', App\Http\Controllers\LogPenyimpananLimbahController::class);
        Route::put('/log-penyimpanan/{logPenyimpanan}/transport', [App\Http\Controllers\LogPenyimpananLimbahController::class, 'markTransported'])->name('log-penyimpanan.transport');
    });

    // Pengangkutan Limbah Routes
    Route::middleware('unit.access')->group(function () {
        Route::get('pengangkutan-limbah', [App\Http\Controllers\PengangkutanLimbahController::class, 'index'])
            ->name('pengangkutan-limbah.index');
        Route::get('pengangkutan-limbah/diangkut', [App\Http\Controllers\PengangkutanLimbahController::class, 'diangkut'])
            ->name('pengangkutan-limbah.diangkut');
        Route::post('pengangkutan-limbah/{id}/approve', [App\Http\Controllers\PengangkutanLimbahController::class, 'approve'])
            ->name('pengangkutan-limbah.approve');
        Route::post('pengangkutan-limbah/bulk-approve', [App\Http\Controllers\PengangkutanLimbahController::class, 'bulkApprove'])
            ->name('pengangkutan-limbah.bulk-approve');
    });

    // User Management
    Route::middleware('unit.access')->group(function () {
        Route::resource('pengguna-sistem', App\Http\Controllers\PenggunaSistemController::class);
        Route::patch('/pengguna-sistem/{penggunaSistem}/toggle-status', [App\Http\Controllers\PenggunaSistemController::class, 'toggleStatus'])->name('pengguna-sistem.toggle-status');
    });

    // Role Management
    Route::resource('peran-pengguna', App\Http\Controllers\PeranPenggunaController::class);
    Route::patch('/peran-pengguna/{peran_pengguna}/toggle-status', [App\Http\Controllers\PeranPenggunaController::class, 'toggleStatus'])->name('peran-pengguna.toggle-status');

    // Master Data Management
    Route::resource('jenis-limbah', App\Http\Controllers\JenisLimbahController::class);
    Route::resource('perusahaan-penghasil', App\Http\Controllers\PerusahaanPenghasilController::class);
    Route::resource('karakteristik-limbah', App\Http\Controllers\KarakteristikLimbahController::class);
    Route::resource('kategori-kegiatan-sumber', App\Http\Controllers\KategoriKegiatanSumberController::class);
    Route::middleware('unit.access')->resource('unit-pembangkit', App\Http\Controllers\UnitPembangkitController::class);

    // Application Settings Management
    Route::resource('application-settings', App\Http\Controllers\ApplicationSettingController::class);
    Route::post('/application-settings/clear-cache', [App\Http\Controllers\ApplicationSettingController::class, 'clearCache'])->name('application-settings.clear-cache');

    // Report Management
    Route::middleware('unit.access')->prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [App\Http\Controllers\ReportController::class, 'index'])->name('index');

        // Monthly/Yearly Reports
        Route::get('/monthly', [App\Http\Controllers\ReportController::class, 'monthly'])->name('monthly');
        Route::get('/monthly/export/{format}', [App\Http\Controllers\ReportController::class, 'exportMonthly'])->name('monthly.export');

        // Status Reports
        Route::get('/status', [App\Http\Controllers\ReportController::class, 'status'])->name('status');
        Route::get('/status/export/{format}', [App\Http\Controllers\ReportController::class, 'exportStatus'])->name('status.export');

        // Waste Type Reports
        Route::get('/waste-type', [App\Http\Controllers\ReportController::class, 'wasteType'])->name('waste-type');
        Route::get('/waste-type/export/{format}', [App\Http\Controllers\ReportController::class, 'exportWasteType'])->name('waste-type.export');

        // Company Reports
        Route::get('/company', [App\Http\Controllers\ReportController::class, 'company'])->name('company');
        Route::get('/company/export/{format}', [App\Http\Controllers\ReportController::class, 'exportCompany'])->name('company.export');

        // Unit Reports
        Route::get('/unit', [App\Http\Controllers\ReportController::class, 'unit'])->name('unit');
        Route::get('/unit/export/{format}', [App\Http\Controllers\ReportController::class, 'exportUnit'])->name('unit.export');

        // Cache Management
        Route::post('/clear-cache', [App\Http\Controllers\ReportController::class, 'clearCache'])->name('clear-cache');
    });

    // Expiry Settings Management (Super Admin only)
    Route::prefix('expiry-settings')->name('expiry-settings.')->group(function () {
        Route::get('/', [App\Http\Controllers\ExpirySettingsController::class, 'index'])->name('index');
        Route::put('/update', [App\Http\Controllers\ExpirySettingsController::class, 'update'])->name('update');
        Route::post('/reset', [App\Http\Controllers\ExpirySettingsController::class, 'reset'])->name('reset');
    });

    // Expiry Report Management
    Route::middleware('unit.access')->prefix('expiry-reports')->name('expiry-reports.')->group(function () {
        Route::get('/', [App\Http\Controllers\ExpiryReportController::class, 'index'])->name('index');
        Route::get('/dashboard', [App\Http\Controllers\ExpiryReportController::class, 'dashboard'])->name('dashboard');
        Route::get('/export', [App\Http\Controllers\ExpiryReportController::class, 'export'])->name('export');
    });

    // Notification Management
    Route::middleware('unit.access')->prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [App\Http\Controllers\NotificationController::class, 'index'])->name('index');
        Route::get('/count', [App\Http\Controllers\NotificationController::class, 'getCount'])->name('count');
        Route::get('/get-count', [App\Http\Controllers\NotificationController::class, 'getCount'])->name('get-count');
        Route::get('/get-expiry-notifications', [App\Http\Controllers\NotificationController::class, 'getExpiryNotifications'])->name('get-expiry-notifications');
        Route::get('/settings', [App\Http\Controllers\NotificationController::class, 'getSettings'])->name('settings');
        Route::post('/{id}/mark-read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('mark-read');
    });
});
