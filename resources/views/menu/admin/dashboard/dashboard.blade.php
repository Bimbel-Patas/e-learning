@extends('layout.template.mainTemplate')

@section('container')
    <h1>Dashboard</h1>
    <span class="small">Pemantauan Data</span>
    <hr>


    {{-- Konten Utama --}}
    <div class="bg-body-secondary rounded-4 mb-4">
        <div class="container col-xxl-8 px-4 py-5">
            <div class="row  align-items-center g-5 py-5">

                <div class="text-center">
                    <h3 class=" text-primary fw-bold d-block">Informasi Sistem</h3>
                </div>
                {{-- Chart Section --}}
                <div class="col-12 p-4 border border-primary rounded-4">
                    <div class="row">
                        {{-- Materi chart --}}
                        <div class="col-lg-6">
                            <div class="card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Jumlah Materi Created (dalam 7 Hari
                                        Terakhir)</h6>
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
                                        <canvas id="materiChart" style="display: block; width: 558px; height: 320px;"
                                            class="chartjs-render-monitor  w-100 "></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Tugas Chart --}}
                        <div class="col-lg-6">
                            <div class="card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Jumlah Tugas Created (dalam 7 Hari
                                        Terakhir)</h6>
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

                        {{-- Ujian Chart --}}
                        <div class="col-lg-12">
                            <div class="card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Jumlah Ujian Created (dalam 7 Hari
                                        Terakhir)</h6>
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
                                        <canvas id="ujianChart" style="display: block; width: 558px; height: 320px;"
                                            class="chartjs-render-monitor w-100 "></canvas>
                                    </div>
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
                            <span class="small d-block">Total data siswa</span>
                            <h1 class="display-3 text-primary fw-bold d-block">{{ $data['totalSiswa'] }}</h1>
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
                            <Strong class="fs-5 text-primary d-block">Siswa Terdaftar</Strong>
                            <span class="small d-block">Siswa yang memiliki akun</span>
                            <h1 class="display-3 text-primary fw-bold d-block">{{ $data['totalUserSiswa'] }}</h1>
                        </div>
                    </div>
                </div>
                {{-- Total mapel --}}
                <div class="col-lg-3  col-6">
                    <div class="p-4 border border-primary rounded-2">
                        <div class="text-center">
                            <img src="{{ url('/asset/img/kelas-icon.svg') }}" alt="" class="img-fluid w-50"
                                srcset="" width="100px" style="max-width: 100px;">
                            <br>
                            <Strong class="fs-5 text-primary d-block">Total Pengajar</Strong>
                            <span class="small d-block">User pengajar</span>
                            <h1 class="display-3 text-primary fw-bold d-block">{{ $data['totalPengajar'] }}</h1>
                        </div>
                    </div>
                </div>
                {{-- Total kelas --}}
                <div class="col-lg-3 col-6">
                    <div class="p-4 border border-primary rounded-2">
                        <div class="text-center">
                            <img src="{{ url('/asset/img/mapel-icon.svg') }}" alt="" class="img-fluid w-50"
                                srcset="" width="100px" style="max-width: 100px;">
                            <br>
                            <Strong class="fs-5 text-primary d-block">Total Kelas</Strong>
                            <span class="small d-block">Semua kelas</span>
                            <h1 class="display-3 text-primary fw-bold d-block">{{ $data['totalKelas'] }}</h1>
                        </div>
                    </div>
                </div>

                {{-- Total Mapel --}}
                <div class="col-lg-3 col-6">
                    <div class="p-4 border border-primary rounded-2">
                        <div class="text-center">
                            <img src="{{ url('/asset/img/mapel.svg') }}" alt="" class="img-fluid w-50"
                                srcset="" width="100px" style="max-width: 100px;">
                            <br>
                            <Strong class="fs-5 text-primary d-block">Total Mapel</Strong>
                            <span class="small d-block">Mapel yang terdaftar</span>
                            <h1 class="display-3 text-primary fw-bold d-block">{{ $data['totalMapel'] }}</h1>
                        </div>
                    </div>
                </div>

                {{-- Total Materi --}}
                <div class="col-lg-3 col-6">
                    <div class="p-4 border border-primary rounded-2">
                        <div class="text-center">
                            <img src="{{ url('/asset/img/materi.svg') }}" alt="" class="img-fluid w-50"
                                srcset="" width="100px" style="max-width: 100px;">
                            <br>
                            <Strong class="fs-5 text-primary d-block">Total Materi</Strong>
                            <span class="small d-block">Materi yang terdaftar</span>
                            <h1 class="display-3 text-primary fw-bold d-block">{{ $data['totalMateri'] }}</h1>
                        </div>
                    </div>
                </div>

                {{-- Total Tugas --}}
                <div class="col-lg-3 col-6">
                    <div class="p-4 border border-primary rounded-2">
                        <div class="text-center">
                            <img src="{{ url('/asset/img/tugas.svg') }}" alt="" class="img-fluid w-50"
                                srcset="" width="100px" style="max-width: 100px;">
                            <br>
                            <Strong class="fs-5 text-primary d-block">Total Tugas</Strong>
                            <span class="small d-block">Tugas yang terdaftar</span>
                            <h1 class="display-3 text-primary fw-bold d-block">{{ $data['totalTugas'] }}</h1>
                        </div>
                    </div>
                </div>

                {{-- Total Ujian --}}
                <div class="col-lg-3 col-6">
                    <div class="p-4 border border-primary rounded-2">
                        <div class="text-center">
                            <img src="{{ url('/asset/img/ujian.svg') }}" alt="" class="img-fluid w-50"
                                srcset="" width="100px" style="max-width: 100px;">
                            <br>
                            <Strong class="fs-5 text-primary d-block">Total Ujian</Strong>
                            <span class="small d-block">Ujian yang terdaftar</span>
                            <h1 class="display-3 text-primary fw-bold d-block">{{ $data['totalUjian'] }}</h1>
                        </div>
                    </div>
                </div>

            </div>


        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    {{-- Chart Materi --}}
    <script>
        var ctxMateri = document.getElementById('materiChart').getContext('2d');

        var data = <?php echo json_encode($data); ?>;
        var materiData = <?php echo json_encode(App\Models\Materi::where('created_at', '>=', now()->subWeek())->get()); ?>;

        var materiCount = [];
        var labelDates = [];

        for (var i = 0; i < 7; i++) {
            var date = moment().subtract(i, 'days').format('MMM-DD');
            labelDates.push(date);

            var count = materiData.filter(function(materi) {
                return moment(materi.created_at).format('MMM-DD') === date;
            }).length;

            materiCount.push(count);
        }

        var myChart = new Chart(ctxMateri, {
            type: 'line',
            data: {
                labels: labelDates.reverse(),
                datasets: [{
                    label: 'Materi dibuat',
                    data: materiCount.reverse(),
                    borderColor: 'rgba(0, 123, 255, 1)', // Menggunakan warna Bootstrap primary
                    borderWidth: 2
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
    // Chart Tugas
    <script>
        var ctxTugas = document.getElementById('tugasChart').getContext('2d');

        var data = <?php echo json_encode($data); ?>;
        var materiData = <?php echo json_encode(App\Models\Tugas::where('created_at', '>=', now()->subWeek())->get()); ?>;

        var materiCount = [];
        var labelDates = [];

        for (var i = 0; i < 7; i++) {
            var date = moment().subtract(i, 'days').format('MMM-DD');
            labelDates.push(date);

            var count = materiData.filter(function(materi) {
                return moment(materi.created_at).format('MMM-DD') === date;
            }).length;

            materiCount.push(count);
        }

        var myChart = new Chart(ctxTugas, {
            type: 'line',
            data: {
                labels: labelDates.reverse(),
                datasets: [{
                    label: 'Tugas dibuat',
                    data: materiCount.reverse(),
                    borderColor: 'rgba(0, 123, 255, 1)', // Menggunakan warna Bootstrap primary
                    borderWidth: 2
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
    // Chart ujian
    <script>
        var ctxUjian = document.getElementById('ujianChart').getContext('2d');

        var data = <?php echo json_encode($data); ?>;
        var materiData = <?php echo json_encode(App\Models\Ujian::where('created_at', '>=', now()->subWeek())->get()); ?>;

        var materiCount = [];
        var labelDates = [];

        for (var i = 0; i < 7; i++) {
            var date = moment().subtract(i, 'days').format('MMM-DD');
            labelDates.push(date);

            var count = materiData.filter(function(materi) {
                return moment(materi.created_at).format('MMM-DD') === date;
            }).length;

            materiCount.push(count);
        }

        var myChart = new Chart(ctxUjian, {
            type: 'line',
            data: {
                labels: labelDates.reverse(),
                datasets: [{
                    label: 'Tugas dibuat',
                    data: materiCount.reverse(),
                    borderColor: 'rgba(0, 123, 255, 1)', // Menggunakan warna Bootstrap primary
                    borderWidth: 2
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
