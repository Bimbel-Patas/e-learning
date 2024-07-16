<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Ujian;
use App\Models\Kecermatan;
use App\Models\KelasMapel;
use App\Models\UserCommit;
use App\Models\UserJawaban;
use Illuminate\Http\Request;
use App\Models\SoalUjianEssay;
use App\Imports\SoalUjianImport;
use App\Models\SoalUjianMultiple;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\UserJawabanKecermatan;

class UjianController extends Controller
{
    //
    public function viewPilihTipeUjian(Request $request)
    {
        $id = decrypt($request->token);
        $roles = DashboardController::getRolesName();
        $mapel = Mapel::where('id', $request->mapelId)->first();
        $kelasMapel = KelasMapel::where('mapel_id', $request->mapelId)->where('kelas_id', $id)->first();
        $assignedKelas = DashboardController::getAssignedClass();

        return view('menu.pengajar.ujian.viewPilihTipeUjian', ['assignedKelas' => $assignedKelas, 'title' => 'Tambah Ujian', 'roles' => $roles, 'kelasId' => $id, 'mapel' => $mapel]);
    }

    public function destroyUjian(Request $request)
    {
        // Dapatkan Id Materi dari Inputan Form request
        $ujianId = $request->hapusId;
        $tipe = $request->tipe;

        // Logika untuk memeriksa apakah pengguna yang sudah login memiliki akses editor
        foreach (Auth()->User()->EditorAccess as $key) {
            if ($key->kelas_mapel_id == $request->kelasMapelId) {

                Ujian::where('id', $ujianId)->delete();

                if ($tipe == 'multiple') {
                    SoalUjianMultiple::where('ujian_id', $ujianId)->delete();
                    UserJawaban::where('multiple_id', $ujianId)->delete();
                } else {
                    SoalUjianEssay::where('ujian_id', $ujianId)->delete();
                    UserJawaban::where('essay_id', $ujianId)->delete();
                }

                return redirect()->back()->with('success', 'Ujian Berhasil dihapus');
            }
        }
    }

    public function viewCreateUjian(Request $request)
    {

        $id = decrypt($request->token);
        $roles = DashboardController::getRolesName();
        $mapel = Mapel::where('id', $request->mapelId)->first();

        $assignedKelas = DashboardController::getAssignedClass();

        return view('menu.pengajar.ujian.viewTambahUjian', ['assignedKelas' => $assignedKelas, 'tipe' => $request->type, 'title' => 'Tambah Ujian', 'roles' => $roles, 'kelasId' => $id, 'mapel' => $mapel]);
    }

