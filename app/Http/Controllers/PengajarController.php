<?php

namespace App\Http\Controllers;

use App\Exports\PengajarExport;
use App\Imports\PengajarImport;
use App\Models\Contact;
use App\Models\EditorAccess;
use App\Models\Kelas;
use App\Models\KelasMapel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Maatwebsite\Excel\Facades\Excel;

class PengajarController extends Controller
{
    public $array = [];

    /**
     * Menampilkan halaman data pengajar.
     *
     * @return \Illuminate\View\View
     */
    public function viewPengajar()
    {
        $roles = DashboardController::getRolesName();

        return view('menu.admin.controlPengajar.viewPengajar', ['title' => 'Data Pengajar', 'roles' => $roles, 'pengajar' => User::where('roles_id', 2)->paginate(15)]);
    }

    /**
     * Mencari pengajar berdasarkan nama.
     *
     * @return string
     */
    public function searchPengajar(Request $request)
    {
        $search = $request->input('search');
        $pengajar = User::where('name', 'like', '%' . $search . '%')->where('roles_id', 2)->paginate(15);

        return view('menu.admin.controlPengajar.partials.pengajarTable', compact('pengajar'))->render();
    }

    /**
     * Menampilkan halaman tambah pengajar.
     *
     * @return \Illuminate\View\View
     */
    public function viewNewPengajar()
    {
        $roles = DashboardController::getRolesName();

        return view('menu.admin.controlPengajar.tambahPengajar', ['title' => 'Tambah Pengajar', 'roles' => $roles]);
    }

    /**
     * Validasi data pengajar sebelum tambah kelas.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function validateDataPengajar(Request $request)
    {
        // Validasi inputan form
        $request->validate([
            'nama' => 'required|min:5',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'confirm-password' => 'required|min:8|same:password',
        ]);

        $data = [
            'nama' => $request->nama,
            'noTelp' => $request->noTelp,
            'email' => $request->email,
            'password' => $request->password,
        ];

        session(['data' => $data]);

        return redirect(route('tambahKelasPengajar'));
    }

    /**
     * Menampilkan halaman tambah kelas untuk pengajar.
     *
     * @return \Illuminate\View\View
     */
    public function tambahKelasPengajar()
    {
        if (session('data') != null) {
            $data = session('data');
            session()->forget('data');

            $roles = DashboardController::getRolesName();

            return view('menu.admin.controlPengajar.tambahKelasPengajar', ['data' => $data, 'title' => 'Tambah Pengajar', 'roles' => $roles, 'dataKelas' => Kelas::get()]);
        } else {
            abort(404);
        }
    }

    /**
     * Validasi data pengajar dan kelas sebelum ditambahkan.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function validateDataPengajarKelas(Request $request)
    {
        // Mengambil data dari request
        $data = $request->all();

        // Data pengajar (nama, jenis kelamin, email, password)
        $nama = $data['nama'];
        $email = $data['email'];
        $password = bcrypt($data['password']); // Enkripsi password

        // Membuat pengajar baru
        $pengajar = User::create([
            'name' => $nama,
            'roles_id' => 2,
            'email' => $email,
            'password' => $password,
        ]);

        $userId = User::latest()->first();
        $userId = $userId['id'];

        if ($request->noTelp) {
            Contact::create([
                'user_id' => $userId,
                'no_telp' => $request->noTelp,
            ]);
        } else {
            Contact::create([
                'user_id' => $userId,
                'no_telp' => null,
            ]);
        }

        $dataKelasMapel = json_decode($data['data']['kelas'][0], true);

        if ($dataKelasMapel != null) {

            // Data kelas dan mapel yang akan ditambahkan
            $kelasMapelId = [];
            // Loop melalui data kelas dan mapel
            foreach ($dataKelasMapel as $kelasMapel) {
                // Membuat relasi antara pengajar, kelas, dan mapel
                // $pengajar->kelasMapel()->attach($kelasMapel['kelas'], ['mapel_id' => $kelasMapel['mapel']]);
                $temp = KelasMapel::where('kelas_id', $kelasMapel['kelas'])->where('mapel_id', $kelasMapel['mapel'])->first();
                array_push($kelasMapelId, $temp['id']);
                EditorAccess::create(
                    [
                        'user_id' => $userId,
                        'kelas_mapel_id' => $temp['id'],
                    ]
                );
            }
        } else {
        }

        $data = [
            'prompt' => 'diTambahkan!',
            'action' => 'Tambah',
            'id' => $userId,
        ];

        session(['data' => $data]);

        return redirect(route('dataPengajarSuccess'));
    }

    /**
     * Menampilkan halaman update pengajar.
     *
     * @param  string  $token
     * @return \Illuminate\View\View
     */
    public function viewUpdatePengajar($token)
    {
        try {
            $id = Crypt::decrypt($token);
            $roles = DashboardController::getRolesName();
            $profile = User::findOrFail($id);
            $contact = Contact::where('user_id', $id)->first();
            $editorAccess = editorAccess::where('user_id', $id)->get();

            $mapelEnrolled = [];

            foreach ($editorAccess as $key) {
                $kelasMapel = KelasMapel::where('id', $key['kelas_mapel_id'])->first();
                array_push($mapelEnrolled, $kelasMapel);
            }

            $kelas = Kelas::get();

            return view('menu.admin.controlPengajar.updatePengajar', ['user' => $profile, 'contact' => $contact, 'kelas' => $kelas, 'mapelEnrolled' => $mapelEnrolled, 'roles' => $roles, 'title' => 'Update Pengajar']);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            abort(404);
        }
    }

