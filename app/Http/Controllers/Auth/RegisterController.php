<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PenggunaSistem;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'email_address' => ['required', 'string', 'email', 'max:255', 'unique:pengguna_sistem,email_address'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'unit_id' => ['required', 'exists:unit_pembangkit,unit_id'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @return \App\Models\PenggunaSistem
     */
    protected function create(array $data)
    {
        return PenggunaSistem::create([
            'nama_lengkap' => $data['nama_lengkap'],
            'email_address' => $data['email_address'],
            'kata_sandi_hash' => Hash::make($data['password']),
            'aktif' => 1,
            'unit_id' => $data['unit_id'],
        ]);
    }
}
