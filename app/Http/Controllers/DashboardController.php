<?php

namespace App\Http\Controllers;

use App\Models\DataSiswa;
use App\Models\EditorAccess;
use App\Models\Kelas;
use App\Models\KelasMapel;
use App\Models\Mapel;
use App\Models\Materi;
use App\Models\Role;
use App\Models\Tugas;
use App\Models\Ujian;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;

/**
 * Class : DashboardController
 *
 * Kelas ini mengelola berbagai fungsi yang berkaitan dengan pengguna dan dasbor,
 *
 * @copyright  2023 Sunday Interactive
 * @license    http://www.zend.com/license/3_0.txt   PHP License 3.0
 *
 * @version    Release: 1.0
 *
 * @link       http://dev.zend.com/package/PackageName
 * @since      Kelas ini tersedia sejak Rilis 1.0
 */
class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dasbor. Pengguna akan diarahkan berdasarkan roles id mereka.
     *
     * @return \Illuminate\View\View
     */
    public function viewDashboard()
    {
        // Mengumpulkan beberapa informasi tentang pengguna.
        $authRoles = $this->getAuthId();
        $authRolesName = $this->getRolesName();

        // Pengkondisian
        // Roles_id : 1 = Admin
        // Roles_id : 2 = Pengajar
        // Roles_id : 3 = Siswa
        if ($authRoles == 1) {

            $data = [
                'totalSiswa' => count(DataSiswa::get()),
                'totalUserSiswa' => count(User::where('roles_id', 3)->get()),
                'totalPengajar' => count(User::where('roles_id', 2)->get()),
                'totalKelas' => count(Kelas::get()),
                'totalMapel' => count(Mapel::get()),
                'totalMateri' => count(Materi::get()),
                'totalTugas' => count(Tugas::get()),
                'totalUjian' => count(Ujian::get()),
            ];

            return view('menu/admin/dashboard/dashboard', ['materi' => Materi::all(), 'title' => 'Dashboard', 'roles' => $authRolesName, 'data' => $data]);
        } elseif ($authRoles == 2) {
            try {
                // Dapatkan ID Pengguna
                $id = Auth()->User()->id;

                // Kueri
                $roles = DashboardController::getRolesName();
                $profile = User::findOrFail($id);
                $editorAccess = EditorAccess::where('user_id', $id)->get();

                // Inisialisasi Array Kosong
                $mapelKelas = [];
                $totalSiswa = 0;
                $totalSiswaUnique = [];
                $kelasMapelId = [];
                $kelasInfo = [];
                // Membangun Data yang berkaitan dengan Pengguna dan apa yang mereka Ajar.
                // Sehingga akan muncul di Dasbor apa yang mereka ajar (Editor Access).
                foreach ($editorAccess as $key) {
                    $kelasMapel = KelasMapel::where('id', $key->kelas_mapel_id)->first();

                    if ($kelasMapel) {
                        $mapelID = $kelasMapel->mapel_id;
                        $kelasID = $kelasMapel->kelas_id;

                        // Pemeriksa Mapel
                        $mapelKey = array_search($mapelID, array_column($mapelKelas, 'mapel_id'));

                        if ($mapelKey !== false) {
                            // Tambahkan ke Array
                            $mapelKelas[$mapelKey]['kelas'][] = Kelas::where('id', $kelasID)->first();
                        } else {
                            // Temukan Mapel
                            $mapelKelas[] = [
                                'mapel_id' => $mapelID,
                                'mapel' => Mapel::where('id', $mapelID)->first(),
                                'kelas' => [Kelas::where('id', $kelasID)->first()],
                            ];
                            array_push($kelasMapelId, $kelasMapel['id']);
                        }

                        // Count Siswa
                        $siswa = DataSiswa::where('kelas_id', $kelasID)->get();
                        $totalSiswa += count($siswa);

                        // Extract unique student IDs
                        $totalSiswaUnique = array_merge($totalSiswaUnique, $siswa->pluck('id')->toArray());
                        // $totalSiswaUnique = $siswa->pluck('id');
                    }
                }

                // dd($kelasMapelId);
                $totalSiswaUnique = array_unique($totalSiswaUnique);
                $totalSiswaUnique = count($totalSiswaUnique);

                $assignedKelas = $this->getAssignedClass();

                return view('menu/pengajar/dashboard/dashboard', ['kelasInfo' => $kelasInfo, 'kelasMapelId' => $kelasMapelId, 'totalSiswaUnique' => $totalSiswaUnique, 'totalSiswa' => $totalSiswa, 'assignedKelas' => $assignedKelas, 'user' => $profile, 'countKelas' => count($editorAccess), 'mapelKelas' => $mapelKelas, 'roles' => $roles, 'title' => 'Dashboard']);
            } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
                abort(404);
            }
        } elseif ($authRoles == 3) {
            return redirect('home');
        }
    }

    /**
     * Menampilkan halaman dasbor. Pengguna akan diarahkan berdasarkan roles id mereka.
     *
     * @return \Illuminate\View\View
     */
    public function viewHome()
    {
        // Mengumpulkan beberapa informasi tentang pengguna.
        $authRoles = $this->getAuthId();
        $authRolesName = $this->getRolesName();

        try {
            // $id = Crypt::decrypt($token);

            $roles = DashboardController::getRolesName();
            $profile = User::findOrFail(Auth()->User()->id);

            $kelas = Kelas::where('id', $profile->kelas_id)->first();

            $kelasMapel = KelasMapel::where('kelas_id', $kelas['id'])->get();
            $mapelCollection = [];

            foreach ($kelasMapel as $key) {
                $mapel = Mapel::where('id', $key->mapel_id)->first();
                $editorAccess = EditorAccess::where(
                    'kelas_mapel_id',
                    $key->id
                )->first();

                if ($editorAccess) {
                    $editorAccess = $editorAccess['user_id'];
                    $pengajar = User::where('id', $editorAccess)->first(['id', 'name']);
                    $pengajarNama = $pengajar['name'];
                    $pengajarId = $pengajar['id'];
                } else {
                    $pengajarNama = '-';
                    $pengajarId = null;
                }

                $mapelCollection[] = [
                    'mapel_name' => $mapel['name'],
                    'mapel_id' => $mapel['id'],
                    'deskripsi' => $mapel['deskripsi'],
                    'gambar' => $mapel['gambar'],
                    'pengajar_id' => $pengajarId,
                    'pengajar_name' => $pengajarNama,
                ];
            }

            $assignedKelas = DashboardController::getAssignedClass();

            return view('menu/siswa/home/home', ['assignedKelas' => $assignedKelas, 'title' => 'Home', 'roles' => $authRolesName, 'user' => $profile, 'kelas' => $kelas, 'mapelKelas' => $mapelCollection, 'roles' => $roles, 'title' => 'Profil']);

            return view('menu.profile.profileSiswa', ['assignedKelas' => $assignedKelas, 'user' => $profile, 'kelas' => $kelas['name'], 'mapelKelas' => $mapelCollection, 'roles' => $roles, 'title' => 'Profil']);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            abort(404);
        }
    }

    /**
     * Mendapatkan nama peran pengguna (digunakan dalam beberapa metode lain dalam kelas lain).
     * Ini merupakan akses dasar untuk mendapatkan peran yang akan dirender.
     *
     * @return string
     */
    public static function getRolesName()
    {
        // Dapatkan ID -> Kueri -> Kembalikan sebagai string
        $authRoles = Auth()->User()->roles_id;
        $authRolesName = Role::where('id', $authRoles)->first('name');
        $authRolesName = $authRolesName['name'];

        return $authRolesName;
    }

    /**
     * Mendapatkan roles id (jarang digunakan dalam kelas lain).
     *
     * @return int
     */
    public static function getAuthId()
    {
        return Auth()->User()->roles_id;
    }

    /**
     * Mendapatkan nama peran pengguna (digunakan dalam beberapa metode lain dalam kelas lain).
     * Ini merupakan akses dasar untuk mendapatkan peran yang akan dirender.
     *
     * @return array
     */
    public static function getAssignedClass()
    {
        $authRoles = Auth()->User()->roles_id;

        // Pengkondisian
        // Roles_id : 1 = Admin
        // Roles_id : 2 = Pengajar
        // Roles_id : 3 = Siswa
        if ($authRoles == 1) {
            return null;
        } elseif ($authRoles == 2) {
            try {
                // Dapatkan ID Pengguna
                $id = Auth()->User()->id;

                // Kueri
                $profile = User::findOrFail($id);
                $editorAccess = EditorAccess::where('user_id', $id)->get();

                // Inisialisasi Array Kosong
                $mapelKelas = [];

                // Membangun Data yang berkaitan dengan Pengguna dan apa yang mereka Ajar.
                // Sehingga akan muncul di Dasbor apa yang mereka ajar (Editor Access).
                foreach ($editorAccess as $key) {
                    $kelasMapel = KelasMapel::where('id', $key->kelas_mapel_id)->first();

                    if ($kelasMapel) {
                        $mapelID = $kelasMapel->mapel_id;
                        $kelasID = $kelasMapel->kelas_id;

                        // Pemeriksa Mapel
                        $mapelKey = array_search($mapelID, array_column($mapelKelas, 'mapel_id'));

                        if ($mapelKey !== false) {
                            // Tambahkan ke Array
                            $mapelKelas[$mapelKey]['kelas'][] = Kelas::where('id', $kelasID)->first();
                        } else {
                            // Temukan Mapel
                            $mapelKelas[] = [
                                'mapel_id' => $mapelID,
                                'mapel' => Mapel::where('id', $mapelID)->first(),
                                'kelas' => [Kelas::where('id', $kelasID)->first()],
                            ];
                        }
                    }
                }

                return $mapelKelas;
            } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
                abort(404);
            }
        } elseif ($authRoles == 3) {
            try {
                // Dapatkan ID Pengguna
                $id = Auth()->User()->kelas_id;

                // Kueri
                $kelasMapelId = KelasMapel::where('kelas_id', $id)->get();

                // Inisialisasi Array Kosong
                $mapelKelas = [];

                // Membangun Data yang berkaitan dengan Pengguna dan apa yang mereka Ajar.
                // Sehingga akan muncul di Dasbor apa yang mereka ajar (Editor Access).
                foreach ($kelasMapelId as $key) {
                    // Temukan Mapel
                    $mapelKelas[] = [
                        'mapel_id' => $key->mapel_id,
                        'mapel' => Mapel::where('id', $key->mapel_id)->first(),
                        'kelas' => [Kelas::where('id', $key->kelas_id)->first()],
                    ];
                }

                // dd($mapelKelas);
                return $mapelKelas;
            } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
                abort(404);
            }
        }
    }

    /**
     * Mendapatkan nama peran pengguna (digunakan dalam beberapa metode lain dalam kelas lain).
     * Ini merupakan akses dasar untuk mendapatkan peran yang akan dirender.
     *
     * @return array
     */
    public static function getAssignedClassSiswa()
    {
        $authRoles = Auth()->User()->roles_id;

        // Pengkondisian
        // Roles_id : 1 = Admin
        // Roles_id : 2 = Pengajar
        // Roles_id : 3 = Siswa
        if ($authRoles == 1) {
            return null;
        } elseif ($authRoles == 2) {
            try {
                // Dapatkan ID Pengguna
                $id = Auth()->User()->id;

                // Kueri
                $profile = User::findOrFail($id);
                $editorAccess = EditorAccess::where('user_id', $id)->get();

                // Inisialisasi Array Kosong
                $mapelKelas = [];

                // Membangun Data yang berkaitan dengan Pengguna dan apa yang mereka Ajar.
                // Sehingga akan muncul di Dasbor apa yang mereka ajar (Editor Access).
                foreach ($editorAccess as $key) {
                    $kelasMapel = KelasMapel::where('id', $key->kelas_mapel_id)->first();

                    if ($kelasMapel) {
                        $mapelID = $kelasMapel->mapel_id;
                        $kelasID = $kelasMapel->kelas_id;

                        // Pemeriksa Mapel
                        $mapelKey = array_search($mapelID, array_column($mapelKelas, 'mapel_id'));

                        if ($mapelKey !== false) {
                            // Tambahkan ke Array
                            $mapelKelas[$mapelKey]['kelas'][] = Kelas::where('id', $kelasID)->first();
                        } else {
                            // Temukan Mapel
                            $mapelKelas[] = [
                                'mapel_id' => $mapelID,
                                'mapel' => Mapel::where('id', $mapelID)->first(),
                                'kelas' => [Kelas::where('id', $kelasID)->first()],
                            ];
                        }
                    }
                }

                return $mapelKelas;
            } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
                abort(404);
            }
        } elseif ($authRoles == 3) {
            return null;
        }
    }
}
