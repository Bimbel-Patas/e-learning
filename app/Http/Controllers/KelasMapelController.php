<?php

namespace App\Http\Controllers;

use App\Exports\NilaiTugasExport;
use App\Exports\NilaiUjianExport;
use App\Models\Kelas;
use App\Models\KelasMapel;
use App\Models\Mapel;
use App\Models\Materi;
use App\Models\Tugas;
use App\Models\Ujian;
use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class KelasMapelController extends Controller
{
    /**
     * Menampilkan halaman kelas dan mata pelajaran tertentu.
     *
     * @param  string  $token
     * @return \Illuminate\View\View
     */
    public function viewKelasMapel($x, $token, Request $request)
    {
        if ($token) {
            // Request = mapel id
            $id = decrypt($token);
            $mapel = Mapel::where('id', $request->mapel_id)->first();
            $kelas = Kelas::where('id', $id)->first();
            $kelasMapel = KelasMapel::where('mapel_id', $request->mapel_id)->where('kelas_id', $id)->first();
            $materi = Materi::where('kelas_mapel_id', $kelasMapel['id'])->get();
            $tugas = Tugas::where('kelas_mapel_id', $kelasMapel['id'])->get();
            $ujian = Ujian::where('kelas_mapel_id', $kelasMapel['id'])->get();
            $roles = DashboardController::getRolesName();
            $assignedKelas = DashboardController::getAssignedClass();
            $editor = null;

            // Editor Data
            if (count($kelasMapel->EditorAccess) > 0) {
                $editor = User::where('id', $kelasMapel->EditorAccess[0]->user_id)->first();
                $editor = [
                    'name' => $editor['name'],
                    'id' => $editor['id'],
                ];
            }

            return view('menu.kelasMapel.viewKelasMapel', ['editor' => $editor, 'assignedKelas' => $assignedKelas, 'roles' => $roles, 'title' => 'Dashboard', 'kelasMapel' => $kelasMapel, 'ujian' => $ujian, 'materi' => $materi, 'mapel' => $mapel, 'kelas' => $kelas, 'tugas' => $tugas]);
        } else {
            abort(404);
        }
    }

    /**
     * Metode untuk menyimpan gambar sementara.
     *
     * @return \Illuminate\View\View
     */
    public function saveImageTemp(Request $request)
    {

        $roles = DashboardController::getRolesName();
        $assignedKelas = DashboardController::getAssignedClass();

        return view('menu.mapelKelas.viewKelasMapel', ['assignedKelas' => $assignedKelas, 'roles' => $roles, 'title' => 'Dashboard']);
    }

    public function exportNilaiTugas(Request $request)
    {
        return Excel::download(new NilaiTugasExport($request->tugasId, $request->kelasMapelId), 'export-kelas.xls');
    }

    public function exportNilaiUjian(Request $request)
    {
        return Excel::download(new NilaiUjianExport($request->ujianId, $request->kelasMapelId), 'export-kelas.xls');
    }
}
