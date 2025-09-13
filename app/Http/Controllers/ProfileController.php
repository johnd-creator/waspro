<?php

namespace App\Http\Controllers;

use App\Models\PenggunaSistem;
use App\Models\UnitPembangkit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the user profile form.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function show()
    {
        $user = Auth::user();
        $unitList = UnitPembangkit::all();

        return view('profile.show', compact('user', 'unitList'));
    }

    /**
     * Update the user's profile information.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'email_address' => ['required', 'string', 'email', 'max:255', 'unique:pengguna_sistem,email_address,'.$user->user_id.',user_id'],
            'unit_id' => ['required', 'exists:unit_pembangkit,unit_id'],
        ]);

        PenggunaSistem::where('user_id', $user->user_id)->update([
            'nama_lengkap' => $request->nama_lengkap,
            'email_address' => $request->email_address,
            'unit_id' => $request->unit_id,
        ]);

        return redirect()->route('profile.show')->with('success', 'Profile updated successfully!');
    }

    /**
     * Update the user's password.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = Auth::user();

        if (! Hash::check($request->current_password, $user->kata_sandi_hash)) {
            return back()->withErrors([
                'current_password' => 'The provided password does not match your current password.',
            ]);
        }

        PenggunaSistem::where('user_id', $user->user_id)->update([
            'kata_sandi_hash' => Hash::make($request->password),
        ]);

        return redirect()->route('profile.show')->with('success', 'Password updated successfully!');
    }
}
