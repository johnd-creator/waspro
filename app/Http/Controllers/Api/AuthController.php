<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PenggunaSistem;
use App\Support\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Handle an API login request and return a Sanctum token.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email_address' => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [], [
            'email_address' => 'email',
            'password' => 'kata sandi',
        ]);

        $user = PenggunaSistem::where('email_address', $credentials['email_address'])
            ->where('aktif', true)
            ->first();

        if (! $user || ! Hash::check($credentials['password'], $user->kata_sandi_hash)) {
            return ApiResponse::error(
                'Kredensial tidak valid.',
                401,
                [
                    'email_address' => ['Kredensial yang diberikan tidak cocok dengan catatan kami.'],
                ]
            );
        }

        $token = $user->createToken('api-token')->plainTextToken;
        $user->load('unitPembangkit:unit_id,nama_unit');
        $roles = DB::table('pengguna_peran')
            ->join('peran_pengguna', 'peran_pengguna.peran_id', '=', 'pengguna_peran.peran_id')
            ->where('pengguna_peran.user_id', $user->user_id)
            ->pluck('peran_pengguna.nama_peran')
            ->all();

        return ApiResponse::success([
            'token' => $token,
            'token_type' => 'Bearer',
            'user' => [
                'id' => $user->user_id,
                'name' => $user->nama_lengkap,
                'email' => $user->email_address,
                'unit' => $user->unitPembangkit
                    ? [
                        'id' => $user->unitPembangkit->unit_id,
                        'name' => $user->unitPembangkit->nama_unit,
                    ]
                    : null,
                'roles' => $roles,
            ],
        ], 'Login berhasil.');
    }

    /**
     * Revoke the current access token.
     */
    public function logout(Request $request)
    {
        $user = $request->user();

        if (! $user) {
            return ApiResponse::unauthorized();
        }

        $token = $user->currentAccessToken();

        if ($token) {
            $token->delete();
        } else {
            $user->tokens()->delete();
        }

        return ApiResponse::success(null, 'Logout berhasil.');
    }
}
