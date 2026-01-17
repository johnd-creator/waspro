<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController as ApiDashboardController;
use App\Http\Controllers\Api\JenisLimbahController;
use App\Http\Controllers\Api\KarakteristikLimbahController;
use App\Http\Controllers\Api\KategoriKegiatanSumberController;
use App\Http\Controllers\Api\LogPenyimpananController;
use App\Http\Controllers\Api\PerusahaanPenghasilController;
use App\Http\Controllers\Api\UnitPembangkitController;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthController::class, 'login'])
    ->middleware('throttle:api')
    ->name('api.login');

Route::middleware(['auth:sanctum', 'throttle:api'])->group(function (): void {
    Route::post('logout', [AuthController::class, 'logout'])->name('api.logout');

    Route::apiResource('jenis-limbah', JenisLimbahController::class)->only(['index', 'show'])->names([
        'index' => 'api.jenis-limbah.index',
        'show' => 'api.jenis-limbah.show',
    ]);

    Route::apiResource('karakteristik-limbah', KarakteristikLimbahController::class)->only(['index', 'show'])->names([
        'index' => 'api.karakteristik-limbah.index',
        'show' => 'api.karakteristik-limbah.show',
    ]);

    Route::apiResource('kategori-kegiatan-sumber', KategoriKegiatanSumberController::class)->only(['index', 'show'])->names([
        'index' => 'api.kategori-kegiatan-sumber.index',
        'show' => 'api.kategori-kegiatan-sumber.show',
    ]);

    Route::apiResource('unit-pembangkit', UnitPembangkitController::class)->names([
        'index' => 'api.unit-pembangkit.index',
        'store' => 'api.unit-pembangkit.store',
        'show' => 'api.unit-pembangkit.show',
        'update' => 'api.unit-pembangkit.update',
        'destroy' => 'api.unit-pembangkit.destroy',
    ]);

    Route::apiResource('perusahaan-penghasil', PerusahaanPenghasilController::class)->names([
        'index' => 'api.perusahaan-penghasil.index',
        'store' => 'api.perusahaan-penghasil.store',
        'show' => 'api.perusahaan-penghasil.show',
        'update' => 'api.perusahaan-penghasil.update',
        'destroy' => 'api.perusahaan-penghasil.destroy',
    ]);

    // Log penyimpanan API: CRUD + delta + bulk sync + approval
    Route::apiResource('log-penyimpanan', LogPenyimpananController::class)->names([
        'index' => 'api.log-penyimpanan.index',
        'store' => 'api.log-penyimpanan.store',
        'show' => 'api.log-penyimpanan.show',
        'update' => 'api.log-penyimpanan.update',
        'destroy' => 'api.log-penyimpanan.destroy',
    ]);
    Route::post('sync/logs', [LogPenyimpananController::class, 'sync'])->name('api.sync.logs');

    // Approval API for supervisor
    Route::post('log-penyimpanan/{id}/approve', [LogPenyimpananController::class, 'approve'])->name('api.log-penyimpanan.approve');
    Route::post('log-penyimpanan/{id}/reject', [LogPenyimpananController::class, 'reject'])->name('api.log-penyimpanan.reject');

    // Dashboard summary for mobile apps
    Route::get('dashboard/summary', [ApiDashboardController::class, 'summary'])->name('api.dashboard.summary');
});
