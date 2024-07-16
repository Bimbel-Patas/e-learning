@extends('layout.template.mainTemplate')

@section('container')

    {{-- Navigasi Breadcrumb --}}
    <div class="col-12 ps-4 pe-4 mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-white">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item">
                    <a
                        href="{{ route('viewKelasMapel', ['mapel' => $mapel['id'], 'token' => encrypt($kelasId), 'mapel_id' => $mapel['id']]) }}">
                        {{ $mapel['name'] }}
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('viewPilihTipeUjian', ['token' => encrypt($kelasId), 'mapelId' => $mapel['id']]) }}">
                        Tipe Ujian
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Tambah Ujian</li>
            </ol>
        </nav>
    </div>

    {{-- Judul Halaman --}}
    <div class="ps-4 pe-4 mt-4  pt-4">
        <h2 class="display-6 fw-bold">
            <a href="{{ route('viewPilihTipeUjian', ['token' => encrypt($kelasId), 'mapelId' => $mapel['id']]) }}">
                <button type="button" class="btn btn-outline-secondary rounded-circle">
                    <i class="fa-solid fa-arrow-left"></i>
                </button>
            </a>
            Tambah Ujian
        </h2>

        {{-- Breadcrumb --}}
        <nav style="" aria-label="breadcrumb">
            <ol class="breadcrumb bg-light">
                <li class="breadcrumb-item text-info" aria-current="page">Step 1</li>
                <li class="breadcrumb-item text-info">Step 2</li>
            </ol>
        </nav>
    </div>

    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Formulir Tambah Ujian --}}
    <div class="">
        <div class="row p-4">
            <div class="col-12 col-lg-12">
                <form action="{{ route('createUjian') }}" method="POST">
                    @csrf
                    <h4 class="fw-bold text-primary"><i class="fa-solid fa-pen"></i> Data Ujian</h4>
                    <div class=" row ">
                        {{-- Section Left --}}
                        <div class="p-4 col-lg-12 col-12  bg-white rounded-2">

                            {{-- Status Open / Close --}}
                            <div class="mb-3 row">
                                <div class="col-8 col-lg-4">
                                    <label for="opened" class="form-label d-block">Aktif<span class="small">(apakah
                                            sudah bisa diakses?)</span></label>
                                </div>
                                <div class="col-4 col-lg form-check form-switch">
                                    <input class="form-check-input" name="opened" type="checkbox" role="switch"
                                        id="opened" checked>
                                </div>
                            </div>

                            {{-- Nama Ujian --}}
                            <div class="mb-3">
                                <label for="name" class="form-label">Judul Ujian <span
                                        class="text-danger">*</span></label>
                                <input type="hidden" name="kelasId" value="{{ encrypt($kelasId) }}" readonly>
                                <input type="hidden" name="mapelId" value="{{ $mapel['id'] }}" readonly>
                                @if (session()->has('info'))
                                    <input type="text" class="form-control" id="inputName" name="name"
                                        placeholder="Inputkan judul Tugas..." value="{{ old('name', session('info')[0]) }}"
                                        required>
                                @else
                                    <input type="text" class="form-control" id="inputName" name="name"
                                        placeholder="Inputkan judul Tugas..." value="{{ old('name') }}" required>
                                @endif
                                @error('name')
                                    <div class="text-danger small">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- Reserved grid --}}
                            <div class="row mb-3">
                                <div class="col-12 col-lg-12">
                                    <label class="form-label">Tipe Ujian <span class="text-danger">*</span></label>
                                    <input type="text" name="tipe" class="form-control"
                                        value="@if ($tipe == 'multiple') multiple @elseif($tipe == 'essay') essay @else kecermatan @endif"
                                        readonly>
                                </div>
                            </div>

                            {{-- Waktu Ujian (Timer) --}}
                            <div class="mb-3">
                                <div class="">
                                    <label for="time" class="form-label">Waktu Ujian <span class="small">
                                            @if ($tipe == 'kecermatan')
                                                Per soal (Detik)
                                            @else
                                                dalam
                                                menit
                                            @endif
                                            <span class="text-danger">*</span>
                                        </span></label>
                                    @if (session()->has('info'))
                                        <input type="number" class="form-control" id="inputTime" name="time"
                                            placeholder="0" value="{{ old('time', session('info')[1]) }}" required>
                                    @else
                                        <input type="number" class="form-control" id="inputTime" name="time"
                                            placeholder="0" value="{{ old('time') }}" required>
                                    @endif
                                    @error('time')
                                        <div class="text-danger small">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Due Date Picker --}}
                            <div class="mb-3">
                                <label for="due" class="form-label">Due Date <span
                                        class="text-danger">*</span></label>
                                @if (session()->has('info'))
                                    <input class="form-control" id="inputDue" name="due" autocomplete="off"
                                        placeholder="Pilih tanggal jatuh tempo..." required
                                        value="{{ old('due', session('info')[2]) }}">
                                @else
                                    <input class="form-control" id="inputDue" name="due" autocomplete="off"
                                        placeholder="Pilih tanggal jatuh tempo..." required value="{{ old('due') }}">
                                @endif
                                @error('due')
                                    <div class="text-danger small">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                        </div>

                        <div class="mt-4">
                            <h4 class="fw-bold text-primary"><i class="fa-solid fa-pen"></i>
                                @if ($tipe == 'multiple')
                                    Data Soal Pilihan Ganda
                                    <button class="btn btn-success animate-btn-small" data-bs-toggle="modal"
                                        data-bs-target="#importModal" type="button"><i
                                            class="fa-solid fa-file-import"></i>
                                        Import<span class="small">(.xls, .xlsx)</span>
                                    </button>
                                @elseif($tipe == 'essay')
                                    Data Soal Essay
                                    <button class="btn btn-success animate-btn-small" data-bs-toggle="modal"
                                        data-bs-target="#importModal" type="button"><i
                                            class="fa-solid fa-file-import"></i>
                                        Import<span class="small">(.xls, .xlsx)</span>
                                    </button>
                                @else
                                    Data Kolom Kecermatan
                                @endif

                            </h4>
                            {{-- Container untuk Pertanyaan-Pertanyaan --}}
                            <div class="mt-4 bg-white p-4" id="containerPertanyaan">
                                @if (session()->has('soalEssay'))
                                    @foreach (session('soalEssay') as $key)
                                        <div class="bg-white border border-dark-subtle rounded-2 p-4 mt-4 pertanyaan">
                                            <div class="">
                                                <h3>Soal <span class="badge badge-primary">{{ $loop->iteration }}</span>
                                                    <button type="button"
                                                        class="btn btn-outline-danger btnKurangi">X</button>
                                                </h3>
                                                <div class="mb-3">
                                                    <label for="pertanyaan${nomorPertanyaan}"
                                                        class="form-label">Pertanyaan <span
                                                            class="text-danger">*</span></label>
                                                    <textarea class="form-control" id="pertanyaan${nomorPertanyaan}" name="pertanyaan[]" rows="3" required>{{ $key[1] }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @elseif (session()->has('soalMultiple'))
                                    @foreach (session('soalMultiple') as $key)
                                        <div class="bg-white border border-dark-subtle rounded-2 p-4 mt-4 pertanyaan">
                                            <div class="">
                                                <h3>Soal <span class="badge badge-primary">{{ $loop->iteration }}</span>
                                                    <button type="button"
                                                        class="btn btn-outline-danger btnKurangi">X</button>
                                                </h3>
                                                <div class="mb-3 row">
                                                    <div class="col-lg-7 col-12">
                                                        <label for="pertanyaan${nomorPertanyaan}"
                                                            class="form-label">Pertanyaan <span
                                                                class="text-danger">*</span></label>
                                                        <textarea class="form-control" id="pertanyaan${nomorPertanyaan}" name="pertanyaan[]" rows="3" required>{{ $key[1] }}</textarea>
                                                    </div>
                                                    <div class="col-lg-5 col-12 row">
                                                        <div class="col-5 m-1">
                                                            <label for="pertanyaan${nomorPertanyaan}" class="form-label">A
                                                                <span class="text-danger">*</span></label>
                                                            <input type="text" class="form-control" name="a[]"
                                                                required id="" value="{{ $key[2] }}">
                                                        </div>
                                                        <div class="col-5 m-1">
                                                            <label for="pertanyaan${nomorPertanyaan}" class="form-label">B
                                                                <span class="text-danger">*</span></label>
                                                            <input type="text" class="form-control" name="b[]"
                                                                required id="" value="{{ $key[3] }}">
                                                        </div>
                                                        <div class="col-5 m-1">
                                                            <label for="pertanyaan${nomorPertanyaan}" class="form-label">C
                                                                <span class="text-danger">*</span>
                                                            </label>
                                                            <input type="text" class="form-control" name="c[]"
                                                                required id="" value="{{ $key[4] }}">
                                                        </div>
                                                        <div class="col-5 m-1">
                                                            <label for="pertanyaan${nomorPertanyaan}" class="form-label">D
                                                            </label>
                                                            <input type="text" class="form-control" name="d[]"
                                                                id="" value="{{ $key[5] }}">
                                                        </div>
                                                        <div class="col-5 m-1">
                                                            <label for="pertanyaan${nomorPertanyaan}" class="form-label">E
                                                            </label>
                                                            <input type="text" class="form-control" name="e[]"
                                                                id="" value="{{ $key[6] }}">
                                                        </div>
                                                        <div class="col-5 m-1">
                                                            <label for="pertanyaan${nomorPertanyaan}"
                                                                class="form-label text-primary fw-bold">Jawaban</label>
                                                            <select name="jawaban[]" class="form-select" id="">
                                                                <option value="a"
                                                                    @if ($key[7] == 'a') selected @endif>A
                                                                </option>
                                                                <option value="b"
                                                                    @if ($key[7] == 'b') selected @endif>B
                                                                </option>
                                                                <option value="c"
                                                                    @if ($key[7] == 'c') selected @endif>C
                                                                </option>
                                                                <option value="d"
                                                                    @if ($key[7] == 'd') selected @endif>D
                                                                </option>
                                                                <option value="e"
                                                                    @if ($key[7] == 'e') selected @endif>E
                                                                </option>
                                                            </select>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>

                            {{-- Tombol Tambah Pertanyaan --}}
                            <div class="mt-3 mb-4">
                                <button type="button" class="btn btn-outline-success w-100 btn-lg"
                                    id="btnTambahPertanyaan">Tambah
                                    Pertanyaan</button>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-lg btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
    </div>

    <script src="https://cdn.tiny.cloud/1/owul23wv0iajhyb6jcltjhj0yufndfa0srzpliww43lp6n5d/tinymce/6/tinymce.min.js"
        referrerpolicy="origin"></script>;
    <script src="{{ url('/asset/js/rich-text-editor.js') }}"></script>

    {{-- Modal Import --}}
    <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importModalLabel">Import Excel<span class="small">(.xls, .xlsx)</span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div>
                        <p class="text-left">
                            File yang tidak mengikuti
                            ketentuan
                            format akan
                            menyebabkan Error pada Perintah Import.
                        </p>
                    </div>
                    <div class="mt-2 col-12 bg-body-secondary rounded-2 p-4">
                        <div class="mb-4">
                            @if ($tipe == 'essay')
                                <a href="{{ route('contohEssay') }}"><button type="button" class="btn btn-warning"><i
                                            class="fa-solid fa-download"></i> Download
                                        Contoh<span class="small">(.xls)</span></button></a>
                            @elseif ($tipe == 'multiple' || $tipe == 'kecermatan')
                                <a href="{{ route('contohMultiple') }}"><button type="button"
                                        class="btn btn-warning"><i class="fa-solid fa-download"></i> Download
                                        Contoh<span class="small">(.xls)</span></button></a>
                            @endif
                        </div>
                        <div>
                            <form method="POST" action="{{ route('importSoalUjian') }}" enctype="multipart/form-data">
                                @csrf
                                <label for="file">Upload File<span class="small">(.xls, .xlsx)</span></label>
                                <input type="file" name="file" accept=".xlsx, .xls" required>
                                <input type="hidden" name="tipe" value="{{ $tipe }}">
                                <input type="hidden" name="name" id="importName">
                                <input type="hidden" name="time" id="importTime">
                                <input type="hidden" name="due" id="importDue">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-upload"></i> Import
                        Data</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {

            // Aktifkan date picker dengan format tanggal dan jam
            $(function() {
                $('#inputDue').datetimepicker({
                    format: 'Y-m-d H:i',
                    locale: 'id',
                });
            });

            // Import Hidden Input Builder
            $('#inputName').on('change', function() {
                $('#importName').val($('#inputName').val());
            });
            $('#inputTime').on('change', function() {
                $('#importTime').val($('#inputTime').val());
            });
            $('#inputDue').on('change', function() {
                $('#importDue').val($('#inputDue').val());
            });

            // Tombol Tambah Pertanyaan diklik
            $('#btnTambahPertanyaan').click(function() {
                // Mengambil jumlah pertanyaan saat ini
                const jumlahPertanyaan = $('.pertanyaan').length;

                // Membuat nomor pertanyaan yang akan digunakan
                var nomorPertanyaan = jumlahPertanyaan + 1;
                @if ($tipe == 'essay')
                    // Buat formulir pertanyaan baru Essay
                    const formulirPertanyaanBaru = `
                 <div class="bg-white border border-dark-subtle rounded-2 p-4 mt-4 pertanyaan">
                  <div class="">
                                <h3>Soal <span class="badge badge-primary">${nomorPertanyaan}</span>
                                      <button type="button" class="btn btn-outline-danger btnKurangi">X</button>
                                </h3>
                                <div class="mb-3">
                                    <label for="pertanyaan${nomorPertanyaan}" class="form-label">Pertanyaan <span class="text-danger">*</span></label>
                                    <textarea class="tinymce form-control" id="pertanyaan${nomorPertanyaan}" name="pertanyaan[]" rows="3" ></textarea>
                                </div>
                            </div>
                            </div>
            `;
                @elseif ($tipe == 'multiple')
                    // Buat formulir pertanyaan baru Multiple
                    const formulirPertanyaanBaru = `
                 <div class="bg-white border border-dark-subtle rounded-2 p-4 mt-4 pertanyaan">
                                    <div class="">
                                        <h3>Soal <span class="badge badge-primary">${nomorPertanyaan}</span>
                                            <button type="button" class="btn btn-outline-danger btnKurangi">X</button>
                                        </h3>
                                        <div class="mb-3 row">
                                            <div class="col-lg-7 col-12">
                                                <label for="pertanyaan${nomorPertanyaan}"
                                                    class="form-label">Pertanyaan <span class="text-danger">*</span></label>
                                                <textarea class="tinymce form-control" id="pertanyaan${nomorPertanyaan}" name="pertanyaan[]" rows="3" ></textarea>
                                            </div>
                                            <div class="col-lg-5 col-12 row">
                                                <div class="col-5 m-1">
                                                    <label for="pertanyaan${nomorPertanyaan}" class="form-label">A
                                                          <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" name="a[]" required
                                                        id="">
                                                </div>
                                                <div class="col-5 m-1">
                                                    <label for="pertanyaan${nomorPertanyaan}" class="form-label">B
                                                          <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" name="b[]" required
                                                        id="">
                                                </div>
                                                <div class="col-5 m-1">
                                                    <label for="pertanyaan${nomorPertanyaan}" class="form-label">C
                                                        <span class="text-danger">*</span>
                                                        </label>
                                                    <input type="text" class="form-control" name="c[]" required
                                                        id="">
                                                </div>
                                                <div class="col-5 m-1">
                                                    <label for="pertanyaan${nomorPertanyaan}" class="form-label">D</label>
                                                    <input type="text" class="form-control" name="d[]"
                                                        id="">
                                                </div>
                                                <div class="col-5 m-1">
                                                    <label for="pertanyaan${nomorPertanyaan}" class="form-label">E</label>
                                                    <input type="text" class="form-control" name="e[]"
                                                        id="">
                                                </div>
                                                <div class="col-5 m-1">
                                                    <label for="pertanyaan${nomorPertanyaan}"
                                                        class="form-label text-primary fw-bold">Jawaban</label>
                                                    <select name="jawaban[]" class="form-select" id="">
                                                        <option value="a">A</option>
                                                        <option value="b">B</option>
                                                        <option value="c">C</option>
                                                        <option value="d">D</option>
                                                        <option value="e">E</option>
                                                    </select>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
            `;
                @elseif ($tipe == 'kecermatan')
                    // Buat formulir pertanyaan baru Multiple
                    const formulirPertanyaanBaru = `
                 <div class="bg-white border border-dark-subtle rounded-2 p-4 mt-4 pertanyaan">
                                    <div class="">
                                        <h3>Soal <span class="badge badge-primary">${nomorPertanyaan}</span>
                                            <button type="button" class="btn btn-outline-danger btnKurangi">X</button>
                                        </h3>
                                        <div class="mb-3 row">

                                                <div class="col-5 m-1">
                                                    <label for="pertanyaan${nomorPertanyaan}" class="form-label">A
                                                          <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" name="a[]" required
                                                        id="">
                                                </div>
                                                <div class="col-5 m-1">
                                                    <label for="pertanyaan${nomorPertanyaan}" class="form-label">B
                                                          <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" name="b[]" required
                                                        id="">
                                                </div>
                                                <div class="col-5 m-1">
                                                    <label for="pertanyaan${nomorPertanyaan}" class="form-label">C
                                                        <span class="text-danger">*</span>
                                                        </label>
                                                    <input type="text" class="form-control" name="c[]" required
                                                        id="">
                                                </div>
                                                <div class="col-5 m-1">
                                                    <label for="pertanyaan${nomorPertanyaan}" class="form-label">D <span class="text-secondary small">(opsi)</span></label>
                                                    <input type="text" class="form-control" name="d[]"
                                                        id="">
                                                </div>
                                                <div class="col-5 m-1">
                                                    <label for="pertanyaan${nomorPertanyaan}" class="form-label">E <span class="text-secondary small">(opsi)</span></label>
                                                    <input type="text" class="form-control" name="e[]"
                                                        id="">
                                                </div>
                                                <div class="col-5 m-1">
                                                    <label for="jumlah${nomorPertanyaan}" class="form-label">Jumlah Soal<span class="text-secondary small"></span></label>
                                                    <input type="number" value="" required class="form-control" required name="jumlahSoal[]"
                                                        id="">
                                                </div>
                                        </div>
                                    </div>
                                </div>
            `;
                @endif


                // Tambahkan formulir pertanyaan baru ke dalam container
                $('#containerPertanyaan').append(formulirPertanyaanBaru);

                tinymce.init({
                    selector: ".tinymce",
                    plugins: "image link lists media",
                    toolbar: "undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | align lineheight | tinycomments | checklist numlist bullist indent outdent | emoticons charmap | removeformat",
                    menubar: false,
                    paste_data_images: false,
                    statusbar: false,

                    images_upload_handler: function(blobInfo, success, failure) {
                        // Fungsi penanganan unggah gambar, dapat diisi sesuai kebutuhan.
                        // Di sini, kami mengembalikan false untuk menonaktifkan unggah gambar.
                        return false;
                    },
                    ai_request: (request, respondWith) =>
                        respondWith.string(() =>
                            Promise.reject("See docs to implement AI Assistant")
                        ),
                });

                // Aktifkan tombol Kurangi pada pertanyaan sebelumnya (jika ada)
                $('.pertanyaan:last').prev().find('.btnKurangi').show();
            });


            // Tombol Kurangi diklik
            $('#containerPertanyaan').on('click', '.btnKurangi', function() {
                // Hapus formulir pertanyaan yang terkait
                $(this).closest('.pertanyaan').remove();

                // Update nomor pertanyaan pada pertanyaan yang tersisa
                $('.pertanyaan').each(function(index) {
                    // Menggunakan $(this) untuk merujuk pada elemen pertanyaan saat ini
                    const nomorPertanyaan = index + 1;
                    $(this).find('h3 span.badge').text(nomorPertanyaan);
                });


            });
        });
    </script>
@endsection