    public function createUjian(Request $request)
    {
        // dd($request->input());

        if (!$request->name) {
            $name = 'Ujian';
        }

        $id = decrypt($request->kelasId);
        $kelasMapel = KelasMapel::where('mapel_id', $request->mapelId)->where('kelas_id', $id)->first();
        $isHidden = 1;

        if ($request->opened) {
            $isHidden = 0;
        }
        // dd($isHidden);
        $tanggalWaktuIndonesia = \Carbon\Carbon::createFromFormat('Y-m-d H:i', $request->due);
        $data = [
            'name' => $request->name,
            'isHidden' => $isHidden,
            'kelas_mapel_id' => $kelasMapel['id'],
            'tipe' => $request->tipe,
            'time' => $request->time,
            'due' => $tanggalWaktuIndonesia,
        ];

        $ujian = Ujian::create($data);

        if ($request->tipe == 'essay') {
            if ($request->pertanyaan) {
                foreach ($request->pertanyaan as $key) {
                    $data = [
                        'ujian_id' => $ujian->id,
                        'soal' => $key,
                    ];

                    if ($key) {
                        SoalUjianEssay::create($data);
                    }
                }
            }
        } elseif ($request->tipe == 'multiple') {
            if ($request->pertanyaan) {
                for ($i = 0; $i < count($request->pertanyaan); $i++) {
                    $d = null;
                    $e = null;

                    if ($request->d[$i]) {
                        $d = $request->d[$i];
                    }

                    if ($request->e[$i]) {
                        $e = $request->e[$i];
                    }

                    $data = [
                        'ujian_id' => $ujian->id,
                        'soal' => $request->pertanyaan[$i],
                        'a' => $request->a[$i],
                        'b' => $request->b[$i],
                        'c' => $request->c[$i],
                        'd' => $d,
                        'e' => $e,
                        'jawaban' => $request->jawaban[$i],
                    ];

                    if ($request->pertanyaan[$i]) {
                        SoalUjianMultiple::create($data);
                    }
                }
            }
        } else {
            if ($request->jumlahSoal) {
                for ($i = 0; $i < count($request->jumlahSoal); $i++) {
                    $d = null;
                    $e = null;

                    if ($request->d[$i]) {
                        $d = $request->d[$i];
                    }

                    if ($request->e[$i]) {
                        $e = $request->e[$i];
                    }

                    $data = [
                        'ujian_id' => $ujian->id,
                        // 'soal' => $request->pertanyaan[$i],
                        'a' => $request->a[$i],
                        'b' => $request->b[$i],
                        'c' => $request->c[$i],
                        'd' => $d,
                        'e' => $e,
                        'jumlah_soal' => $request->jumlahSoal[$i],
                    ];

                    if ($request->jumlahSoal[$i]) {
                        Kecermatan::create($data);
                        $data = [
                            "jumlah_kolom" => count($request->jumlahSoal)
                        ];

                        $ujian->update($data);
                    }
                }
            }
        }

        $kelasMapel = KelasMapel::where('mapel_id', $request->mapelId)->where('kelas_id', $id)->first();
        $message = 'tambah';

        $assignedKelas = DashboardController::getAssignedClass();

        return redirect(route('viewKelasMapel', ['assignedKelas' => $assignedKelas, 'mapel' => $request->mapelId, 'token' => encrypt($id), 'mapel_id' => $request->mapelId]))->with('success', 'Data Berhasil di ' . $message);
    }

    public function viewUjian($token, Request $request)
    {
        $ujianId = decrypt($token);
        $kelasMapelId = decrypt($request->kelasMapelId);
        $mapel = Mapel::where('id', $request->mapelId)->first();
        $kelasMapel = KelasMapel::where('id', $kelasMapelId)->first();
        $roles = DashboardController::getRolesName();
        $ujian = Ujian::where('id', $ujianId)->first();
        $kelas = Kelas::where('id', $kelasMapel['kelas_id'])->first();

        $assignedKelas = DashboardController::getAssignedClass();

        return view('menu.pengajar.ujian.viewUjian', ['assignedKelas' => $assignedKelas, 'title' => 'Tambah Ujian', 'ujian' => $ujian, 'roles' => $roles, 'kelas' => $kelas, 'mapel' => $mapel, 'tipe' => $ujian['tipe']]);
    }


    public function ujianUpdateNilai(Request $request)
    {
        // dd($request);
        $id = decrypt($request->token);
        for ($i = 0; $i < count($request->nilai); $i++) {
            // Memeriksa apakah nilai tidak sama dengan null dan tidak sama dengan string kosong
            if ($request->nilai[$i] !== null && $request->nilai[$i] !== '') {
                $exist = UserJawaban::where('user_id', $request->siswaId[$i])->where('essay_id', $request->soalId[$i])->first();

                $nilai = $request->nilai[$i];

                if ($nilai >= 100) {
                    $nilai = 100;
                } elseif ($nilai <= 0) {
                    $nilai = 0;
                }

                // dd($exist);
                if ($exist) {
                    $data = [
                        'nilai' => $nilai,
                    ];
                    $exist->update($data);
                } else {
                    $data = [
                        'multiple_id' => null,
                        'essay_id' => $request->soalId[$i],
                        'user_id' => $request->siswaId[$i],
                        'nilai' => $nilai,
                    ];
                    UserJawaban::create($data);
                }
            }
        }

        return redirect()->back()->with('success', 'Nilai Telah diPerbaharui');
    }

