@extends('layout.template.mainTemplate')

@section('container')
    @if (Auth()->user()->roles_id == 1)
        @include('menu.admin.adminHelper')
    @endif

    {{-- Navigasi Breadcrumb --}}
    <div class="col-12 ps-4 pe-4 mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-white">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $mapel['name'] }}</li>
            </ol>
        </nav>
    </div>

    {{-- Judul Halaman --}}
    <div class="ps-4 pe-4">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="display-6 fw-bold ">
                <a href="{{ route('dashboard') }}">
                    <button type="button" class="btn btn-outline-dark rounded-circle">
                        <i class="fa-solid fa-arrow-left"></i>
                    </button>
                </a> {{ $mapel['name'] }}
                <!-- <span class="badge badge-secondary">{{ $kelas['name'] }}</span> -->
            </h1>
        </div>
    </div>

    {{-- Pesan Sukses --}}
    @if (session()->has('success'))
        <div class="alert alert-lg alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- {{-- Informasi Mata Pelajaran --}}
    <div class="bg-body-secondary rounded-4 mb-4">
        <div class="container col-xxl-8 px-4 py-5 ">
            <div class="row flex-lg-row-reverse align-items-center g-5 py-5">
                <div class="col-10 col-sm-8 col-lg-6">
                    @if ($mapel['gambar'] != null)
                        <img src="{{ asset('storage/mapel/' . $mapel['gambar']) }}"
                            class="d-block mx-lg-auto img-fluid w-100 rounded-3" alt="Bootstrap Themes" loading="lazy">
                    @else
                        <img src="{{ url('/asset/img/work.png') }}" class="d-block mx-lg-auto img-fluid h-50 w-50 "
                            alt="" loading="lazy">
                    @endif
                </div>
                <div class="col-lg-6">
                    <h1 class=" fw-bold text-body-emphasis lh-1 mb-3">{{ $mapel['name'] }}</h1>
                    <span class="small">
                        @if ($editor)
                            with
                            <a
                                href="{{ route('viewProfilePengajar', ['token' => encrypt($editor['id'])]) }}">{{ $editor['name'] }}</a>
                        @else
                            (belum ada pengajar)
                        @endif
                    </span>
                    <p class="lead">{{ $mapel['deskripsi'] }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Section Tugas, Materi --}}
    <div class="row ps-4 pe-4 mb-4" id="materi">
        <div class=" col-lg-12 col-md-12">
            <h3 class=" fw-bold text-primary"><i class="fa-solid fa-book"></i> Materi
                @if (Auth()->User()->roles_id == 2)
                    <a
                        href="{{ route('viewCreateMateri', ['token' => encrypt($kelas['id']), 'mapelId' => $mapel['id']]) }}">
                        <button type="button" class="btn btn-outline-primary">+ Tambah</button>

                    </a>
                @endif
            </h3>
            <div class="p-4 bg-white rounded-3">
                <div class="row">
                    {{-- Tabel Materi --}}
                    <div class="table-responsive col-lg-6 col-12 p-3" style="max-height: 300px; overflow-y:auto;">
                        @if (count($materi) > 0)
                            <table id="table" class="table table-striped table-hover table-lg p-3">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Nama Materi</th>
                                        @if (Auth()->User()->roles_id == 2)
                                            <th scope="col">Created at</th>
                                        @endif
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($materi as $key)
                                        @if ($key->isHidden != 1 || Auth()->User()->roles_id == 2)
                                            <tr class=" @if ($key->isHidden == 1) opacity-50 @endif">
                                                <td>{{ $loop->iteration }}</td>
                                                <td>
                                                    {{ $key->name }}
                                                    @if ($key->isHidden == 1)
                                                        <i class="fa-solid fa-eye-slash fa-bounce text-danger"></i>
                                                    @endif
                                                </td>
                                                @if (Auth()->User()->roles_id == 2)
                                                    <td>
                                                        {{ $key->created_at->format('d F Y H:i') }}
                                                    </td>
                                                @endif
                                                @if (Auth()->User()->roles_id == 2)
                                                    <td>
                                                        <a href="{{ route('viewMateri', ['token' => encrypt($key->id), 'kelasMapelId' => encrypt($key['kelas_mapel_id']), 'mapelId' => $mapel['id']]) }}"
                                                            class="badge bg-info p-2 mb-1 animate-btn-small">
                                                            <i class="fa-regular fa-eye fa-xl"></i>
                                                        </a>
                                                        <a href="{{ route('viewUpdateMateri', ['token' => encrypt($key->id), 'mapelId' => $mapel['id']]) }}"
                                                            class="badge bg-secondary p-2 mb-1 animate-btn-small">
                                                            <i class="fa-solid fa-pen-to-square fa-xl"></i>
                                                        </a>
                                                        <a href="#table" class="badge bg-secondary p-2 animate-btn-small">
                                                            <i class="fa-solid fa-xl fa-trash" data-bs-toggle="modal"
                                                                data-bs-target="#modalHapusMateri"
                                                                onclick="changeValueMateri({{ $key->id }})"></i>
                                                        </a>
                                                    </td>
                                                @else
                                                    <td>
                                                        <a
                                                            href="{{ route('viewMateri', ['token' => encrypt($key->id), 'kelasMapelId' => encrypt($key['kelas_mapel_id']), 'mapelId' => $mapel['id']]) }}">
                                                            <button type="button" class="btn btn-primary"><i
                                                                    class="fa-regular fa-eye fa-xl"></i>
                                                                View</button>
                                                        </a>
                                                    </td>
                                                @endif
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="text-center">
                                <img src="{{ url('/asset/img/not-found.png') }}" alt="" class="img-fluid w-50"
                                    style="filter: saturate(0);" srcset="">
                                <br>
                                <Strong>Belum ada Materi</Strong>
                            </div>
                        @endif
                    </div>

                    {{-- Tabel Kanan --}}
                    <div class="  p-4 col-lg-6 col-12">
                        <div class="border border-primary rounded-2  h-100 p-3">
                            <h6 class="text-primary fw-bold text-center">Materi</h6>

                            <p>Materi berfungsi sebagai akses materi pembelajaran, referensi untuk
                                belajar mandiri, pemantauan kemajuan, dan sumber referensi bagi pengguna dalam memahami
                                materi, persiapan ujian, serta pengayaan pengetahuan.</p>
                        </div>

                    </div>
                </div>

            </div>
        </div>

        <div class=" col-lg-12 col-md-12 mt-4">
            <h3 class="text-primary fw-bold "><i class="fa-solid fa-pen"></i> Tugas
                @if (Auth()->User()->roles_id == 2)
                    <a
                        href="{{ route('viewCreateTugas', ['token' => encrypt($kelas['id']), 'mapelId' => $mapel['id']]) }}">
                        <button type="button" class="btn btn-outline-primary">+ Tambah</button>
                    </a>
                @endif
            </h3>
            <div class="p-4 bg-white rounded-3">

                <div class="row">
                    {{-- Tabel Tugas --}}
                    <div class="table-responsive col-lg-6 col-12" style="max-height: 300px; overflow-y: auto;">
                        @if (count($tugas) > 0)
                            <table id="table" class="table table-hover table-striped table-lg ">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Nama Tugas</th>
                                        <th scope="col">Due Date</th>
                                        @if (Auth()->User()->roles_id == 2)
                                            <th scope="col">Created at</th>
                                        @endif
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($tugas as $key)
                                        @if ($key->isHidden != 1 || Auth()->User()->roles_id == 2)
                                            <tr class=" @if ($key->isHidden == 1) opacity-50 @endif">
                                                <td>{{ $loop->iteration }}</td>
                                                <td>
                                                    {{ $key->name }}
                                                    @if ($key->isHidden == 1)
                                                        <i class="fa-solid fa-eye-slash fa-bounce text-danger"></i>
                                                    @endif
                                                </td>
                                                <td>
                                                    @php
                                                        $dueDate = \Carbon\Carbon::parse($key->due);
                                                        $now = \Carbon\Carbon::now();
                                                        $daysUntilDue = $dueDate->diffInDays($now);
                                                    @endphp
                                                    @if ($dueDate->isPast())
                                                        <span class="badge badge-secondary">Selesai</span>
                                                    @else
                                                        @if ($daysUntilDue == 0)
                                                            <span class="badge badge-primary">Mendekati Deadline</span>
                                                        @else
                                                            <span class="badge badge-primary">{{ $daysUntilDue }} hari
                                                                lagi</span>
                                                        @endif
                                                    @endif
                                                </td>
                                                @if (Auth()->User()->roles_id == 2)
                                                    <td>
                                                        {{ $key->created_at->format('d F Y H:i') }}
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('viewTugas', ['token' => encrypt($key->id), 'kelasMapelId' => encrypt($key['kelas_mapel_id']), 'mapelId' => $mapel['id']]) }}"
                                                            class="badge badge-info p-2 mb-1 animate-btn-small"><i
                                                                class="fa-regular fa-eye fa-xl"></i></a>
                                                        <a href="{{ route('viewUpdateTugas', ['token' => encrypt($key->id), 'kelasMapelId' => encrypt($key['kelas_mapel_id']), 'mapelId' => $mapel['id']]) }}"
                                                            class="badge bg-secondary p-2 mb-1 animate-btn-small"><i
                                                                class="fa-solid fa-pen-to-square fa-xl"></i></a>
                                                        <a href="#table"
                                                            class="badge bg-secondary p-2 animate-btn-small"><i
                                                                class="fa-solid fa-xl fa-trash" data-bs-toggle="modal"
                                                                data-bs-target="#modalHapusTugas"
                                                                onclick="changeValueTugas({{ $key->id }})"></i></a>

                                                        <a
                                                            href="{{ route('exportNilaiTugas', ['tugasId' => $key->id, 'kelasMapelId' => $kelasMapel->id]) }}">
                                                            <button type="button" class="btn btn-outline-success"><i
                                                                    class="fa-solid fa-file-export"></i>
                                                                Export</button>
                                                        </a>

                                                    </td>
                                                @else
                                                    <td>
                                                        <a
                                                            href="{{ route('viewTugas', ['token' => encrypt($key->id), 'kelasMapelId' => encrypt($key['kelas_mapel_id']), 'mapelId' => $mapel['id']]) }}">
                                                            <button type="button" class="btn btn-primary"><i
                                                                    class="fa-regular fa-eye fa-xl"></i>
                                                                View</button>
                                                        </a>
                                                    </td>
                                                @endif
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="text-center">
                                <img src="{{ url('/asset/img/not-found.png') }}" alt="" class="img-fluid w-50"
                                    style="filter: saturate(0);" srcset="">
                                <br>
                                <Strong>Belum ada Tugas</Strong>
                            </div>
                        @endif
                    </div>
                    {{-- Tabel Kanan --}}
                    <div class="  p-4 col-lg-6 col-12">
                        <div class="border border-primary rounded-2  h-100 p-3">
                            <h6 class="text-primary fw-bold text-center">Tugas</h6>

                            <p>Tugas menjadi instrumen penting dalam proses pengukuran pengetahuan dan keterampilan peserta
                                ujian. Mereka membantu dalam evaluasi dan pemantauan kemampuan siswa atau peserta ujian
                                secara efisien</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> -->

    {{-- Section Ujian --}}
    <div class="mb-4 ps-4 pe-4">
        <h3 class="text-primary fw-bold "><i class="fa-solid fa-newspaper"></i> Ujian
            @if (Auth()->User()->roles_id == 2)
                <a
                    href="{{ route('viewPilihTipeUjian', ['token' => encrypt($kelas['id']), 'mapelId' => $mapel['id']]) }}">
                    <button type="button" class="btn btn-outline-primary">+ Tambah</button>
                </a>
            @endif
        </h3>
        <div class="p-4 bg-white rounded-3">

            {{-- Tabel Ujian --}}
            <div class="table-responsive col-12 ">
                @if (count($ujian) > 0)
                    <table id="table" class="table table-hover table-striped table-lg ">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nama Ujian</th>
                                <th scope="col">Time</th>
                                <th scope="col">Tipe Soal</th>
                                <th scope="col">Jumlah Soal</th>
                                <th scope="col">Due Date</th>
                                @if (Auth()->User()->roles_id == 2)
                                    <th scope="col">Created at</th>
                                @endif
                                <th scope="col">Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($ujian as $key)
                                @if ($key->isHidden != 1 || Auth()->User()->roles_id == 2)
                                    <tr class=" @if ($key->isHidden == 1) opacity-50 @endif">
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $key->name }}
                                            @if ($key->isHidden == 1)
                                                <i class="fa-solid fa-eye-slash fa-bounce text-danger"></i>
                                            @endif
                                        </td>
                                        <td>{{ $key->time }} @if ($key->tipe == 'multiple' || $key->tipe == 'essay')
                                                Menit
                                            @else
                                                Detik / Kolom
                                            @endif
                                        </td>


                                        @if ($key->tipe == 'multiple')
                                            <td><span class="badge p-2 badge-dark">Pilihan Ganda</span></td>
                                            <td>{{ count($key->SoalUjianMultiple) }}</td>
                                        @elseif($key->tipe == 'essay')
                                            <td><span class="badge p-2 badge-dark">Essay</span></td>
                                            <td>{{ count($key->SoalUjianEssay) }}</td>
                                        @elseif($key->tipe == 'kecermatan')
                                            <td><span class="badge p-2 badge-dark">Kecermatan</span></td>
                                            <td>{{ count($key->Kecermatan) }}</td>
                                        @endif
                                        <td>
                                            @php
                                                $dueDate = \Carbon\Carbon::parse($key->due);
                                                $now = \Carbon\Carbon::now();
                                                $daysUntilDue = $dueDate->diffInDays($now);
                                            @endphp
                                            @if ($dueDate->isPast())
                                                <span class="badge badge-secondary">Selesai</span>
                                            @else
                                                @if ($daysUntilDue == 0)
                                                    <span class="badge badge-primary">Mendekati Deadline</span>
                                                @else
                                                    <span class="badge badge-primary">{{ $daysUntilDue }} hari lagi</span>
                                                @endif
                                            @endif
                                        </td>
                                        @if (Auth()->User()->roles_id == 2)
                                            <td> {{ $key->created_at->format('d F Y H:i') }}</td>
                                            <td>
                                                <a href="{{ route('viewUjian', ['token' => encrypt($key->id), 'kelasMapelId' => encrypt($key['kelas_mapel_id']), 'mapelId' => $mapel['id']]) }}"
                                                    class="badge badge-info p-2 mb-1 animate-btn-small"><i
                                                        class="fa-regular fa-eye fa-xl"></i></a>
                                                <a href="{{ route('viewUpdateUjian', ['token' => encrypt($key->id), 'kelasId' => $kelas['id'], 'mapelId' => $mapel['id']]) }}"
                                                    class="badge bg-secondary p-2 mb-1 animate-btn-small"><i
                                                        class="fa-solid fa-pen-to-square fa-xl"></i></a>
                                                <a href="#table" class="badge bg-secondary p-2 animate-btn-small"><i
                                                        class="fa-solid fa-xl fa-trash" data-bs-toggle="modal"
                                                        data-bs-target="#modalHapusUjian"
                                                        onclick="changeValueUjian({{ $key->id }} , '{{ $key->tipe }}')"></i></a>
                                                @if ($key->tipe != 'kecermatan')
                                                    <a
                                                        href="{{ route('exportNilaiUjian', ['ujianId' => $key->id, 'kelasMapelId' => $kelasMapel->id]) }}">
                                                        <button type="button" class="btn btn-outline-success"><i
                                                                class="fa-solid fa-file-export"></i>
                                                            Export</button>
                                                    </a>
                                                @endif
                                            </td>
                                        @else
                                            <td>
                                                <a
                                                    href="{{ route('ujianAccess', ['token' => encrypt($key->id), 'kelasMapelId' => encrypt($key['kelas_mapel_id']), 'kelasId' => $kelas['id'], 'mapelId' => $mapel['id']]) }}">
                                                    <button type="button" class="btn btn-primary"><i
                                                            class="fa-regular fa-eye fa-xl"></i>
                                                        View</button>
                                                </a>
                                            </td>
                                        @endif
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="text-center">
                        <img src="{{ url('/asset/img/not-found.png') }}" alt="" class="img-fluid w-25"
                            style="filter: saturate(0);" srcset="">
                        <br>
                        <Strong>Belum ada Ujian</Strong>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Modal Konfirmasi Hapus Materi --}}
    <div class="modal fade" id="modalHapusMateri" tabindex="-1" aria-labelledby="modalHapusMateriLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalHapusMateriLabel">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menghapus Materi ini?
                </div>
                <div class="modal-footer">
                    <form action="{{ route('destroyMateri') }}" method="post">
                        @csrf
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <input type="hidden" name="hapusId" id="materiId" value="">
                        <input type="hidden" name="kelasMapelId" id="kelasMapelMateri" value="">
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Konfirmasi Hapus Tugas --}}
    <div class="modal fade" id="modalHapusTugas" tabindex="-1" aria-labelledby="modalHapusTugasLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalHapusTugasLabel">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menghapus Tugas ini?
                </div>
                <div class="modal-footer">
                    <form action="{{ route('destroyTugas') }}" method="post">
                        @csrf
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <input type="hidden" name="hapusId" id="tugasId" value="">
                        <input type="hidden" name="kelasMapelId" id="kelasMapelTugas" value="">
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Konfirmasi Hapus Ujian --}}
    <div class="modal fade" id="modalHapusUjian" tabindex="-1" aria-labelledby="modalHapusUjianLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalHapusUjianLabel">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menghapus Ujian ini?
                </div>
                <div class="modal-footer">
                    <form action="{{ route('destroyUjian') }}" method="post">
                        @csrf
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <input type="hidden" name="hapusId" id="ujianId" value="">
                        <input type="hidden" name="tipe" id="tipe" value="">
                        <input type="hidden" name="kelasMapelId" id="kelasMapelUjian" value="">
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    {{-- Script JavaScript --}}
    <script>
        function changeValueMateri(itemId) {
            console.log(itemId);
            const materiId = document.getElementById('materiId');
            const kelasMapelMateri = document.getElementById('kelasMapelMateri');
            materiId.setAttribute('value', itemId);
            kelasMapelMateri.setAttribute('value', "{{ $kelasMapel['id'] }}");
        }

        function changeValueTugas(itemId) {
            console.log(itemId);
            const tugasId = document.getElementById('tugasId');
            const kelasMapelTugas = document.getElementById('kelasMapelTugas');
            tugasId.setAttribute('value', itemId);
            kelasMapelTugas.setAttribute('value', "{{ $kelasMapel['id'] }}");
        }

        function changeValueUjian(itemId, tipe) {
            console.log(itemId);
            console.log(tipe);
            const ujianId = document.getElementById('ujianId');
            const tipeId = document.getElementById('tipe');
            const kelasMapelUjian = document.getElementById('kelasMapelUjian');
            ujianId.setAttribute('value', itemId);
            tipeId.setAttribute('value', tipe);
            kelasMapelUjian.setAttribute('value', "{{ $kelasMapel['id'] }}");
        }
    </script>
@endsection
