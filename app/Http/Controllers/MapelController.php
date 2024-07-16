<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Mapel;
use App\Models\KelasMapel;
use App\Exports\MapelExport;
use App\Imports\MapelImport;
use App\Models\EditorAccess;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class MapelController extends Controller
{
    /**
     * Menampilkan halaman daftar mapel.
     *
     * @return \Illuminate\View\View
     */
    public function viewMapel()
    {
        // Ambil peran pengguna
        $roles = DashboardController::getRolesName();

        // Tampilkan halaman daftar mapel dengan data mapel yang dipaginasi
        return view('menu.admin.controlMapel.viewMapel', ['title' => 'Data Mapel', 'roles' => $roles, 'mapel' => Mapel::paginate(15)]);
    }

    /**
     * Mencari mapel berdasarkan kriteria tertentu.
     *
     * @return \Illuminate\View\View
     */
    public function searchMapel(Request $request)
    {
        $search = $request->input('search');
        $mapel = Mapel::where('name', 'like', '%' . $search . '%')->paginate(15);

        return view('menu.admin.controlMapel.partials.mapelTable', compact('mapel'))->render();
    }

    /**
     * Menambahkan akses editor untuk mapel tertentu.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function tambahEditorAccess(Request $request)
    {
        $kelasMapel = KelasMapel::where('kelas_id', $request->kelasId)->where('mapel_id', $request->mapelId)->first();

        $temp = [
            'user_id' => $request->userId,
            'kelas_mapel_id' => $kelasMapel['id'],
        ];

        EditorAccess::create($temp);

        return response()->json(['response' => 'Added']);
    }

    /**
     * Menghapus akses editor untuk mapel tertentu.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteEditorAccess(Request $request)
    {
        $kelasMapel = KelasMapel::where('kelas_id', $request->kelasId)->where('mapel_id', $request->mapelId)->first();

        if ($kelasMapel) {
            $kelasMapelId = $kelasMapel->id;

            EditorAccess::where('kelas_mapel_id', $kelasMapelId)->delete();

            return response()->json(['response' => 'Deleted']);
        } else {
            return response()->json(['response' => 'Data tidak ditemukan'], 404);
        }
    }

    /**
     * Menampilkan halaman tambah mapel.
     *
     * @return \Illuminate\View\View
     */
    public function viewTambahMapel()
    {
        $roles = DashboardController::getRolesName();

        return view('menu.admin.controlMapel.viewTambahMapel', ['title' => 'Tambah Mapel', 'roles' => $roles]);
    }

    /**
     * Menampilkan halaman update mapel.
     *
     * @return \Illuminate\View\View
     */
    public function viewUpdateMapel(Mapel $mapel)
    {
        $roles = DashboardController::getRolesName();

        return view('menu.admin.controlMapel.updateMapel', ['title' => 'Update Mapel', 'roles' => $roles, 'mapel' => $mapel]);
    }

    /**
     * Memeriksa apakah mapel sudah terhubung ke kelas tertentu.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function cekKelasMapel(Request $request)
    {
        $response = KelasMapel::where('kelas_id', $request->kelasId)->where('mapel_id', $request->mapelId)->first();

        if (count($response->EditorAccess) > 0) {
            return response()->json(['response' => '1']); // Pesan jika memiliki akses Editor
        } else {
            return response()->json(['response' => '0']); // Pesan jika tidak memiliki akses Editor
        }
    }

    /**
     * Validasi data mapel sebelum penyimpanan.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function validateNamaMapel(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:mapels',
        ]);

        $desk = $request->deskripsi;

        if ($request->deskripsi == null) {
            $desk = '-';
        }

        $data = [
            'name' => $request->name,
            'deskripsi' => $desk,
        ];

        Mapel::create($data);

        // Mencari id Mapel terakhir
        $mapelId = Mapel::latest()->first();
        $mapelId = $mapelId['id'];

        $data = [
            'prompt' => 'diTambahkan!',
            'action' => 'Tambah',
            'id' => $mapelId,
        ];
        session(['data' => $data]);

        return redirect(route('dataMapelSuccess'));
    }

    /**
     * Memperbarui data mapel.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateMapel(Request $request)
    {
        $request->validate([
            'nama' => 'required',
        ]);

        $desk = $request->deskripsi;

        if ($request->deskripsi == null) {
            $desk = '-';
        }

        $data = [
            'name' => $request->nama,
            'deskripsi' => $desk,
        ];

        Mapel::where('id', $request->id)->update($data);

        return redirect()->back()->with('success', 'Update berhasil!');
    }

    /**
     * Menambah atau mengubah akses editor untuk mapel tertentu.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function addChangeEditorAccess(Request $request)
    {

        $kelasMapelId = KelasMapel::where('kelas_id', $request->kelasId)->where('mapel_id', $request->mapelId)->first();
        $kelasMapelId = $kelasMapelId['id'];

        $editorAccess = EditorAccess::where('kelas_mapel_id', $kelasMapelId)->get();
        // return response()->json(['success' => $editorAccess]);

        if ($request->pengajarId == 'delete') {
            EditorAccess::where('kelas_mapel_id', $kelasMapelId)->delete();

            return response()->json(['success' => 'deleted']);
        }

        try {
            if (count($editorAccess) > 0) {
                $data = ['user_id' => $request->pengajarId];
                EditorAccess::where('kelas_mapel_id', $kelasMapelId)->update($data);

                return response()->json(['success' => 1]);
            } else {
                EditorAccess::create(['user_id' => $request->pengajarId, 'kelas_mapel_id' => $kelasMapelId]);

                return response()->json(['success' => 0]);
            }
        } catch (Exception $e) {
            return response()->json(['success' => $e]);
        }
    }

    /**
     * Menangani penambahan gambar untuk mapel.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function mapelTambahGambar(Request $request)
    {
        $request->validate([
            'file' => 'file|image|max:4000',
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $newImageName = 'UIMG' . date('YmdHis') . uniqid() . '.jpg'; // Nama gambar baru

            // Simpan file ke direktori penyimpanan (storage)
            $filePath = $file->storeAs('mapel', $newImageName, 'public');

            if (!$filePath) {
                return response()->json(['status' => 0, 'msg' => 'Upload gagal']);
            } else {
                // Hapus gambar lama jika ada
                $userInfo = Mapel::where('id', $request->id)->first();
                $userPhoto = $userInfo->gambar;

                if ($userPhoto != null) {
                    // Hapus gambar lama dari penyimpanan
                    Storage::disk('public')->delete('mapel/' . $userPhoto);
                }

                // Perbarui gambar
                Mapel::where('id', $request->id)->update(['gambar' => $newImageName]);

                return response()->json(['status' => 1, 'msg' => 'Upload berhasil', 'name' => $newImageName]);
            }
        }

        return response()->json(['status' => 0, 'msg' => 'Tidak ada file yang diunggah']);
    }

    /**
     * Mencari kelas-mapel yang terkait dengan kelas tertentu.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchKelasMapel(Request $request)
    {

        $kelasMapel = KelasMapel::where('kelas_id', $request->kelasId)->get();
        $enrolledMapel = []; // Inisialisasi array untuk menyimpan data mapel yang diambil

        foreach ($kelasMapel as $key) {
            $mapel = Mapel::where('id', $key->mapel_id)->first();
            $pengajarExist = count($key->EditorAccess);
            $mapel['exist'] = $pengajarExist;

            if ($mapel) {
                $enrolledMapel[] = $mapel; // Tambahkan data mapel ke dalam array
            }
        }
        // dd($enrolledMapel);

        return response()->json($enrolledMapel);
    }

    /**
     * Menampilkan halaman sukses data mapel ditambahkan.
     *
     * @return \Illuminate\View\View
     */
    public function dataMapelSuccess()
    {
        if (session('data') != null) {
            $data = session('data');
            session()->forget('data');
            $roles = DashboardController::getRolesName();

            return view('menu.admin.controlMapel.dataSukses', ['title' => 'Sukses', 'roles' => $roles, 'data' => $data]);
        } else {
            abort(404);
        }
    }

    /**
     * Menghapus data mapel beserta kelas-mapel dan akses editor yang terkait.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyMapel(Request $request)
    {
        Mapel::destroy($request->idHapus);
        KelasMapel::where('mapel_id', $request->idHapus)->delete();
        EditorAccess::where('kelas_mapel_id', $request->idHapus)->delete();

        return redirect()->back()->with('delete-success', 'Berhasil menghapus Mapel!');
    }

    // Mapel

    /**
     * Mengunduh contoh data mapel dalam format Excel.
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function contohMapel()
    {
        // File PDF disimpan di dalam project/public/download/info.pdf
        $file = public_path() . '/examples/contoh-data-mapel.xls';

        return response()->download($file, 'contoh-mapel.xls');
    }

    /**
     * Mengunduh data kelas dalam format Excel.
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function export()
    {
        return Excel::download(new MapelExport, 'export-mapel.xls');
    }

    /**
     * Mengimpor data kelas dari file Excel.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);
        session()->forget('imported_ids', []);

        try {
            Excel::import(new MapelImport, $request->file('file'));

            $ids = session()->get('imported_ids');
            Mapel::whereNotIn('id', $ids)->delete();

            $editorId = KelasMapel::whereNotIn('mapel_id', $ids)->get('id');

            KelasMapel::whereNotIn('mapel_id', $ids)->delete();

            $editor = EditorAccess::whereIn('kelas_mapel_id', $editorId)->get();

            if (count($editor) > 0) {
                EditorAccess::whereIn('kelas_mapel_id', $editorId)->delete();
            }

            return redirect()->route('viewMapel')->with('import-success', 'Data Kelas berhasil diimpor.');
        } catch (\Exception $e) {
            return redirect()->route('viewMapel')->with('import-error', 'Error: ' . $e);
        }
    }
}