    public function viewUpdateUjian($token, Request $request)
    {
        $ujianId = decrypt($token);

        $mapel = Mapel::where('id', $request->mapelId)->first();
        $kelasMapel = KelasMapel::where('kelas_id', $request->kelasId)->where('mapel_id', $request->mapelId)->first();
        $roles = DashboardController::getRolesName();
        $ujian = Ujian::where('id', $ujianId)->first();
        $kelas = Kelas::where('id', $kelasMapel['kelas_id'])->first();

        $assignedKelas = DashboardController::getAssignedClass();

        return view('menu.pengajar.ujian.viewUpdateUjian', ['assignedKelas' => $assignedKelas, 'tipe' => $request->type, 'title' => 'Tambah Ujian', 'ujian' => $ujian, 'roles' => $roles, 'kelas' => $kelas, 'mapel' => $mapel]);
    }

    public function updateUjian(Request $request)
    {
        // dd($request);
        // try {
        if (!$request->name) {
            $name = 'Ujian';
        }

        $id = decrypt($request->kelasId);
        $kelasMapel = KelasMapel::where('mapel_id', $request->mapelId)->where('kelas_id', $id)->first();
        $isHidden = 1;

        if ($request->opened) {
            $isHidden = 0;
        }
        // dd($isHidden);

        $ujianId = decrypt($request->token);
        $tanggalWaktuIndonesia = \Carbon\Carbon::createFromFormat('Y-m-d H:i', $request->due);
        $data = [
            'name' => $request->name,
            'isHidden' => $isHidden,
            'time' => $request->time,
            'due' => $tanggalWaktuIndonesia,
        ];

        $ujian = Ujian::where('id', $ujianId)->update($data);

        $temp = null;

        if ($request->tipe == 'essay') {
            $temp = SoalUjianEssay::where('ujian_id', $ujianId)->get();
        } elseif ($request->tipe == 'multiple') {
            $temp = SoalUjianMultiple::where('ujian_id', $ujianId)->get();
        } elseif ($request->tipe == 'kecermatan') {
            $temp = Kecermatan::where('ujian_id', $ujianId)->get();
        }

        $soalIdRequest = $request->pertanyaanId;

        foreach ($temp as $key) {
            $soalUjianIds[] = $key->id;
        }
        $idToDelete = null;

        if ($soalIdRequest && count($temp) > 0) {
            $idToDelete = array_diff($soalUjianIds, $soalIdRequest);
        }

        if ($request->tipe == 'essay') {
            if ($idToDelete) {
                SoalUjianEssay::whereIn('id', $idToDelete)->delete();
            }

            if ($request->pertanyaan) {
                for ($i = 0; $i < count($request->pertanyaan); $i++) {
                    $exist = 0;

                    if ($request->pertanyaanId[$i]) {
                        $exist = SoalUjianEssay::where('id', $request->pertanyaanId[$i])->first();
                    }

                    // Data Building
                    $data = [
                        'ujian_id' => $ujianId,
                        'soal' => $request->pertanyaan[$i],
                    ];

                    if ($exist && $request->pertanyaan[$i]) {
                        SoalUjianEssay::where('id', $request->pertanyaanId[$i])->update($data);
                    } elseif ($request->pertanyaan[$i]) {
                        SoalUjianEssay::create($data);
                    }
                }
            }
        } elseif ($request->tipe == 'multiple') {

            if ($idToDelete) {
                SoalUjianMultiple::whereIn('id', $idToDelete)->delete();
            }

            if ($request->pertanyaan) {
                for ($i = 0; $i < count($request->pertanyaan); $i++) {
                    $exist = 0;

                    if ($request->pertanyaanId[$i]) {
                        $exist = SoalUjianMultiple::where('id', $request->pertanyaanId[$i])->first();
                    }

                    // Data Building
                    $d = null;
                    $e = null;

                    if ($request->d[$i]) {
                        $d = $request->d[$i];
                    }

                    if ($request->e[$i]) {
                        $e = $request->e[$i];
                    }

                    $data = [
                        'ujian_id' => $ujianId,
                        'soal' => $request->pertanyaan[$i],
                        'a' => $request->a[$i],
                        'b' => $request->b[$i],
                        'c' => $request->c[$i],
                        'd' => $d,
                        'e' => $e,
                        'jawaban' => $request->jawaban[$i],
                    ];

                    if ($exist) {
                        SoalUjianMultiple::where('id', $request->pertanyaanId[$i])->update($data);
                    } else {
                        SoalUjianMultiple::create($data);
                    }
                }
            }
        } elseif ($request->tipe == 'kecermatan') {

            if ($idToDelete) {
                Kecermatan::whereIn('id', $idToDelete)->delete();
            }

            if ($request->pertanyaanId) {
                // dd(count($request->pertanyaanId));
                for ($i = 0; $i < count($request->pertanyaanId); $i++) {
                    $exist = 0;

                    if ($request->pertanyaanId[$i]) {
                        $exist = Kecermatan::where('id', $request->pertanyaanId[$i])->first();
                    }

                    // Data Building
                    $d = null;
                    $e = null;

                    if ($request->d[$i]) {
                        $d = $request->d[$i];
                    }

                    if ($request->e[$i]) {
                        $e = $request->e[$i];
                    }

                    $data = [
                        'ujian_id' => $ujianId,
                        'a' => $request->a[$i],
                        'b' => $request->b[$i],
                        'c' => $request->c[$i],
                        'd' => $d,
                        'e' => $e,
                        // 'jawaban' => $request->jawaban[$i],
                        'jumlah_soal' => $request->jumlahSoal[$i],
                    ];

                    if ($exist) {
                        Kecermatan::where('id', $request->pertanyaanId[$i])->update($data);
                    } else {
                        Kecermatan::create($data);
                    }
                }
            }
        }

        $message = 'Update';
        $assignedKelas = DashboardController::getAssignedClass();

        return redirect(route('viewKelasMapel', ['assignedKelas' => $assignedKelas, 'mapel' => $request->mapelId, 'token' => encrypt($id), 'mapel_id' => $request->mapelId]))->with('success', 'Data Berhasil di ' . $message);
    }

