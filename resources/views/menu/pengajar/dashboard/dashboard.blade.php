@extends('layout.template.mainTemplate')

@section('container')
    {{-- Cek peran pengguna --}}
    @if (Auth()->user()->roles_id == 1)
        @include('menu.admin.adminHelper')
    @endif

    {{-- Navigasi Breadcrumb --}}
    <div class="col-12 ps-4 pe-4 mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-white">
                <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
            </ol>
        </nav>
    </div>

    {{-- Judul Halaman --}}
    <div class="ps-4 pe-4">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="display-6 fw-bold ">Dashboard</h1>
        </div>
    </div>

    {{-- Konten Utama --}}
    <div class="bg-body-secondary rounded-4 mb-4">
        <div class="container col-xxl-8 px-4 py-5">

            <div class="row  align-items-center g-5 py-5">

                <div class="text-center">
                    <h3 class=" text-primary fw-bold d-block">Informasi Pengajar</h3>
                    <span class="small">({{ Auth()->User()->name }})</span>
                </div>

                {{-- Chart Section --}}
                <div class="col-12 row mt-4">
                    {{-- Tugas Chart --}}
                    <div class="col-lg-12">
                        <div class="card shadow mb-4">
                            <!-- Card Header - Dropdown -->
                            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                <h6 class="m-0 font-weight-bold text-primary">Materi, Tugas, Ujian Created (Dalam 7 hari
                                    terakhir)</h6>
                            </div>
                            <!-- Card Body -->
                            <div class="card-body">
                                <div class="chart-area">
                                    <div class="chartjs-size-monitor">
                                        <div class="chartjs-size-monitor-expand">
                                            <div class=""></div>
                                        </div>
                                        <div class="chartjs-size-monitor-shrink">
                                            <div class=""></div>
                                        </div>
                                    </div>
                                    <canvas id="tugasChart" style="display: block; width: 558px; height: 320px;"
                                        class="chartjs-render-monitor  w-100 "></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Total Siswa --}}
                <div class="col-lg-3 col-6">
                    <div class="p-4 border border-primary rounded-2">
                        <div class="text-center">
                            <img src="{{ url('/asset/img/student-icon.svg') }}" alt="" class="img-fluid w-50"
                                srcset="" width="100px" style="max-width: 100px;">
                            <br>
                            <Strong class="fs-5 text-primary d-block">Total Siswa</Strong>
                            <h1 class="display-3 text-primary fw-bold d-block">{{ $totalSiswa }}</h1>
                        </div>
                    </div>
                </div>
                {{-- Total Siswa Unik --}}
                <div class="col-lg-3  col-6">
                    <div class="p-4 border border-primary rounded-2">
                        <div class="text-center">
                            <img src="{{ url('/asset/img/unique-user.svg') }}" alt="" class="img-fluid w-50"
                                srcset="" width="100px" style="max-width: 100px;">
                            <br>
                            <Strong class="fs-5 text-primary d-block">Siswa Unique</Strong>
                            <h1 class="display-3 text-primary fw-bold d-block">{{ $totalSiswaUnique }}</h1>
                        </div>
                    </div>
                </div>
                {{-- Total mapel --}}
                <div class="col-lg-3  col-6">
                    <div class="p-4 border border-primary rounded-2">
                        <div class="text-center">
                            <img src="{{ url('/asset/img/mapel-icon.svg') }}" alt="" class="img-fluid w-50"
                                srcset="" width="100px" style="max-width: 100px;">
                            <br>
                            <Strong class="fs-5 text-primary d-block">Total Mapel</Strong>
                            <h1 class="display-3 text-primary fw-bold d-block">{{ count($mapelKelas) }}</h1>
                        </div>
                    </div>
                </div>
                {{-- Total kelas --}}
                <div class="col-lg-3 col-6">
                    <div class="p-4 border border-primary rounded-2">
                        <div class="text-center">
                            <img src="{{ url('/asset/img/kelas-icon.svg') }}" alt="" class="img-fluid w-50"
                                srcset="" width="100px" style="max-width: 100px;">
                            <br>
                            <Strong class="fs-5 text-primary d-block">Total Kelas</Strong>
                            <h1 class="display-3 text-primary fw-bold d-block">{{ $countKelas }}</h1>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Daftar Mapel --}}
    <div id="container-mapel mb-4">
        <div class="row mb-4">
            {{-- Bagian Kiri --}}
            <div class="col-xl-4 col-md-5 col-12 d-none d-sm-none d-lg-none d-xl-block">
                <div class="bg-white  rounded-4 p-4">
                    {{-- Gambar Profil Pengguna --}}
                    <div class="row">
                        <div class="col-12 mb-2">
                            <div class="text-center">
                                @if (Auth()->User()->gambar == null)
                                    <img src="/asset/icons/profile-women.svg"
                                        class="image-previewer image-class rounded-circle" width="250" alt="">
                                @else
                                    <img src="{{ asset('storage/user-images/' . Auth()->User()->gambar) }}"
                                        alt="placeholder" class="image-class rounded-circle h-50 w-50" loading="lazy">
                                @endif
                            </div>
                        </div>
                        <div class="col-12 text-center mt-2 row">
                            <div class="col-12">
                                <h4 class="fs-5">{{ Auth()->User()->name }}</h4>
                            </div>
                            <div class="col-12 mb-2">
                                <span class="my-1 badge badge-success p-2 ">Pengajar</span>
                                @if ($countKelas)
                                    <span class="my-1 badge badge-light p-2 ">{{ $countKelas }} Kelas</span>
                                @else
                                    <span class="my-1 badge badge-light p-2 ">0 Kelas</span>
                                @endif

                                @if ($mapelKelas)
                                    <span class="my-1 badge badge-light p-2 ">{{ count($mapelKelas) }} Mapel</span>
                                @else
                                    <span class="my-1 badge badge-light p-2 ">0 Mapel</span>
                                @endif
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            {{-- Bagian Kanan --}}
            <div class="col-xl-8 col-md-12 col-12 col-sm-12">
                @foreach ($mapelKelas as $mapelKelasItem)
                    <div class="row">
                        <div class="card mb-3 col-12 col-sm-12 col-md-12 mx-2">
                            <div class="row g-0">
                                <div class="col-md-4">
                                    {{-- Gambar Mapel --}}
                                    @if ($mapelKelasItem['mapel']->gambar != null)
                                        <div class="card-img-full h-100"
                                            style="background-image: url('{{ asset('storage/mapel/' . $mapelKelasItem['mapel']->gambar) }}'); background-size: cover;">
                                        </div>
                                    @else
                                        <div class="card-img-full h-100"
                                            style="background-image: url('{{ url('/asset/img/placeholder-3.jpg') }}'); background-size: cover;">
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-8">
                                    <div class="card-body">
                                        <h5 class="card-title fw-bold">{{ $mapelKelasItem['mapel']->name }}</h5>
                                        <p class="card-text">
                                            {{ Str::substr($mapelKelasItem['mapel']->deskripsi, 0, 100) }}
                                        </p>
                                        {{-- Daftar Kelas yang Terkait dengan Mata Pelajaran --}}
                                        <div class="row">
                                            @if ($mapelKelasItem['kelas'])
                                                @foreach ($mapelKelasItem['kelas'] as $kelas)
                                                    <div class="col-12">
                                                        <a href="{{ route('viewKelasMapel', ['mapel' => $mapelKelasItem['mapel']->id, 'token' => encrypt($kelas['id']), 'mapel_id' => $mapelKelasItem['mapel']]) }}"
                                                            class="animate-btn-small">
                                                            <span class="my-1 badge badge-primary p-3">
                                                                {{ $kelas->name }}
                                                            </span>
                                                        </a>
                                                        <span class="my-1 badge badge-light p-3 ">
                                                            Materi</span>
                                                        <span class="my-1 badge badge-light p-3 ">
                                                            Tugas</span>
                                                        <span class="my-1 badge badge-light p-3 ">
                                                            Ujian
                                                        </span>
                                                    </div>
                                                @endforeach
                                            @else
                                                <p>Tidak ada data kelas yang tersedia.</p>
                                            @endif

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script>
        var ctxTugas = document.getElementById('tugasChart').getContext('2d');

        var now = new Date();
        now.setHours(now.getHours() + 7); // Menambahkan 7 jam ke waktu saat ini

        var tugasData = <?php echo json_encode(
            App\Models\Tugas::where('created_at', '>=', now()->subDays(7))
                ->whereIn('kelas_mapel_id', $kelasMapelId)
                ->get(),
        ); ?>;
        var materiData = <?php echo json_encode(
            App\Models\Materi::where('created_at', '>=', now()->subDays(7))
                ->whereIn('kelas_mapel_id', $kelasMapelId)
                ->get(),
        ); ?>;
        var ujianData = <?php echo json_encode(
            App\Models\Ujian::where('created_at', '>=', now()->subDays(7))
                ->whereIn('kelas_mapel_id', $kelasMapelId)
                ->get(),
        ); ?>;

        var tugasCount = [];
        var materiCount = [];
        var ujianCount = [];
        var labelDatesTugas = [];

        for (var i = 0; i < 7; i++) {
            var date = moment().subtract(i, 'days').format('YYYY-MM-DD');
            labelDatesTugas.push(date);

            var tugasCountPerDay = tugasData.filter(function(tugas) {
                var tugasDate = moment(tugas.created_at).format('YYYY-MM-DD');
                return tugasDate === date;
            }).length;
            tugasCount.push(tugasCountPerDay);

            var materiCountPerDay = materiData.filter(function(materi) {
                var materiDate = moment(materi.created_at).format('YYYY-MM-DD');
                return materiDate === date;
            }).length;
            materiCount.push(materiCountPerDay);

            var ujianCountPerDay = ujianData.filter(function(ujian) {
                var ujianDate = moment(ujian.created_at).format('YYYY-MM-DD');
                return ujianDate === date;
            }).length;
            ujianCount.push(ujianCountPerDay);
        }

        var tugasChart = new Chart(ctxTugas, {
            type: 'line',
            data: {
                labels: labelDatesTugas.reverse(),
                datasets: [{
                    label: 'Tugas',
                    data: tugasCount.reverse(),
                    borderColor: 'rgba(0, 129, 149, 1)', // Warna garis Tugas (misalnya Toska)
                    borderWidth: 2,
                    pointStyle: 'rectRot', // Menggunakan bentuk kotak pada titik data
                    pointRadius: 6, // Ukuran titik data
                    pointBorderColor: 'rgba(0, 129, 149, 1)', // Warna pinggiran kotak
                    pointBackgroundColor: 'rgba(0, 129, 149, 1)', // Warna isi kotak
                }, {
                    label: 'Materi',
                    data: materiCount.reverse(),
                    borderColor: 'rgba(0, 255, 242, 0.68)', // Warna garis Materi (misalnya Cyan)
                    borderWidth: 2,
                    pointStyle: 'rectRot',
                    pointRadius: 6,
                    pointBorderColor: 'rgba(0, 255, 242, 0.68)',
                    pointBackgroundColor: 'rgba(0, 255, 242, 0.68)',
                }, {
                    label: 'Ujian',
                    data: ujianCount.reverse(),
                    borderColor: 'rgba(0, 0, 255, 0.68)', // Warna garis Ujian (misalnya ungu)
                    borderWidth: 2,
                    pointStyle: 'rectRot',
                    pointRadius: 6,
                    pointBorderColor: 'rgba(0, 0, 255, 0.68)',
                    pointBackgroundColor: 'rgba(0, 0, 255, 0.68)',
                }]
            },
            options: {
                animations: {
                    tension: {
                        duration: 1000,
                        easing: 'linear',
                        from: 1,
                        to: 0.5,
                        loop: false
                    }
                },
                scales: {
                    y: { // defining min and max so hiding the dataset does not change scale range
                        min: 0,
                        max: 10,
                    }
                }
            }
        });
    </script>
@endsection
