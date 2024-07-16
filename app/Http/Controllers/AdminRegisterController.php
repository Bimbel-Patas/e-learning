<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * Class : AdminRegisterController
 *
 * Kelas ini mengelola pembuatan akun admin.
 *
 * @copyright  2023 Sunday Interactive
 * @license    http://www.zend.com/license/3_0.txt   PHP License 3.0
 *
 * @version    Release: 1.0
 *
 * @link       http://dev.zend.com/package/PackageName
 * @since      Kelas ini tersedia sejak Rilis 1.0
 */
class AdminRegisterController extends Controller
{
    /**
     * Menampilkan halaman registrasi admin jika belum ada admin yang terdaftar.
     *
     * @return \Illuminate\View\View
     */
    public function viewAdminRegister()
    {
        // Cek apakah sudah ada admin atau belum.
        $adminAvailability = User::where('roles_id', 1)->first();

        if ($adminAvailability != null) {
            // Jika sudah ada admin, tampilkan error 404.
            return abort(404);
        } else {
            // Jika belum ada admin, tampilkan halaman registrasi admin.
            return view('loginRegist/defining-admin/registerAdmin', ['title' => 'Admin Register']);
        }
    }

    /**
     * Memvalidasi dan membuat akun admin berdasarkan data yang dimasukkan.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function registAdmin(Request $request)
    {
        // Lakukan validasi Data
        $request->validate([
            'nama' => 'required|min:5',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'confirm-password' => 'required|min:8|same:password',
        ]);

        // Data yang akan dibuat sebagai akun admin
        $data = [
            'name' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'roles_id' => '1',
            'kelas_id' => null,
        ];

        // Membuat akun admin berdasarkan data yang dimasukkan
        User::create($data);

        $contact = [
            'user_id' => 1,
        ];
        Contact::create($contact);

        // Redirect ke halaman login dengan pesan sukses registrasi.
        return redirect('/login')->with('register-success', 'Berhasil Mendaftarkan Akun Admin');
    }

    public function debug(Request $request)
    {
        dd($request);
    }
}
