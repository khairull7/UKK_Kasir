<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    // Menampilkan form login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Menghandle login
    public function login(Request $request)
    {
        // Validasi input form
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);
    
        // Mengecek kredensial
        if (Auth::attempt(['email' => $validated['email'], 'password' => $validated['password']])) {
            // Mendapatkan pengguna yang terautentikasi
            $user = Auth::user();
    
    
            // Jika berhasil login, arahkan ke dashboard berdasarkan role
            if ($user->role == 'admin') {
                return redirect()->route('admin.dashboard'); // Ganti dengan rute dashboard admin
            } elseif ($user->role == 'petugas') {
                return redirect()->route('petugas.dashboard'); // Ganti dengan rute dashboard petugas
            }
    
            // Jika tidak ada role yang ditemukan, arahkan ke halaman default
            return redirect()->intended('/dashboard');
        }
    
        // Jika login gagal
        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ]);
    }
    

    // Logout pengguna
    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }

}
