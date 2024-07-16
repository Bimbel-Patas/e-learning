<?php

namespace App\Http\Controllers;

use App\Exports\KelasExport;
use App\Imports\KelasImport;
use App\Models\DataSiswa;
use App\Models\EditorAccess;
use App\Models\Kelas;
use App\Models\KelasMapel;
use App\Models\Mapel;
use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class KelasController extends Controller
{
    /**
     * Menampilkan halaman data kelas.
     *
     * @return \Illuminate\View\View
     */
    public function viewKelas()
    {
        $roles = DashboardController::getRolesName();

        return view('menu.admin.controlKelas.viewKelas', ['title' => 'Data Kelas', 'roles' => $roles, 'kelas' => Kelas::paginate(15), 'mapelCount' => count(Mapel::get())]);
    }

    /**
     * Mencari kelas berdasarkan nama.
     *
     * @return \Illuminate\View\View
     */
    public function searchKelas(Request $request)
    {
        $search = $request->input('search');
        $kelas = Kelas::where('name', 'like', '%' . $search . '%')->paginate(15);

        return view('menu.admin.controlKelas.partials.kelasTable', compact('kelas'))->render();
    }

    /**
     * Menampilkan halaman tambah kelas.
     *
     * @return \Illuminate\View\View
     */
    public function viewTambahKelas()
    {
        $roles = DashboardController::getRolesName();

        return view('menu.admin.controlKelas.tambahKelas', ['title' => 'Tambah Kelas', 'roles' => $roles, 'dataMapel' => Mapel::get()]);
    }

    /**
     * Validasi dan menyimpan nama kelas baru.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function validateNamaKelas(Request $request)
    {

        $request->validate([
            'name' => 'required|unique:kelas',
        ]);

        $data = [
            'name' => $request->name,
        ];

        Kelas::create($data);

        $latestKelas = Kelas::latest('id')->first();

        if ($request->mapels) {
            foreach ($request->mapels as $key) {
                $data = ['kelas_id' => $latestKelas['id'], 'mapel_id' => $key];
                KelasMapel::create($data);
            }
        }

        $data = [
            'prompt' => 'ditambahkan!',
            'action' => 'Tambah',
        ];
        session(['data' => $data]);

        return redirect(route('dataKelasSuccess'));
    }

    /**
     * Menampilkan halaman sukses setelah menambahkan kelas.
     *
     * @return \Illuminate\View\View
     */
    public function dataKelasSuccess()
    {
        if (session('data') != null) {
            $data = session('data');
            session()->forget('data');
            $roles = DashboardController::getRolesName();

            return view('menu.admin.controlKelas.dataSukses', ['title' => 'Sukses', 'roles' => $roles, 'data' => $data]);
        } else {
            abort(404);
        }
    }

    /**
     * Menghapus kelas dan data terkait.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyKelas(Request $request)
    {
        $kelasMapelId = KelasMapel::where('kelas_id', $request->idHapus)->get();

        foreach ($kelasMapelId as $key) {
            EditorAccess::where('kelas_mapel_id', $key['id'])->delete();
        }

        KelasMapel::where('kelas_id', $request->idHapus)->delete();
        Kelas::destroy($request->idHapus);

        return redirect()->back()->with('delete-success', 'Berhasil menghapus kelas!');
    }

    /**
     * Menampilkan halaman update kelas.
     *
     * @return \Illuminate\View\View
     */
    public function viewUpdateKelas(Kelas $kelas)
    {
        $kelasMapel = KelasMapel::where('kelas_id', $kelas->id)->get();
        $enrolledMapel = [];

        foreach ($kelasMapel as $key) {
            $mapel = Mapel::where('id', $key->mapel_id)->first();

            if ($mapel) {
                $enrolledMapel[] = $mapel;
            }
        }

        $mapel = Mapel::get();
        $roles = DashboardController::getRolesName();

        return view('menu.admin.controlKelas.updateKelas', ['title' => 'Update Kelas', 'roles' => $roles, 'kelas' => $kelas, 'dataMapel' => $mapel, 'kelasMapel' => $enrolledMapel]);
    }

    /**
     * Menampilkan halaman detail kelas.
     *
     * @return \Illuminate\View\View
     */
    public function viewDetailKelas(Request $request)
    {
        $kelas = Kelas::where('id', $request->kelasId)->first();
        $kelasMapel = KelasMapel::where('kelas_id', $request->kelasId)->get();
        $enrolledMapel = [];

        foreach ($kelasMapel as $key) {
            $mapel = Mapel::where('id', $key->mapel_id)->first(['id', 'name']);
            $pengajarName = null;

            if (count($key->EditorAccess) > 0) {
                $pengajar = User::where('id', $key->EditorAccess[0]->user_id)->first();
                $pengajarName = $pengajar ? $pengajar->name : null;
                $pengajarId = $pengajar ? $pengajar->id : null;
            } else {
                $pengajarName = null;
                $pengajarId = null;
            }

            if ($mapel) {
                $enrolledMapel[] = [
                    'id' => $mapel->id,
                    'name' => $mapel->name,
                    'pengajarName' => $pengajarName,
                    'pengajarId' => $pengajarId,
                ];
            }
        }

        $pengajar = User::where('roles_id', 2)->get();

        return view('menu.admin.controlKelas.partials.mapelList', ['enrolledMapel' => $enrolledMapel, 'pengajar' => $pengajar, 'kelas' => $kelas])->render();
    }

    /**
     * Mengupdate kelas dan mapel terkait.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateKelas(Request $request)
    {

        $id = $request->id;
        $mapelsToKeep = $request->mapels;

        $idKelasNew = KelasMapel::where('kelas_id', $id)->get();
        $temp = [];

        foreach ($idKelasNew as $key) {
            array_push($temp, $key->mapel_id);
        }

        $diff = array_diff($mapelsToKeep, $temp);

        foreach ($diff as $key) {
            $data = [
                'kelas_id' => $id,
                'mapel_id' => $key,
            ];
            KelasMapel::create($data);
        }

        $idKelas = KelasMapel::where('kelas_id', $id)->whereNotIn('mapel_id', $mapelsToKeep)->get();

        KelasMapel::where('kelas_id', $id)
            ->whereNotIn('mapel_id', $mapelsToKeep)
            ->delete();

        foreach ($idKelas as $key) {
            EditorAccess::where('kelas_mapel_id', $key['id'])->delete();
        }

        if ($request->nama) {
            Kelas::where('id', $id)->update(['name' => $request->nama]);
        }

        return redirect()->back()->with('success', 'Update berhasil!');
    }

    // Kelas

    /**
     * Mengunduh data kelas dalam format Excel.
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function export()
    {
        return Excel::download(new KelasExport, 'export-kelas.xls');
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
            Excel::import(new KelasImport, $request->file('file'));

            $ids = session()->get('imported_ids');
            Kelas::whereNotIn('id', $ids)->delete();

            DataSiswa::whereNotIn('kelas_id', $ids)->update(['kelas_id' => null]);

            $editorId = KelasMapel::whereNotIn('kelas_id', $ids)->get('id');

            KelasMapel::whereNotIn('kelas_id', $ids)->delete();

            $editor = EditorAccess::whereIn('kelas_mapel_id', $editorId)->get();

            if (count($editor) > 0) {
                EditorAccess::whereIn('kelas_mapel_id', $editorId)->delete();
            }

            return redirect()->route('viewKelas')->with('import-success', 'Data Kelas berhasil diimpor.');
        } catch (\Exception $e) {
            return redirect()->route('viewKelas')->with('import-error', 'Error: ' . $e);
        }
    }

    /**
     * Mengunduh contoh data kelas dalam format Excel.
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function contohKelas()
    {
        $file = public_path('/examples/contoh-data-kelas.xls');

        return response()->download($file, 'contoh-kelas.xls');
    }
}
