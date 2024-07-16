<?php

namespace App\Http\Controllers;

use App\Models\EditorAccess;
use App\Models\Kelas;
use App\Models\KelasMapel;
use App\Models\Mapel;
use App\Models\Materi;
use App\Models\MateriFile;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Class : MateriController
 *
 * Class ini berisi berbagai fungsi yang berkaitan dengan manipulasi data-data materi, terutama terkait dengan model.
 *
 * @copyright  2023 Sunday Interactive
 * @license    http://www.zend.com/license/3_0.txt   PHP License 3.0
 *
 * @version    Release: 1.0
 *
 * @link       http://dev.zend.com/package/PackageName
 * @since      Class tersedia sejak Rilis 1.0
 */
class MateriController extends Controller
{
    /**
     * Menampilkan halaman Tambah Materi.
     *
     * @param  string  $token
     * @return \Illuminate\View\View
     */
    public function viewCreateMateri($token, Request $request)
    {
        // id = Kelas Id
        $id = decrypt($token);
        $kelasMapel = KelasMapel::where('mapel_id', $request->mapelId)->where('kelas_id', $id)->first();

        $preparedIdMateri = count(Materi::get());
        $preparedIdMateri = $preparedIdMateri + 1;
        // Logika untuk memeriksa apakah pengguna yang sudah login memiliki akses editor
        foreach (Auth()->User()->EditorAccess as $key) {
            if ($key->kelas_mapel_id == $kelasMapel['id']) {
                $roles = DashboardController::getRolesName();
                $mapel = Mapel::where('id', $request->mapelId)->first();

                $assignedKelas = DashboardController::getAssignedClass();

                return view('menu.pengajar.materi.viewTambahMateri', ['assignedKelas' => $assignedKelas, 'title' => 'Tambah Materi', 'roles' => $roles, 'kelasId' => $id, 'mapel' => $mapel, 'preparedIdMateri' => $preparedIdMateri]);
            }
        }
        abort(404);
    }

    /**
     * Menampilkan halaman Update Materi.
     *
     * @param  string  $token
     * @return \Illuminate\View\View
     */
    public function viewUpdateMateri($token, Request $request)
    {
        // token = Materi Id
        $id = decrypt($token);
        $materi = Materi::where('id', $id)->first();  // Dapatkan Materi

        // Dapatkan kelas mapel untuk dibandingkan dengan materi
        $kelasMapel = KelasMapel::where('id', $materi->kelas_mapel_id)->first();

        // Logika untuk memeriksa apakah pengguna yang sudah login memiliki akses editor
        foreach (Auth()->User()->EditorAccess as $key) {
            if ($key->kelas_mapel_id == $kelasMapel['id']) {
                $roles = DashboardController::getRolesName();
                $mapel = Mapel::where('id', $request->mapelId)->first();

                $kelas = Kelas::where('id', $kelasMapel['kelas_id'])->first('id');

                $assignedKelas = DashboardController::getAssignedClass();

                return view('menu.pengajar.materi.viewUpdateMateri', ['assignedKelas' => $assignedKelas, 'title' => 'Update Materi', 'materi' => $materi, 'roles' => $roles, 'kelasId' => $kelas['id'], 'mapel' => $mapel, 'kelasMapel' => $kelasMapel]);
            }
        }
        abort(404);
    }

    /**
     * Menampilkan halaman Materi.
     *
     * @return \Illuminate\View\View
     */
    public function viewMateri(Request $request)
    {
        // materi id
        $id = decrypt($request->token);
        //kelasMapel id
        $idx = decrypt($request->kelasMapelId);

        $materi = Materi::where('id', $id)->first();

        $roles = DashboardController::getRolesName();
        $kelasMapel = KelasMapel::where('id', $materi->kelas_mapel_id)->first();

        // Dapatkan Pengajar
        $editorAccess = EditorAccess::where('kelas_mapel_id', $kelasMapel['id'])->first();
        $editorData = User::where('id', $editorAccess['user_id'])->where('roles_id', 2)->first();

        $mapel = Mapel::where('id', $request->mapelId)->first();
        $kelas = Kelas::where('id', $kelasMapel['kelas_id'])->first('id');

        $materiAll = Materi::where('kelas_mapel_id', $idx)->get();

        $assignedKelas = DashboardController::getAssignedClass();

        return view('menu.pengajar.materi.viewMateri', ['assignedKelas' => $assignedKelas, 'editor' => $editorData, 'materi' => $materi, 'kelas' => $kelas, 'title' => $materi->name, 'roles' => $roles, 'materiAll' => $materiAll, 'mapel' => $mapel, 'kelasMapel' => $kelasMapel]);
    }