    // Export Import
    public function contohEssay()
    {
        $file = public_path() . '/examples/contoh-data-essay.xls';

        return response()->download($file, 'contoh-essay.xls');
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
        session()->forget('soal', []);
        session()->forget('info', []);

        $info = session('info', []);
        array_push($info, $request->name);
        array_push($info, $request->time);
        array_push($info, $request->due);
        session(['info' => $info]); // Menyimpan kembali array $soal ke dalam sesi 'soal'

        // Proses impor data dari Excel
        try {
            if ($request->tipe == 'essay') {
                Excel::import(new SoalUjianImport, $request->file('file')); // Gantilah dengan nama sesuai nama kelas impor Anda

                // dd(session('info')[0]);
                return redirect()->back()->with('soalEssay', session('soal'))->with('info', session('info'));
            } elseif ($request->tipe == 'multiple' || $request->tipe == 'quiz') {
                Excel::import(new SoalUjianImport, $request->file('file')); // Gantilah dengan nama sesuai nama kelas impor Anda

                return redirect()->back()->with('soalMultiple', session('soal'))->with('info', session('info'));
            }
        } catch (\Exception $e) {
            dd(session('soal'));

            return redirect()->back()->with('error', 'action gagal')->with('info', session('info'));
        }
    }

    public function contohMultiple()
    {
        $file = public_path() . '/examples/contoh-data-multiple.xls';

        return response()->download($file, 'contoh-multiple.xls');
    }

