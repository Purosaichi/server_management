<?php


namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (session()->has('user_id')) {
            return redirect()->route('dashboard');
        }

        return view('pages.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user) {
            return back()->withInput()->with('error', 'Email tidak ditemukan di database.');
        }

        $status = strtolower(trim((string) $user->status_pengguna));
        if ($status !== '' && ! in_array($status, ['aktif', 'active', '1'], true)) {
            return back()->withInput()->with('error', 'Akun Anda tidak aktif.');
        }

        if (! Hash::check($request->password, $user->kata_sandi)) {
            return back()->withInput()->with('error', 'Password salah.');
        }

        $request->session()->regenerate();
        $request->session()->put('user_id', $user->id_akun);
        $request->session()->put('user_name', $user->nama_pengguna);
        $request->session()->put('user_login', $user->email);
        $request->session()->put('email', $user->email);

        return redirect()->route('dashboard');
    }

    public function logout(Request $request)
    {
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
