<?php

namespace App\Http\Controllers;

use App\Exports\SiswaExport;
use App\Imports\SiswaImport;
use App\Models\Contact;
use App\Models\DataSiswa;
use App\Models\Kelas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

/**
 * Class : DataSiswaController
 *
 * Class ini memuat beragam Function yang mengacu terhadap Manipulasi, Pencarian, Delete, Create Siswa. Function is on Unordered List.
 *
 * @copyright  2023 Sunday Interactive
 * @license    http://www.zend.com/license/3_0.txt   PHP License 3.0
 *
 * @version    Release: 1.0
 *
 * @link       http://dev.zend.com/package/PackageName
 * @since      Class available since Release 1.0
 */
class DataSiswaController extends Controller
{
    /**
     * Menampilkan halaman daftar siswa dengan opsi pagination.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function viewSiswa()
    {
        // Memanggil class DashboardController dan mengambil Roles name.
        $roles = DashboardController::getRolesName();

        // Menampilkan halaman daftar siswa dengan data siswa yang dipaginasi (15 per halaman) dan daftar kelas.
        return view('menu.admin.controlSiswa.viewSiswa', ['title' => 'Data Siswa', 'roles' => $roles, 'siswa' => DataSiswa::paginate(15), 'dataKelas' => Kelas::get()]);
    }

    /**
     * Melakukan pencarian siswa berdasarkan nama dengan opsi pemfilteran berdasarkan kelas.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function searchSiswa(Request $request)
    {
        // Mendefinisikan variabel untuk pencarian dan kelas.
        $search = $request->input('search');
        $kelas = $request->input('kelas');

        // Pengkondisian
        // Apakah data kelas disertakan dalam request (1 atau 2 parameter)?
        if ($request->kelas != null) {
            // Jika terdapat 2 parameter (nama dan kelas), lakukan pencarian berdasarkan nama dan kelas.
            $siswa = DataSiswa::where('name', 'like', '%' . $search . '%')->where('kelas_id', $request->kelas)->paginate(15);
        } else {
            // Jika hanya terdapat 1 parameter (hanya nama), lakukan pencarian berdasarkan nama saja.
            $siswa = DataSiswa::where('name', 'like', '%' . $search . '%')->paginate(15);
        }

        if ($request->ajax()) {
            // Jika request berasal dari AJAX, kembalikan tampilan hasil pencarian siswa.
            return view('menu.admin.controlSiswa.partials.siswaTable', compact('siswa', 'search', 'kelas'))->render();
        }

        // Jika bukan request AJAX, tampilkan tampilan hasil pencarian siswa.
        return view('menu.admin.controlSiswa.partials.siswaTable', compact('siswa', 'search', 'kelas'))->render();
    }

    /**
     * Menampilkan halaman tambah siswa dengan daftar kelas yang tersedia.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function viewTambahSiswa()
    {
        // Memanggil class DashboardController dan mengambil Roles name.
        $roles = DashboardController::getRolesName();

        // Menampilkan halaman tambah siswa dengan daftar peran (roles) dan daftar kelas yang tersedia.
        return view('menu.admin.controlSiswa.viewTambahSiswa', ['title' => 'Tambah Siswa', 'roles' => $roles, 'dataKelas' => Kelas::get()]);
    }

    /**
     * Memvalidasi dan menyimpan data siswa yang ditambahkan.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function validateDataSiswa(Request $request)
    {
        // Validasi Data
        $request->validate([
            'nama' => 'required',
            'kelas' => 'required',
            'nis' => 'required|unique:data_siswas',
        ]);

        // Membangun array data siswa berdasarkan input dari request.
        $data = [
            'name' => $request->nama,
            'kelas_id' => $request->kelas,
            'nis' => $request->nis,
        ];

        // Membuat baris data siswa dalam tabel.
        DataSiswa::create($data);

        // Membangun array data untuk sesi (session).
        $dataSession = [
            'prompt' => 'diTambahkan!',
            'action' => 'Tambah',
        ];

        // Menyimpan data dalam sesi.
        session(['data' => $dataSession]);

        // Mengarahkan ke rute 'dataSiswaSuccess' untuk menampilkan pesan sukses.
        return redirect(route('dataSiswaSuccess'));
    }

    /**
     * Menampilkan halaman pembaruan data siswa dengan data siswa yang akan diperbarui.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function viewUpdateDataSiswa(DataSiswa $DataSiswa)
    {
        // Memanggil class DashboardController dan mengambil Roles name.
        $roles = DashboardController::getRolesName();

        // Menampilkan halaman pembaruan data siswa dengan data siswa yang akan diperbarui serta daftar kelas yang tersedia.
        return view('menu.admin.controlSiswa.updateSiswa', ['title' => 'Update Siswa', 'roles' => $roles, 'siswa' => $DataSiswa, 'dataKelas' => Kelas::get()]);
    }

    /**
     * Menghapus data siswa dan, jika ada, akun pengguna terkait.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroyDataSiswa(Request $request)
    {
        // Mengambil data siswa berdasarkan ID yang diberikan dalam request.
        $siswa = DataSiswa::where('id', $request->idHapus)->first();

        // Jika siswa memiliki akun terkait (punya_akun == 1), hapus akun tersebut.
        if ($siswa['punya_akun'] == 1) {
            $userId = $siswa['user_id'];
            User::where('id', $userId)->delete();
        }

        // Menghapus data siswa berdasarkan ID yang diberikan dalam request.
        DataSiswa::destroy($request->idHapus);

        // Mengarahkan kembali ke halaman sebelumnya dengan pesan sukses.
        return redirect()->back()->with('delete-success', 'Berhasil menghapus DataSiswa!');
    }

    /**
     * Memperbarui data siswa, termasuk pengelolaan akun pengguna jika sudah ada.
     *
     * @return \Illuminate\Http\Response
     */
    public function updateDataSiswa(Request $request)
    {
        // Melakukan validasi data yang dikirimkan melalui request.
        $request->validate([
            'nama' => 'required',
            'kelas' => 'required',
            'nis' => 'required',
        ]);

        // Mengambil data siswa yang akan diperbarui berdasarkan ID yang diberikan dalam request.
        $siswa = DataSiswa::where('id', $request->id)->first();

        // Jika siswa memiliki akun terkait (punya_akun == 1), perbarui informasi akun jika diperlukan.
        if ($siswa['punya_akun'] == 1) {
            $oldKelas = User::where('id', $siswa['user_id'])->first();

            if ($request->kelas != $oldKelas['kelas_id'] || $request->nama != $siswa['nama']) {
                User::where('id', $siswa['user_id'])->update(['kelas_id' => $request->kelas, 'name' => $request->nama]);
            }
        }

        // Jika NIS diubah, lakukan validasi keunikan NIS.
        if ($siswa['nis'] != $request->nis) {
            $request->validate(['nis' => 'unique:data_siswas']);
        }

        // Menyiapkan data yang akan diperbarui dan melakukan pembaruan pada data siswa.
        $data = [
            'name' => $request->nama,
            'kelas_id' => $request->kelas,
            'nis' => $request->nis,
        ];

        DataSiswa::where('id', $request->id)->update($data);

        // Mengarahkan kembali ke halaman sebelumnya dengan pesan sukses.
        return redirect()->back()->with('success', 'Update berhasil!');
    }