    public function ujianAccess($token, Request $request)
    {
        // ujian id
        $id = decrypt($token);
        $ujian = Ujian::where('id', $id)->first();

        $userCommit = UserCommit::where('user_id', Auth()->User()->id)->where('ujian_id', $ujian['id'])->first();


        $mapel = Mapel::where('id', $request->mapelId)->first();
        $kelas = Kelas::where('id', $request->kelasId)->first();
        $data = [
            'content' => $ujian,
            'tipe' => $ujian['tipe'],
        ];

        $roles = DashboardController::getRolesName();
        $assignedKelas = DashboardController::getAssignedClass();
        $quizCommit = 0;
        if ($ujian['tipe'] == 'quiz') {
            if ($userCommit) {
                $quizCommit = 1;
            }
        }

        if ($userCommit && $ujian['tipe'] != 'quiz') {
            if ($userCommit['status'] == 'active') {
                return redirect(route('userUjian', ['ujian' => $ujian['name'], 'token' => encrypt($ujian['id'])]));
            }
        }

        return view('menu.siswa.ujian.ujianAccess', ['quizCommit' =>   $quizCommit, 'ujian' => $ujian, 'userCommit' => $userCommit, 'assignedKelas' => $assignedKelas, 'tipe' => $data['tipe'], 'kelas' => $kelas, 'mapel' => $mapel, 'title' => 'Tambah Ujian', 'ujian' => $ujian, 'roles' => $roles]);
    }

    public function startUjian($token)
    {
        $ujianId = decrypt($token);

        $ujian = Ujian::find($ujianId);

        // if ($ujian->tipe == 'multiple') {
        // }

        $data = [
            'user_id' => auth()->user()->id,  // Perhatikan perubahan pada 'Auth' menjadi 'auth' dan 'User' menjadi 'user'
            'ujian_id' => $ujianId,
            'start_time' => now()->format('Y-m-d H:i:s'),
            'end_time' => now()->addMinutes($ujian->time)->format('Y-m-d H:i:s'),
            'due' => $ujian->due,
        ];

        UserCommit::create($data);

        return redirect(route('userUjian', ['ujian' => $ujian->name, 'token' => encrypt($ujian->id)]));
    }

    public function userUjian($ujian, $token)
    {
        $ujianId = decrypt($token);

        try {
            $userCommit = UserCommit::where('user_id', Auth()->user()->id)->where('ujian_id', $ujianId)->orderBy('created_at', 'desc')->first();
            $userCommitFirst = UserCommit::where('user_id', Auth()->user()->id)->where('ujian_id', $ujianId)->orderBy('created_at', 'asc')->first();
        } catch (Exception $e) {
            abort(404);
        }

        $ujian = Ujian::find($ujianId);

        $roles = DashboardController::getRolesName();
        $assignedKelas = DashboardController::getAssignedClass();

        if ($ujian->tipe == 'multiple') {
            $soalUjianMultiple = $ujian->soalUjianMultiple; // Ambil semua soal dari ujian


            if ($ujian->tipe == 'multiple' && $userCommitFirst['status'] == 'selesai') {
                foreach ($soalUjianMultiple as $key) {
                    UserJawaban::where('user_id', auth()->user()->id)
                        ->where('multiple_id', $key->id)
                        ->delete();
                }
                $userCommitFirst->delete();
                // dd('here');
            }


            return view('menu.siswa.ujian.startUjianMultiple', [
                'userCommit' => $userCommit,
                'ujian' => $ujian,
                'soalUjianMultiple' => $soalUjianMultiple, // Kirim data soal ke view
                'title' => $ujian->name,
                'roles' => $roles,
                'assignedKelas' => $assignedKelas,
            ]);
        } elseif ($ujian->tipe == 'kecermatan') {

            $soalUjianMultiple = $ujian->soalUjianMultiple;

            if ($userCommit->status == 'active' || $userCommit->status == 'selesai') {
                foreach ($ujian->Kecermatan as $key) {
                    UserJawabanKecermatan::where('kecermatan_id', $key->id)->delete();
                }
                // dd($ujian->Kecermatan);
                // dd("here2");
                // userCommit::where('ujian_id', $ujian->id)->delete();
            }
            // dd($userCommit->status);
            foreach ($ujian->Kecermatan as $key) {
                for ($i = 0; $i < $key->jumlah_soal; $i++) {
                    $letters = [$key->a, $key->b, $key->c];

                    if ($key->d !== null) { // Include D if it's not null
                        $letters[] = $key->d;
                    }

                    if ($key->e !== null) { // Include E if it's not null
                        $letters[] = $key->e;
                    }

                    shuffle($letters);
                    $answer = array_pop($letters); // Remove one letter to be the answer

                    if ($key->a == $answer) {
                        $answer = "a";
                    } else if ($key->b == $answer) {
                        $answer = "b";
                    } else if ($key->c == $answer) {
                        $answer = "c";
                    } else if ($key->d == $answer) {
                        $answer = "d";
                    } else if ($key->e == $answer) {
                        $answer = "e";
                    }

                    $soal = implode('', $letters); // Convert the array to a string

                    $data = [
                        "user_id" => auth()->user()->id,
                        "soal" => $soal,
                        "jawaban" => $answer,
                        "kecermatan_id" => $key->id,
                    ];

                    UserJawabanKecermatan::create($data);
                }
            }

            $data = ["status" => "selesai"];
            $userCommit->update($data);

            $userJawabanKecermatan = UserJawabanKecermatan::where('user_id', Auth()->user()->id)
                ->whereIn('kecermatan_id', $ujian->Kecermatan->pluck('id'))
                ->get();

            return view('menu.siswa.ujian.startUjianKecermatan', [
                'userCommit' => $userCommit,
                'ujian' => $ujian,
                'userJawabanKecermatan' => $userJawabanKecermatan, // Kirim data soal ke view
                'title' => $ujian->name,
                'roles' => $roles,
                'assignedKelas' => $assignedKelas,
            ]);
        } else {
            $soalUjianEssay = $ujian->SoalUjianEssay; // Ambil semua soal dari ujian

            return view('menu.siswa.ujian.startUjian', [
                'userCommit' => $userCommit,
                'ujian' => $ujian,
                'soalUjianEssay' => $soalUjianEssay, // Kirim data soal ke view
                'title' => $ujian->name,
                'roles' => $roles,
                'assignedKelas' => $assignedKelas,
            ]);
        }
    }



