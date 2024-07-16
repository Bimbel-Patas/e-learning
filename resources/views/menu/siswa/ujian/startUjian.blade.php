@extends('layout.template.mainTemplate')

@section('container')
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Informasi Tugas --}}
    <div class="mb-4 p-4 bg-white rounded-4">
        <div class=" p-4">
            <h2 class="fw-bold mb-2 text-primary">{{ $ujian->name }}</h2>
            <hr>
            <div class="row">
                @php
                    $end_time = Carbon\Carbon::parse($userCommit->end_time);
                    $now = Carbon\Carbon::now();
                    if ($now > $end_time) {
                        $diffInSeconds = 0;
                    } else {
                        $diffInSeconds = $end_time->diffInSeconds($now);
                    }
                @endphp

                <div class="border p-3 fw-bold col-lg-3 col-12">
                    Deadline :
                    <span class="badge badge-primary p-2">
                        {{ \Carbon\Carbon::parse($userCommit->end_time)->format('h:i A') }}
                    </span>
                </div>

                <div class="col-12 border p-3 col-lg-3">
                    <span class="fw-bold">Time : </span>
                    {{ $ujian->time }} Menit
                </div>
                <div class="border p-3 fw-bold col-lg-3 col-12">
                    <div id="countdown">
                        Waktu :
                        <span class="badge badge-danger p-2">
                            <span id="minutes">{{ floor($diffInSeconds / 60) }}</span>
                            <span id="seconds">{{ $diffInSeconds % 60 }}</span>
                        </span>
                    </div>
                </div>

                <div class="col-12 border p-3 col-lg-3">
                    <span class="fw-bold">Jumlah Soal :</span>
                    {{ count($ujian->SoalUjianEssay) }}
                </div>
            </div>
        </div>
    </div>
    <hr>

    {{-- Main Section --}}
    <div class="row">
        {{-- Question Section --}}
        <div class="col-lg-8 col-12">
            <div class="bg-white p-4 rounded-2 row">
                {{-- Soal --}}
                <div class="border border-primary rounded-2 p-4 mb-4 col-12" id="soal-container">
                    <h1 class="text-primary fw-bold" id="soal-title">Soal 1</h1>
                    <hr>
                    <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Aliquid atque quos inventore nesciunt
                        voluptates ipsam molestias non quasi incidunt dolor!</p>
                </div>

                {{-- Jawaban --}}
                <div class="rounded-2 mb-4 col-12">
                    <h6 class="text-primary fw-bold">Jawaban</h6>
                    <textarea id="jawaban" cols="30" rows="10" class="form-control"></textarea>
                </div>

                {{-- Next and Prev --}}
                <div class="d-flex justify-content-between align-items-center col-12">
                    <button class="btn btn-primary" id="prevBtn" disabled>Previous</button>
                    <button class="btn btn-primary" id="nextBtn">Next</button>
                </div>
            </div>
        </div>

        {{-- Navigation --}}
        <div class="col-lg-4 col-12">
            <div class="bg-white p-4 rounded-2">
                <div class="border border-primary rounded-2 p-4">
                    <h5 class="text-primary fw-bold">Nomor Soal</h5>
                    <div class="border border-secondary p-4 rounded-2" id="nomorSoalContainer">
                        @foreach ($ujian->SoalUjianEssay as $index => $soal)
                            <button class="btn btn-outline-primary nomor-soal-btn"
                                data-soal="{{ $soal->id }}">{{ $index + 1 }}</button>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="mt-4">
                <button class="btn btn-outline-primary w-100" data-bs-toggle="modal" data-bs-target="#modalSelesai">Selesai
                    Mengerjakan</button>
            </div>
        </div>

    </div>

    {{-- Modal --}}
    <div class="modal fade" id="modalSelesai" tabindex="-1" aria-labelledby="modalSelesai" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteConfirmationModalLabel">Konfirmasi Selesai</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin mengakhiri ujian?
                </div>
                <div class="modal-footer">
                    <form action="{{ route('selesaiUjian') }}" method="post">
                        @csrf
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <input type="hidden" name="userCommit" id="userCommit" value="{{ encrypt($userCommit['id']) }}">
                        {{-- <input type="hidden" name="idMateri" id="idMateri" value="{{ $materi['id'] }}"> --}}
                        <button type="submit" class="btn btn-danger">Selesai</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Fungsi untuk mengatur countdown
        function startCountdown(seconds) {
            var displayMinutes = document.getElementById('minutes');
            var displaySeconds = document.getElementById('seconds');
            var remainingSeconds = seconds;

            var countdown = setInterval(function() {
                if (remainingSeconds <= 0) {
                    clearInterval(countdown);
                    displayMinutes.textContent = 'Waktu Habis';
                    displaySeconds.textContent = '';

                    // Nonaktifkan kotak input jawaban
                    document.getElementById('jawaban').disabled = true;
                } else {
                    var minutes = Math.floor(remainingSeconds / 60);
                    var seconds = remainingSeconds % 60;
                    displayMinutes.textContent = minutes + ' menit';
                    displaySeconds.textContent = seconds + ' detik';
                    remainingSeconds--;

                    if (remainingSeconds < 0) {
                        clearInterval(countdown);
                        displayMinutes.textContent = 'Waktu Habis';
                        displaySeconds.textContent = '';

                        // Nonaktifkan kotak input jawaban
                        document.getElementById('jawaban').disabled = true;
                    }
                }
            }, 1000); // 1 detik
        }


        // Memulai countdown dengan waktu dari PHP (dalam detik)
        startCountdown({{ $diffInSeconds }});

        var soalUjianEssay = @json($ujian->SoalUjianEssay);

        // Variabel untuk melacak nomor soal saat ini
        var currentSoal = 0;

        // Fungsi untuk menampilkan soal sesuai dengan nomor yang dipilih
        function tampilkanSoal(nomor) {
            if (nomor >= 1 && nomor <= soalUjianEssay.length) {
                currentSoal = nomor;
                var selectedSoal = soalUjianEssay[nomor - 1];
                // document.getElementById('soal-title').html = "Soal " + nomor;
                document.getElementById('jawaban').value = ""; // Reset jawaban
                // Ganti konten soal sesuai dengan data soal yang diambil
                document.getElementById('soal-container').innerHTML = `
                <h1 class="text-primary fw-bold">Soal ${nomor}</h1>
                <hr>
                <p>${selectedSoal.soal}</p>
            `;
            }
        }

        // Inisialisasi tampilan soal pertama
        tampilkanSoal(1);
        getJawaban();

        // Tambahkan event listener untuk tombol "Next"
        document.getElementById('nextBtn').addEventListener('click', function() {
            if (currentSoal < soalUjianEssay.length) {
                tampilkanSoal(currentSoal + 1);
                getJawaban();
                // Aktifkan tombol "Previous"
                document.getElementById('prevBtn').removeAttribute('disabled');
                console.log(currentSoal);
                console.log(soalUjianEssay.length - 1);
                // Jika pengguna sekarang di soal terakhir, nonaktifkan tombol "Next"
                if (currentSoal > soalUjianEssay.length - 1) {
                    document.getElementById('nextBtn').setAttribute('disabled', 'true');
                }
            }
            updateRaguRaguCheckbox(currentSoal - 1);
        });

        // Tambahkan event listener untuk tombol "Previous"
        document.getElementById('prevBtn').addEventListener('click', function() {
            tampilkanSoal(currentSoal - 1);
            getJawaban();
            if (currentSoal === 1) {
                // Nonaktifkan tombol "Previous" jika kembali ke soal pertama
                document.getElementById('nextBtn').removeAttribute('disabled');

                if (currentSoal <= soalUjianEssay.length - 1) {
                    document.getElementById('prevBtn').setAttribute('disabled', 'true');
                }
            }
            updateRaguRaguCheckbox(currentSoal - 1);
        });

        function updateRaguRaguCheckbox(currentSoalId) {
            var raguRaguCheckbox = document.getElementById('raguRaguCheckbox');
            raguRaguCheckbox.setAttribute('data-soal', currentSoalId);
        }

        // Tambahkan event listener untuk tombol nomor soal
        var nomorSoalButtons = document.querySelectorAll('.nomor-soal-btn');
        nomorSoalButtons.forEach(function(button, index) {
            button.addEventListener('click', function() {
                tampilkanSoal(index + 1);

                // Nonaktifkan tombol "Previous" jika memilih soal dari nomor pertama
                if (index === 0) {
                    document.getElementById('prevBtn').setAttribute('disabled', 'true');
                } else {
                    // Aktifkan tombol "Previous"
                    document.getElementById('prevBtn').removeAttribute('disabled');
                }

                // Periksa jika pengguna berada di ujung nomor soal, maka nonaktifkan tombol "Next"
                if (index === soalUjianEssay.length - 1) {
                    document.getElementById('nextBtn').setAttribute('disabled', 'true');
                } else {
                    // Aktifkan tombol "Next" jika bukan di ujung nomor soal
                    document.getElementById('nextBtn').removeAttribute('disabled');
                }

                // Periksa nomor soal saat ini dan mengambil jawaban dari database jika ada
                var currentSoalId = soalUjianEssay[index].id;
                getJawaban(currentSoalId);

                // Mengatur warna tombol nomor soal
                // setButtonColors(index);
            });
        });




        // Tambahkan token CSRF ke header permintaan
        function getJawaban() {
            var soalId = soalUjianEssay[currentSoal - 1].id;
            var data = {
                soal_id: soalId,
            };
            // Kirim permintaan AJAX untuk mengambil jawaban dari database
            $.ajax({
                type: 'GET', // Gunakan metode GET
                url: "{{ route('getJawaban') }}",
                data: data, // Sesuaikan dengan URL yang sesuai
                success: function(response) {
                    // Periksa jika jawaban ditemukan dalam respons
                    console.log(soalId);
                    if (response.jawaban) {
                        console.log(response);
                        document.getElementById('jawaban').value = response.jawaban;

                    } else {
                        // Jika tidak ada jawaban tersimpan, reset textarea
                        console.log('null');
                        document.getElementById('jawaban').value = '';
                    }
                },
                error: function(xhr, status, error) {
                    // Tangani kesalahan jika terjadi
                    console.error(error);
                }
            });
        }
        $(document).ready(function() {
            // Cek jawaban yang telah disimpan di Local Storage
            for (var i = 0; i < soalUjianEssay.length; i++) {
                var soalId = soalUjianEssay[i].id;
                var storedJawaban = localStorage.getItem('jawaban_' + soalId);

                if (storedJawaban) {
                    // Jika jawaban telah tersimpan, tandai tombol sebagai "btn-primary"
                    var nomorSoalButton = $('#nomorSoalContainer button[data-soal="' + soalId + '"]');
                    nomorSoalButton.addClass('btn-primary');
                    nomorSoalButton.removeClass('btn-outline-primary');
                }
            }

            // Tambahkan event listener untuk textarea jawaban
            $('#jawaban').on('input', function() {
                var jawaban = $(this).val();
                var soalId = soalUjianEssay[currentSoal - 1].id;

                // Simpan jawaban di Local Storage
                localStorage.setItem('jawaban_' + soalId, jawaban);

                // Tangai tombol nomor soal sebagai "btn-primary" jika ada jawaban
                var nomorSoalButton = $('#nomorSoalContainer button[data-soal="' + soalId + '"]');
                if (jawaban.trim() !== '') {
                    nomorSoalButton.addClass('btn-primary');
                    nomorSoalButton.removeClass('btn-outline-primary');
                } else {
                    nomorSoalButton.addClass('btn-outline-primary');
                    nomorSoalButton.removeClass('btn-primary');
                }

                var data = {
                    soal_id: soalId,
                    jawaban: jawaban,
                };

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    }
                });
                $.ajax({
                    type: 'POST',
                    url: '/simpan-jawaban', // Ganti dengan URL yang sesuai
                    data: data,
                    success: function(response) {
                        // Tangani respon dari server
                        console.log(response.message);

                        // Tandai tombol nomor soal sebagai "btn-primary" jika ada jawaban
                        var nomorSoalButton = $('#nomorSoalContainer button[data-soal="' +
                            soalId + '"]');
                        if (jawaban.trim() !== '') {
                            nomorSoalButton.addClass('btn-primary');
                            nomorSoalButton.removeClass('btn-outline-primary');
                        } else {
                            nomorSoalButton.addClass('btn-outline-primary');
                            nomorSoalButton.removeClass('btn-primary');
                        }

                        // Di sini Anda dapat menambahkan notifikasi atau tindakan lain sesuai kebutuhan.
                    },
                    error: function(xhr, status, error) {
                        // Tangani kesalahan jika terjadi
                        console.error(error);
                    }
                });
                // Di sini Anda dapat menambahkan notifikasi atau tindakan lain sesuai kebutuhan.
            });
        });
    </script>
@endsection
