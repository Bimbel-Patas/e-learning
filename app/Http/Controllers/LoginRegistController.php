<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\DataSiswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginRegistController extends Controller
{
    /**
     * Menampilkan halaman login.
     *
     * @return \Illuminate\View\View
     */
    public function viewLogin()
    {
        // Periksa ketersediaan akun admin
        $adminAvailability = User::where('roles_id', 1)->first();

        // Jika ada admin, set variabel $checker ke 1, jika tidak, ke 0
        if ($adminAvailability != null) {
            $checker = 1;
        } else {
            $checker = 0;
        }

        // Tampilkan halaman login dengan variabel hasAdmin yang mengindikasikan ketersediaan admin
        return view('loginRegist/login/login', ['title' => 'Login', 'hasAdmin' => $checker]);
    }

    /**
     * Menampilkan halaman registrasi.
     *
     * @return \Illuminate\View\View
     */
    public function viewRegister()
    {
        return view('loginRegist/register/register', ['title' => 'Register']);
    }

    /**
     * Menangani proses registrasi pengguna baru.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(Request $request)
    {
        // Validasi data yang dikirimkan oleh form registrasi
        $request->validate([
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'confirm-password' => 'required|min:8|same:password',
            'nis' => 'required',
        ]);

        // Ambil data siswa berdasarkan NIS
        $dataSiswa = DataSiswa::get('nis');

        foreach ($dataSiswa as $key) {
            // Jika NIS cocok dengan yang diinputkan
            if ($key['nis'] == $request->nis) {
                $dataSiswa2 = DataSiswa::where('nis', $request->nis)->first();

                // Jika siswa belum memiliki akun
                if ($dataSiswa2['punya_akun'] == 0) {
                    // Ambil kelas siswa
                    $kelasSiswa = $dataSiswa2['kelas_id'];

                    // Buat data untuk user baru
                    $data = [
                        'name' => $dataSiswa2['name'],
                        'roles_id' => 3, // 3 = Siswa
                        'kelas_id' => $kelasSiswa,

                        'gambar' => null,
                        'email' => $request->email,
                        'password' => Hash::make($request->password),
                    ];

                    // Simpan data user baru
                    User::create($data);

                    // Ambil ID user yang baru dibuat
                    $user_id = User::where('email', $request->email)->first();

                    // Update status punya akun siswa
                    $data = [
                        'user_id' => $user_id->id,
                        'punya_akun' => 1,
                    ];
                    DataSiswa::where('nis', $request->nis)->update($data);

                    // Buat data kontak untuk user
                    Contact::create(['user_id' => $user_id->id, 'no_telp' => $request->noTelp]);

                    // Redirect ke halaman login dengan pesan sukses
                    return redirect('/login')->with('register-success', 'Registrasi Berhasil');
                } else {
                    // Jika siswa sudah memiliki akun
                    return back()->with('nis-error', 'NIS (Nomor Induk Siswa) Sudah digunakan.');
                }
            }
        }

        // Jika NIS tidak ditemukan
        return back()->with('nis-error', 'NIS (Nomor Induk Siswa) Tidak ditemukan');
    }

    /**
     * Menangani proses otentikasi pengguna.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function authenticate(Request $request)
    {
        // Validasi email dan password yang dikirimkan oleh form login
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Coba untuk melakukan otentikasi dengan email dan password
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Intended digunakan untuk melewati Middleware dan mengarahkan ke halaman yang dimaksud
            return redirect()->intended(route('dashboard'));
        } else {
            // Jika otentikasi gagal, kirim pesan error
            return back()->with('login-error', 'Email atau Kata Sandi salah!');
        }
    }

    /**
     * Menangani proses keluar (logout) pengguna.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        if (auth()) {
            Auth::logout();

            $request->session()->invalidate();

            $request->session()->regenerateToken();

            // Redirect ke halaman login dengan pesan logout sukses
            return redirect(route('login'))->with('logout-success', 'Berhasil keluar!');
        } else {
            // Jika tidak ada pengguna yang terautentikasi, redirect ke halaman login
            return redirect(route('login'));
        }
    }

    /**
     * Menampilkan halaman lupa kata sandi.
     *
     * @return \Illuminate\View\View
     */
    public function viewForgotPassword()
    {
        return view('loginRegist/forgot-password/forgotPassword', ['title' => 'Forgot Password']);
    }
}
