<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UnitAccessMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah user sudah login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        // Jika user adalah Super Admin, beri akses penuh tanpa filter
        if ($this->isSuperAdmin($user)) {
            return $next($request);
        }
        
        // Untuk Administrator dan role lainnya, tambahkan filter unit
        $request->merge(['user_unit_id' => $user->unit_id]);
        
        // Cek akses berdasarkan role dan resource
        $this->checkRoleBasedAccess($request, $user);
        
        return $next($request);
    }
    
    /**
     * Check role-based access to resources
     */
    private function checkRoleBasedAccess(Request $request, $user)
    {
        $route = $request->route();
        
        if (!$route) {
            return;
        }
        
        $routeName = $route->getName();
        $parameters = $route->parameters();
        $method = $request->method();
        
        // Cek akses berdasarkan role untuk berbagai resource
        $this->checkLogPenyimpananAccess($routeName, $parameters, $user, $method);
        $this->checkPenggunaSistemAccess($routeName, $parameters, $user, $method);
        $this->checkUnitPembangkitAccess($routeName, $parameters, $user, $method);
        $this->checkMasterDataAccess($routeName, $user, $method);
    }
    
    /**
     * Check access to log penyimpanan based on role
     */
    private function checkLogPenyimpananAccess($routeName, $parameters, $user, $method)
    {
        if (!str_contains($routeName, 'log-penyimpanan')) {
            return;
        }
        
        // Viewer hanya bisa melihat (GET)
        if ($this->isViewer($user) && !in_array($method, ['GET', 'HEAD'])) {
            abort(403, 'Anda hanya memiliki akses untuk melihat data.');
        }
        
        // Cek akses unit untuk resource spesifik
        if (isset($parameters['logPenyimpanan'])) {
            $logPenyimpanan = $parameters['logPenyimpanan'];
            if ($logPenyimpanan->unit_id !== $user->unit_id) {
                abort(403, 'Anda tidak memiliki akses untuk melihat data dari unit lain.');
            }
        }
    }
    
    /**
     * Check access to pengguna sistem based on role
     */
    private function checkPenggunaSistemAccess($routeName, $parameters, $user, $method)
    {
        if (!str_contains($routeName, 'pengguna-sistem')) {
            return;
        }
        
        // Operator tidak bisa mengakses manajemen pengguna
        if ($this->isOperator($user)) {
            abort(403, 'Anda tidak memiliki akses untuk mengelola pengguna sistem.');
        }
        
        // Viewer hanya bisa melihat
        if ($this->isViewer($user) && !in_array($method, ['GET', 'HEAD'])) {
            abort(403, 'Anda hanya memiliki akses untuk melihat data.');
        }
        
        // Cek akses unit untuk resource spesifik
        if (isset($parameters['penggunaSistem'])) {
            $penggunaSistem = $parameters['penggunaSistem'];
            if ($penggunaSistem->unit_id !== $user->unit_id) {
                abort(403, 'Anda tidak memiliki akses untuk mengelola pengguna dari unit lain.');
            }
        }
    }
    
    /**
     * Check access to unit pembangkit based on role
     */
    private function checkUnitPembangkitAccess($routeName, $parameters, $user, $method)
    {
        if (!str_contains($routeName, 'unit-pembangkit')) {
            return;
        }
        
        // Hanya Administrator dan Super Admin yang bisa mengelola unit
        if (!$this->isAdministrator($user) && !$this->isSuperAdmin($user)) {
            abort(403, 'Anda tidak memiliki akses untuk mengelola unit pembangkit.');
        }
        
        // Administrator hanya bisa mengelola unit sendiri
        if ($this->isAdministrator($user) && isset($parameters['unitPembangkit'])) {
            $unitPembangkit = $parameters['unitPembangkit'];
            if ($unitPembangkit->unit_id !== $user->unit_id) {
                abort(403, 'Anda tidak memiliki akses untuk mengelola unit lain.');
            }
        }
    }
    
    /**
     * Check access to master data based on role
     */
    private function checkMasterDataAccess($routeName, $user, $method)
    {
        $masterDataRoutes = ['jenis-limbah', 'karakteristik-limbah', 'kategori-kegiatan', 'cabang-perusahaan'];
        
        foreach ($masterDataRoutes as $masterRoute) {
            if (str_contains($routeName, $masterRoute)) {
                // Operator dan Viewer tidak bisa mengubah master data
                if (($this->isOperator($user) || $this->isViewer($user)) && !in_array($method, ['GET', 'HEAD'])) {
                    abort(403, 'Anda tidak memiliki akses untuk mengubah master data.');
                }
                break;
            }
        }
    }
    
    /**
     * Check if user is Super Admin
     */
    private function isSuperAdmin($user): bool
    {
        return $user->peranPengguna()->where('peran_pengguna.nama_peran', 'Super Admin')->exists();
    }
    
    /**
     * Check if user is Administrator
     */
    private function isAdministrator($user): bool
    {
        return $user->peranPengguna()->where('peran_pengguna.nama_peran', 'Administrator')->exists() ||
               $user->peranPengguna()->where('peran_pengguna.nama_peran', 'Admin')->exists(); // Backward compatibility
    }
    
    /**
     * Check if user is Operator
     */
    private function isOperator($user): bool
    {
        return $user->peranPengguna()->where('peran_pengguna.nama_peran', 'Operator')->exists();
    }
    
    /**
     * Check if user is Viewer
     */
    private function isViewer($user): bool
    {
        return $user->peranPengguna()->where('peran_pengguna.nama_peran', 'Viewer')->exists();
    }
    
    /**
     * Check if user is admin (Administrator or Super Admin)
     */
    private function isAdmin($user)
    {
        return $this->isAdministrator($user) || $this->isSuperAdmin($user);
    }
}