    public function simpanJawaban(Request $request)
    {
        $soalId = $request->input('soal_id');
        $jawaban = $request->input('jawaban');

        // return response()->json(['message' => $soalId]);
        // Periksa apakah jawaban sudah ada atau perlu dibuat
        $existingJawaban = UserJawaban::where('user_id', auth()->user()->id)
            ->where('essay_id', $soalId)
            ->first();

        if ($existingJawaban) {
            // Jika jawaban sudah ada, perbarui jawaban
            $existingJawaban->update(['user_jawaban' => $jawaban]);
        } else {
            // Jika jawaban belum ada, buat jawaban baru
            UserJawaban::create([
                'user_id' => auth()->user()->id,
                'essay_id' => $soalId,
                'user_jawaban' => $jawaban,
            ]);
        }

        return response()->json(['message' => 'Jawaban berhasil disimpan.']);
    }
    public function simpanJawabanKecermatan(Request $request)
    {
        $soalId = $request->input('soal_id');
        $jawaban = $request->input('jawaban');

        // return response()->json(['message' => $soalId]);
        // Periksa apakah jawaban sudah ada atau perlu dibuat
        $existingJawaban = UserJawabanKecermatan::where('user_id', auth()->user()->id)
            ->where('id', $soalId)
            ->first();

        if ($existingJawaban) {
            // Jika jawaban sudah ada, perbarui jawaban
            $existingJawaban->update(['jawaban_user' => $jawaban]);
        } else {
            // Jika jawaban belum ada, buat jawaban baru
            UserJawabanKecermatan::create([
                'user_id' => auth()->user()->id,
                'essay_id' => $soalId,
                'jawaban_user' => $jawaban,
            ]);
        }

        return response()->json(['message' => 'Jawaban berhasil disimpan.']);
    }