    /**
     * Membuat Materi baru.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createMateri(Request $request)
    {
        // Lakukan validasi untuk inputan form
        $request->validate([
            'name' => 'required',
            'content' => 'required',
        ]);

        try {
            // Dekripsi token dan dapatkan KelasMapel
            $token = decrypt($request->kelasId);
            $kelasMapel = KelasMapel::where('mapel_id', $request->mapelId)->where('kelas_id', $token)->first();

            $isHidden = 1;

            if ($request->opened) {
                $isHidden = 0;
            }
            $temp = [
                'kelas_mapel_id' => $kelasMapel['id'],
                'name' => $request->name,
                'content' => $request->content,
                'isHidden' => $isHidden,
            ];

            // Simpan data Materi ke database
            Materi::create($temp);

            // Commit transaksi database
            DB::commit();

            // Berikan respons sukses jika semuanya berjalan lancar
            return response()->json(['message' => 'Materi berhasil dibuat'], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error'], 200);
        }
    }

    /**
     * Mengupdate Materi.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateMateri(Request $request)
    {
        // Lakukan validasi untuk inputan form
        $request->validate([
            'name' => 'required',
            'content' => 'required',
        ]);
        // return response()->json(['message' => $request->input()], 200);
        // Dekripsi token hasil dari hidden form lalu dapatkan data KelasMapel
        $materiId = decrypt($request->materiId);

        try {
            $isHidden = 1;

            if ($request->opened) {
                $isHidden = 0;
            }
            $data = [
                'name' => $request->name,
                'content' => $request->content,
                'isHidden' => $isHidden,
            ];
            // Simpan data Materi ke database
            Materi::where('id', $materiId)->update($data);
            // Commit transaksi database
            DB::commit();

            // Berikan respons sukses jika semuanya berjalan lancar
            return response()->json(['message' => 'Materi berhasil dibuat'], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error'], 200);
        }
    }

    /**
     * Menghapus Materi.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyMateri(Request $request)
    {

        // Dapatkan Id Materi dari Inputan Form request
        $materiId = $request->hapusId;

        // Logika untuk memeriksa apakah pengguna yang sudah login memiliki akses editor
        foreach (Auth()->User()->EditorAccess as $key) {
            if ($key->kelas_mapel_id == $request->kelasMapelId) {
                $dest = '../public_html/file/materi'; // Destinasi tempat pengguna akan disimpan
                $files = MateriFile::where('materi_id', $materiId)->get();
                foreach ($files as $key) {
                    if (file_exists(public_path($dest . '/' . $key->file))) {
                        unlink(public_path($dest . '/' . $key->file));
                    }
                }
                Materi::where('id', $materiId)->delete();
                MateriFile::where('materi_id', $materiId)->delete();

                return redirect()->back()->with('success', 'Materi Berhasil dihapus');
            }
        }
        abort(404);
    }

    /**
     * Upload file Materi.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function uploadFileMateri(Request $request)
    {
        // Dapatkan Id Materi dari Inputan Form request
        // Validasi file yang diunggah
        $request->validate([
            'file' => 'required|file|max:2048', // Batasan ukuran maksimum adalah 2 MB (ganti sesuai kebutuhan Anda)
        ]);

        // return response()->json(['message' => $request->input()]);
        if ($request->action == 'tambah') {
            $latestMateri = Materi::latest()->first();

            // Proses unggahan file di sini
            $file = $request->file('file');
            $fileName = 'F' . mt_rand(1, 999) . '_' . $file->getClientOriginalName();
            $file->move(public_path('storage/file/materi'), $fileName); // Simpan file di direktori 'storage/app/uploads'

            MateriFile::create([
                'materi_id' => $latestMateri['id'],
                'file' => $fileName,
            ]);

            return response()->json(['message' => 'File berhasil diunggah.']); // Respon sukses
        } elseif ($request->action == 'edit') {
            // Proses unggahan file di sini
            $file = $request->file('file');
            $fileName = 'F' . mt_rand(1, 999) . '_' . $file->getClientOriginalName();
            $file->move(public_path('storage/file/materi'), $fileName); // Simpan file di direktori 'storage/app/uploads'

            MateriFile::create([
                'materi_id' => $request->idMateri,
                'file' => $fileName,
            ]);

            return response()->json(['message' => 'File berhasil diunggah.']); // Respon sukses
        }

        return response()->json(['message' => 'File Error.']);
    }

    /**
     * Delete file Materi.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyFileMateri(Request $request)
    {
        $idMateri = $request->idMateri;
        $fileName = $request->fileName;

        $dest = 'file/materi'; // Destinasi tempat pengguna akan disimpan

        if (file_exists(public_path($dest . '/' . $fileName))) {
            unlink(public_path($dest . '/' . $fileName));
        }

        MateriFile::where('materi_id', $idMateri)->where('file', $fileName)->delete();

        return redirect()->back()->with('success', 'File Deleted');
    }

    /**
     * Delete file Materi.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirectBack(Request $request)
    {
        $mapelId = request('amp;mapelId');
        $message = request('amp;message');

        return redirect(route('viewKelasMapel', ['mapel' => $mapelId, 'token' => encrypt($request->kelasId), 'mapel_id' => $mapelId]))->with('success', 'Data Berhasil di ' . $message);
    }
}