    /**
     * Menampilkan halaman sukses impor data siswa.
     *
     * @return \Illuminate\View\View
     */
    public function dataSiswaSuccess()
    {
        // Memeriksa apakah ada data yang disimpan dalam sesi.
        if (session('data') != null) {
            $data = session('data');
            session()->forget('data');
            $roles = DashboardController::getRolesName();

            // Menampilkan halaman dengan data yang berhasil diimpor.
            return view('menu.admin.controlSiswa.dataSukses', ['title' => 'Sukses', 'roles' => $roles, 'data' => $data]);
        } else {
            // Menampilkan halaman 404 jika tidak ada data yang tersedia.
            abort(404);
        }
    }

    /**
     * Mengunduh data siswa dalam format Excel.
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function export()
    {
        return Excel::download(new SiswaExport, 'export-siswa.xls');
    }

    /**
     * Mengimpor data siswa dari file Excel.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function import(Request $request)
    {
        // Melakukan validasi jenis file yang diizinkan.
        $request->validate([
            'file' => 'required|mimes:xlsx,xls', // Sesuaikan dengan jenis file Excel yang diizinkan
        ]);
        session()->forget('imported_ids', []);

        // Proses impor data dari Excel.
        try {
            Excel::import(new SiswaImport, $request->file('file')); // Gantilah dengan nama sesuai nama kelas impor Anda
            $ids = session()->get('imported_ids');
            DataSiswa::whereNotIn('id', $ids)->delete();
            // Hapus akses editor dll

            return redirect()->route('viewSiswa')->with('import-success', 'Data Siswa berhasil diimpor.');
        } catch (\Exception $e) {
            return redirect()->route('viewSiswa')->with('import-error', 'Error : ' . $e->getMessage());
        }
    }

    /**
     * Mengunduh contoh data siswa dalam format Excel.
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function contohSiswa()
    {
        // File Excel disimpan dalam direktori ini.
        $file = public_path('/examples/contoh-data-siswa.xls');

        return response()->download($file, 'contoh-siswa.xls');
    }

    /**
     * Menampilkan halaman pembaruan profil pengguna siswa.
     *
     * @param  string  $token
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function viewUpdateUserSiswa($token)
    {
        try {
            // Mendekripsi token dan mengambil ID pengguna.
            $id = Crypt::decrypt($token);
            $roles = DashboardController::getRolesName();

            // Mengambil data profil pengguna, kontak, dan daftar kelas.
            $profile = User::findOrFail($id);
            $contact = Contact::where('user_id', $id)->first();
            $kelas = Kelas::get();

            // Menampilkan halaman pembaruan profil dengan data yang diperlukan.
            return view('menu.admin.controlSiswa.user.updateUser', ['user' => $profile, 'contact' => $contact, 'kelas' => $kelas, 'roles' => $roles, 'title' => 'Profile']);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            // Menampilkan halaman 404 jika terjadi kesalahan dalam mendekripsi token.
            abort(404);
        }
    }

    /**
     * Memperbarui data profil pengguna (siswa, pengajar, atau admin).
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateUserSiswa(Request $request)
    {
        // Mengambil data dari request.
        $data = $request->all();

        if (Auth()->User()->roles_id == 1) {
            // Untuk Admin

            // Mendapatkan data yang diperlukan (nama, jenis kelamin, email, dan password jika diubah).
            $nama = $data['nama'];
            $email = $data['email'];

            if ($data['password'] != null) {
                $password = bcrypt($data['password']); // Mengenkripsi password jika diubah.

                // Memperbarui data pengguna (nama, jenis kelamin, email, dan password jika diubah).
                $temp = [
                    'name' => $nama,
                    'email' => $email,
                    'password' => $password,

                ];

                User::where('id', $data['id'])->update($temp);

                // Mengubah jenis kelamin menjadi format yang sesuai.

                $temp2 = [
                    'name' => $nama,

                ];

                // Memperbarui data siswa.
                DataSiswa::where('user_id', $data['id'])->update($temp2);
            } else {
                // Jika password tidak diubah, hanya memperbarui data pengguna (nama, jenis kelamin, email).
                $temp = User::where('id', $data['id'])->update([
                    'name' => $nama,
                    'email' => $email,
                ]);

                $temp2 = [
                    'name' => $nama,
                ];

                // Memperbarui data siswa.
                DataSiswa::where('user_id', $data['id'])->update($temp2);
            }

            // Memperbarui data kontak (nomor telepon).
            Contact::where('user_id', $data['id'])->update([
                'no_telp' => $request->noTelp,
            ]);

            // Melakukan commit transaksi database.
            DB::commit();

            // Mengarahkan kembali dengan pesan sukses.
            return redirect()->back()->with('success', 'Update berhasil!');
        } elseif (Auth()->User()->roles_id == 1 && Auth()->User()->id == $data['id']) {
            // Untuk Siswa

            // Mendapatkan data email.
            $email = $data['email'];

            // Memperbarui data pengguna (email).
            $temp = User::where('id', $data['id'])->update([
                'email' => $email,
            ]);

            // Memperbarui data kontak (nomor telepon).
            Contact::where('user_id', $data['id'])->update([
                'no_telp' => $request->noTelp,
            ]);

            // Melakukan commit transaksi database.
            DB::commit();

            // Mengarahkan kembali dengan pesan sukses.
            return redirect()->back()->with('success', 'Update berhasil!');
        } elseif (Auth()->User()->roles_id == 2 && Auth()->User()->id == $data['id']) {
            // Untuk Pengajar

            // Mendapatkan data email dan deskripsi.
            $email = $data['email'];
            $deskripsi = $data['deskripsi'];

            // Memperbarui data pengguna (email dan deskripsi).
            $temp = User::where('id', $data['id'])->update([
                'email' => $email,
                'deskripsi' => $deskripsi,
            ]);

            // Memperbarui data kontak (nomor telepon).
            Contact::where('user_id', $data['id'])->update([
                'no_telp' => $request->noTelp,
            ]);

            // Melakukan commit transaksi database.
            DB::commit();

            // Mengarahkan kembali dengan pesan sukses.
            return redirect()->back()->with('success', 'Update berhasil!');
        } elseif (Auth()->User()->roles_id == 3 && Auth()->User()->id == $data['id']) {
            $user = User::find(Auth()->User()->id);

            if ($user->email == $data['email']) {
            } else {
                $request->validate([
                    'email' => 'required|email|unique:users',
                ]);
            }

            if ($data['password'] != null) {
                $request->validate([
                    'password' => 'required|min:8',
                    'confirm-password' => 'required|min:8|same:password',
                ]);
            }

            if ($data['password'] != null) {
                // Mendapatkan data email dan deskripsi.
                $email = $data['email'];

                // Memperbarui data pengguna (email dan deskripsi).
                $temp = User::where('id', $data['id'])->update([
                    'email' => $email,
                    'password' => Hash::make($data['password']),
                ]);

                // Memperbarui data kontak (nomor telepon).
                Contact::where('user_id', $data['id'])->update([
                    'no_telp' => $request->noTelp,
                ]);

                // Melakukan commit transaksi database.
                DB::commit();
            } else {
                // Mendapatkan data email dan deskripsi.
                $email = $data['email'];

                // Memperbarui data pengguna (email dan deskripsi).
                $temp = User::where('id', $data['id'])->update([
                    'email' => $email,
                ]);

                // Memperbarui data kontak (nomor telepon).
                Contact::where('user_id', $data['id'])->update([
                    'no_telp' => $request->noTelp,
                ]);

                // Melakukan commit transaksi database.
                DB::commit();
            }

            // Mengarahkan kembali dengan pesan sukses.
            return redirect()->back()->with('success', 'Update berhasil!');
        } else {
            // Menampilkan halaman 404 jika peran pengguna tidak sesuai.
            abort(404);
        }
    }

    /**
     * Menampilkan daftar siswa berdasarkan nama kelas.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function viewSiswaKelas(Request $request)
    {
        // Memeriksa apakah parameter 'kelasName' tidak null.
        if ($request->kelasName != null) {
            // Mencari ID kelas berdasarkan nama kelas yang diberikan.
            $num = Kelas::where('name', $request->kelasName)->first();

            // Mengambil daftar siswa yang terkait dengan ID kelas.
            $siswa = DataSiswa::where('kelas_id', $num['id'])->get();
            $data = [];

            // Iterasi melalui daftar siswa dan mengambil data yang diperlukan.
            foreach ($siswa as $key) {
                // Mengambil data pengguna terkait.
                $temp = User::where('id', $key->user_id)->first();

                // Memeriksa apakah data pengguna ditemukan.
                if ($temp) {
                    $gambar = $temp->gambar;
                } else {
                    $gambar = null;
                }

                // Menyusun data siswa untuk ditampilkan.
                $data[] = [
                    'user_id' => $key['user_id'],
                    'name' => $key['name'],
                    'gambar' => $gambar,
                ];
            }

            // Menampilkan tampilan daftar siswa sebagai respons.
            return view('menu.profile.partials.siswaList', ['siswa' => $data])->render();
        } else {
            // Menampilkan halaman 404 jika parameter 'kelasName' null.
            abort(404);
        }
    }
}