    public function getJawaban(Request $request)
    {
        // return response()->json(['jawaban' => $request->input()]);
        // Mengambil jawaban dari database berdasarkan ID soal
        $jawaban = UserJawaban::where('essay_id', $request->soal_id)->where('user_id', Auth()->User()->id)->first();

        // return response()->json(['jawaban' => $jawaban]);
        if ($jawaban) {
            return response()->json(['jawaban' => $jawaban->user_jawaban]);
        } else {
            return response()->json(['jawaban' => null]);
        }
    }

    public function getJawabanMultiple(Request $request)
    {
        // return response()->json(['jawaban' => $request->input()]);
        // Mengambil jawaban dari database berdasarkan ID soal
        $jawaban = UserJawaban::where('multiple_id', $request->soal_id)->where('user_id', Auth()->User()->id)->first();

        // return response()->json(['jawaban' => $jawaban]);
        if ($jawaban) {
            return response()->json(['jawaban' => $jawaban->user_jawaban]);
        } else {
            return response()->json(['jawaban' => null]);
        }
    }

    public function selesaiUjian(Request $request)
    {
        $id = decrypt($request->userCommit);

        $userCommit = UserCommit::where('id', $id)->first();

        $data = [
            'status' => 'selesai',
        ];

        $userCommit->update($data);

        $ujian = Ujian::where('id', $userCommit['id'])->first();
        $kelasMapel = KelasMapel::where('id', $ujian['id'])->first();

        return redirect('home')->with('success', 'Ujian berhasil di submit');
    }

    public function selesaiUjianMultiple(Request $request)
    {

        try {
            $id = decrypt($request->userCommit);

            $userCommit = UserCommit::where('id', $id)->first();

            $data = [
                'status' => 'selesai',
            ];

            $userCommit->update($data);

            $ujian = Ujian::where('id', $userCommit['ujian_id'])->first();

            $countUjian = count($ujian->SoalUjianMultiple);
            // dd($countUjian);
            $jawabanUser = UserJawaban::where('multiple_id', $ujian['id'])->where('user_id', Auth()->User()->id)->get();

            $nilaiPerSoal = 100 / $countUjian;
            if (count($jawabanUser) > 0) {
                foreach ($ujian->SoalUjianMultiple as $key) {
                    $jawabanUser = UserJawaban::where('multiple_id', $key->id)->where('user_id', Auth()->User()->id)->first();
                    // dd($key->jawaban, $jawabanUser['user_jawaban'], $key->id);
                    if (strcasecmp($key->jawaban, $jawabanUser['user_jawaban']) === 0) {
                        // dd('benar');
                        UserJawaban::where('multiple_id', $key->id)->where('user_id', Auth()->User()->id)->update(['nilai' => $nilaiPerSoal]);
                    } else {
                        // dd('salah');
                        UserJawaban::where('multiple_id', $key->id)->where('user_id', Auth()->User()->id)->update(['nilai' => 0]);
                    }
                }
            }
            return redirect('home')->with('success', 'Ujian berhasil di submit');
        } catch (Exception $e) {
            return redirect('home')->with('success', 'Ujian berhasil di submit');
        }
    }
    public function selesaiUjianKecermatan(Request $request)
    {
        return redirect('home')->with('success', 'Ujian berhasil di submit');
    }

    public function simpanJawabanMultiple(Request $request)
    {
        $soalId = $request->input('soal_id');
        $jawaban = $request->input('jawaban');

        // return response()->json(['message' => $soalId]);
        // Periksa apakah jawaban sudah ada atau perlu dibuat
        $existingJawaban = UserJawaban::where('user_id', auth()->user()->id)
            ->where('multiple_id', $soalId)
            ->first();

        if ($existingJawaban) {
            // Jika jawaban sudah ada, perbarui jawaban
            $existingJawaban->update(['user_jawaban' => $jawaban]);
        } else {
            // Jika jawaban belum ada, buat jawaban baru
            UserJawaban::create([
                'user_id' => auth()->user()->id,
                'multiple_id' => $soalId,
                'user_jawaban' => $jawaban,
            ]);
        }

        return response()->json(['message' => 'Jawaban berhasil disimpan.']);
    }
}