    /**
     * Mengupdate data pengajar.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePengajar(Request $request)
    {
        // Mengambil data dari request
        $data = $request->all();

        // Data pengajar (nama, jenis kelamin, email, password)
        $nama = $data['nama'];
        $email = $data['email'];

        if ($data['password'] != null) {
            $password = bcrypt($data['password']); // Enkripsi password

            $pengajar = [
                'name' => $nama,
                'email' => $email,
                'password' => $password,
            ];

            User::where('id', $data['id'])->update($pengajar);
        } else {
            $pengajar = User::where('id', $data['id'])->update([
                'name' => $nama,
                'email' => $email,
            ]);
        }

        Contact::where('user_id', $data['id'])->update([
            'no_telp' => $request->noTelp,
        ]);

        return redirect()->back()->with('success', 'Update berhasil!');
    }

    /**
     * Menampilkan halaman sukses setelah data pengajar ditambahkan.
     *
     * @return \Illuminate\View\View
     */
    public function dataPengajarSuccess()
    {
        if (session('data') != null) {
            $data = session('data');
            session()->forget('data');
            $roles = DashboardController::getRolesName();

            return view('menu.admin.controlPengajar.dataSukses', ['title' => 'Sukses', 'roles' => $roles, 'data' => $data]);
        } else {
            abort(404);
        }
    }

    /**
     * Debugging route.
     */
    public function catch(Request $request)
    {
        dd($request);
    }

    /**
     * Menghapus data pengajar.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyPengajar(Request $request)
    {
        // dd($request);
        User::where('id', $request->idHapus)->delete();
        EditorAccess::where('user_id', $request->idHapus)->delete();

        return redirect(route('viewPengajar'))->with('delete-success', 'Berhasil menghapus data!');
    }

    /**
     * Debugging route.
     */
    public function debugRoute(Request $request)
    {
        dd($request);
        $roles = DashboardController::getRolesName();

        return view('menu.admin.controlPengajar.tambahKelasPengajar', ['title' => 'Tambah Pengajar', 'roles' => $roles, 'dataKelas' => Kelas::get()]);
    }

    // Excel
    /**
     * Export data pengajar ke file Excel.
     *
     * @return \Illuminate\Http\Response
     */
    public function export()
    {
        return Excel::download(new PengajarExport, 'export-pengajar.xls');
    }

    /**
     * Import data pengajar dari file Excel.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls', // Sesuaikan dengan jenis file Excel yang diizinkan
        ]);
        session()->forget('imported_ids', []);

        // Proses impor data dari Excel
        try {
            Excel::import(new PengajarImport, $request->file('file')); // Gantilah dengan nama sesuai nama kelas impor Anda
            $ids = session()->get('imported_ids');
            User::where('roles_id', 2)->whereNotIn('id', $ids)->delete();
            EditorAccess::whereNotIn('user_id', $ids)->delete();
            // Hapus Editor Access dan data pengajar yang tidak diimpor

            return redirect()->route('viewPengajar')->with('import-success', 'Data pengajar berhasil diimpor.');
        } catch (\Exception $e) {
            return redirect()->route('viewPengajar')->with('import-error', 'Error: Terjadi Kesalahan, Mohon cek Data Anda!');
        }
    }

    /**
     * Mengunduh file contoh Excel.
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function fileContoh()
    {
        // File PDF disimpan di bawah project/public/download/info.pdf
        $file = public_path() . '/examples/contoh-data-pengajar.xls';

        return response()->download($file, 'contoh-data-pengajar.xls');
    }
}
