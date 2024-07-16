@extends('layout.template.mainTemplate')

@section('container')
    @if (Auth()->user()->roles_id == 1)
        @include('menu.admin.adminHelper')
    @endif

    <!-- Breadcrumb -->
    <div class="col-12 ps-4 pe-4 mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-white">
                <li class="breadcrumb-item active" aria-current="page">Data Kelas /</li>
            </ol>
        </nav>
    </div>

    <div class="ps-4 pe-4">
        <!-- Header dan Tombol Aksi -->
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="fw-bold display-6">Data Kelas
                <a href="{{ route('viewTambahKelas') }}"><button class="btn btn-primary animate-btn-small">+
                        Tambah</button>
                </a>
                <a href="{{ route('exportKelas') }}"><button class="btn btn-success animate-btn-small"><i
                            class="fa-solid fa-file-export"></i>
                        Export<span class="small">(.xls)</span></button></a>
                <button class="btn btn-success animate-btn-small" data-bs-toggle="modal" data-bs-target="#importModal"
                    type="button"><i class="fa-solid fa-file-import"></i> Import<span class="small">(.xls, .xlsx)</span>
                </button>
            </h1>
        </div>
    </div>

    @if ($mapelCount == 0)
        <!-- Peringatan jika Mata Pelajaran belum ditambahkan -->
        <div class="alert alert-warning">
            <p>Mata Pelajaran Belum ditambahkan!. <a href="{{ route('viewMapel') }}">Klik disini</a> untuk pergi ke menu
                Mata
                Pelajaran.</p>
        </div>
    @endif


    <div class="row p-4">
        <div class="col-12 col-lg-6 bg-white rounded-2">
            <div class="mt-4">
                <div class=" p-4">
                    @if (session()->has('delete-success'))
                        <!-- Notifikasi jika data berhasil dihapus -->
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('delete-success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    @if (session()->has('import-success'))
                        <!-- Notifikasi jika data berhasil diimpor -->
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('import-success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    @if (session()->has('import-error'))
                        <!-- Notifikasi jika terjadi error saat mengimpor data -->
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('import-error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if ($kelas->count() > 0)
                        <div class="row mb-2">
                            <div class="col-12">
                                <label for="search">Cari :</label>
                                <!-- Input untuk mencari kelas -->
                                <div class="input-group mb-3">
                                    <input type="text" id="search" class="form-control" placeholder="Cari Kelas..."
                                        aria-label="Cari berdasarkan Kelas..." aria-describedby="search">
                                </div>
                            </div>
                            <div class="col-12">
                                <!-- Tombol untuk memulai pencarian -->
                                <button class="btn btn-outline-primary w-100 animate-btn-small" type="button"
                                    id="btnSearch">Cari</button>
                            </div>
                        </div>
                        <div class="w-100">
                            <div id="badge" class="mb-3 d-flex justify-content-between align-items-center">
                                <button class="btn" type="button" id="clearSearch"><span
                                        class="badge p-2 badge-danger animate-btn-small" id="btnClear"></span></button>
                            </div>
                        </div>
                        <div id="tableContent">
                            {{-- Loading --}}
                            <div id="loadingIndicator" class="d-none">
                                <div class="spinner-border" role="status">
                                    <span class="visually-impaired">Loading...</span>
                                </div>
                            </div>
                            <!-- Informasi Jumlah Kelas -->
                            Jumlah Kelas : {{ $kelas->total() }}
                            <div class="table-responsive col-12 ">
                                <!-- Tabel Data Kelas -->
                                <table id="table" class="table table-striped table-lg ">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Nama</th>
                                            <th scope="col">Jumlah Mapel</th>
                                            <th scope="col">Jumlah Siswa</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($kelas as $key)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $key->name }}</td>
                                                <td>{{ $key->KelasMapel ? $key->KelasMapel->count() : 0 }}</td>
                                                <td>{{ count($key->dataSiswa) }}</td>
                                                <td>
                                                    <!-- Tombol Aksi -->
                                                    <a href="#table" data-bs-toggle="modal" data-bs-target="#modal-view"
                                                        data-kelasid="{{ $key->id }}"
                                                        class="badge bg-info p-2 mb-1 animate-btn-small"
                                                        onclick="getData({{ $key->id }})"><i
                                                            class="fa-regular fa-eye fa-xl"></i></a>
                                                    <a href="{{ route('viewUpdateKelas', ['kelas' => $key->id]) }}"
                                                        class="badge bg-secondary p-2 mb-1 animate-btn-small"><i
                                                            class="fa-solid fa-pen-to-square fa-xl"></i></a>
                                                    <a href="#table" class="badge bg-secondary p-2 animate-btn-small"><i
                                                            class="fa-solid fa-xl fa-trash" data-bs-toggle="modal"
                                                            data-bs-target="#deleteConfirmationModal"
                                                            onclick="changeValue('{{ $key->id }}', 'delete');"></i></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-center">
                                    {{ $kelas->links('pagination::bootstrap-5') }}
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Pesan jika belum ada data kelas -->
                        <div class="text-center mt-4">
                            <img src="{{ url('/asset/img/not-found.png') }}" alt="" class="img-fluid w-50 "
                                srcset="">
                            <br>
                            <Strong>Data belum ditambahkan</Strong>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <!-- Gambar di sisi kanan (hanya ditampilkan di perangkat desktop) -->
        <div class="col-6 text-center d-none d-lg-block">
            <img src="{{ url('/asset/img/office.png') }}" class="img-fluid-icon w-100" alt="">
        </div>
    </div>


    <!-- Modal Konfirmasi Hapus -->
    <div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteConfirmationModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close animate-btn-small" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menghapus Kelas ini?
                </div>
                <div class="modal-footer">
                    <form action="{{ route('destroyKelas') }}" method="post">
                        @csrf
                        <button type="button" class="btn btn-secondary animate-btn-small"
                            data-bs-dismiss="modal">Tutup</button>
                        <input type="hidden" name="idHapus" id="deleteButton" value="">
                        <button type="submit" class="btnHapusModal btn btn-danger animate-btn-small">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Detail Kelas -->
    <div class="modal fade" id="modal-view" tabindex="-1" aria-labelledby="modal-view" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa-solid fa-book"></i> Mapel Kelas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-1 ps-4 pe-4">
                        <img src="{{ url('/asset/img/panorama.png') }}" class="w-100 rounded-2 img-fluid"
                            alt="">
                    </div>
                    <div id="modalContent">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary animate-btn-small"
                        data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Import Data Kelas -->
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
                            menyebabkan Error pada Perintah Import, atau anda bisa melakukan penambahan/pengurangan dari
                            file hasil
                            Export.
                        </p>
                    </div>
                    <div class="mt-2 col-12 bg-body-secondary rounded-2 p-4">
                        <div class="mb-4">
                            <a href="{{ route('contohKelas') }}"><button type="button" class="btn btn-warning"><i
                                        class="fa-solid fa-download"></i> Download
                                    Contoh<span class="small">(.xls)</span></button></a>
                        </div>
                        <div>
                            <form method="POST" action="{{ route('importKelas') }}" enctype="multipart/form-data">
                                @csrf
                                <label for="file">Upload File<span class="small">(.xls, .xlsx)</span></label>
                                <input type="file" name="file" accept=".xlsx, .xls" required>
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
        const loading = `<div id="loadingIndicator2">
                        <div class="spinner-border" role="status">
                            <span class="visually-impaired"></span>
                        </div>
                    </div>`;

        const deleteButton = document.getElementById('deleteButton');
        const modalTitle = document.getElementById('deleteConfirmationModalLabel');
        const modalContent = document.getElementById('modalContent');

        var kelasId = null;

        function changeValue(itemId, modalType) {
            deleteButton.setAttribute('value', itemId);
        }

        function getData(itemId) {
            kelasId = itemId;
            $('#modalContent').html(loading);
            $.ajax({
                url: "{{ route('viewDetailKelas') }}",
                type: "GET",
                data: {
                    kelasId: itemId
                },
                success: function(data) {
                    $('#modalContent').html(data);
                    $("#loadingIndicator2").addClass("d-none");
                },
                error: function() {
                    console.error('Gagal mengambil data mapel.');
                    $("#loadingIndicator2").addClass("d-none");
                }
            });
        }
        const url = "{{ route('searchKelas') }}";
    </script>

    <script src="{{ url('/asset/js/customJS/s-kelas.js') }}"></script>

@endsection
