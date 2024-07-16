@extends('layout.template.mainTemplate')

@section('container')
    @if (Auth()->user()->roles_id == 1)
        @include('menu.admin.adminHelper')
    @endif

    <div class="col-12 ps-4 pe-4 mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-white">
                <li class="breadcrumb-item active" aria-current="page">Data Mapel /</li>
            </ol>
        </nav>
    </div>

    <div class="ps-4 pe-4">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="display-6 fw-bold">Data Mapel <a href="{{ route('viewTambahMapel') }}"><button
                        class="btn btn-primary animate-btn-small">+
                        Tambah</button></a>
                <a href="{{ route('exportMapel') }}"><button class="btn btn-success animate-btn-small"><i
                            class="fa-solid fa-file-export"></i>
                        Export<span class="small">(.xls)</span></button></a>
                <button class="btn btn-success animate-btn-small" data-bs-toggle="modal" data-bs-target="#importModal"
                    type="button"><i class="fa-solid fa-file-import"></i> Import<span class="small">(.xls, .xlsx)</span>
                </button>
            </h1>
        </div>
    </div>

    <div class="">
        <div class="row p-4">
            <div class="col-12 col-lg-6 bg-white rounded-2">
                <div class="mt-4">
                    <div class=" p-4">
                        @if (session()->has('delete-success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('delete-success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif
                        @if (session()->has('import-success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('import-success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif
                        @if (session()->has('import-error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('import-error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif
                        @if ($mapel->count() > 0)
                            <div class="row mb-2">
                                <div class="col-12">
                                    <label for="search">Cari :</label>
                                    <div class="input-group mb-3">
                                        <input type="text" id="search" class="form-control"
                                            placeholder="Cari Mapel..." aria-label="Cari berdasarkan mapel..."
                                            aria-describedby="search">
                                    </div>
                                </div>
                                <div class="col-12">
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
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>

                                Jumlah Mapel : {{ $mapel->total() }}
                                <div class="table-responsive col-12 ">
                                    <table id="table" class="table table-striped table-lg ">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">Nama</th>
                                                <th scope="col">Deskripsi</th>
                                                <th scope="col">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            @foreach ($mapel as $key)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $key->name }}</td>
                                                    <td>{{ Str::substr($key->deskripsi, 0, 7) }}</td>
                                                    <td>
                                                        <a href="{{ route('viewUpdateMapel', ['mapel' => $key->id]) }}"
                                                            class="badge bg-info p-2 mb-1 animate-btn-small"><i
                                                                class="fa-solid fa-pen-to-square fa-xl mb-1"></i></a>
                                                        <a href="#table"
                                                            class="badge bg-secondary p-2 animate-btn-small"><i
                                                                class="fa-solid fa-xl fa-trash mb-1" data-bs-toggle="modal"
                                                                data-bs-target="#deleteConfirmationModal"
                                                                onclick="changeValue('{{ $key->id }}');"></i></a>
                                                    </td>
                                                </tr>
                                            @endforeach

                                        </tbody>
                                    </table>
                                    <div class="d-flex justify-content-center" id="pagination-container">
                                        {{ $mapel->links('pagination::bootstrap-5') }}
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="text-center">
                                <img src="{{ url('/asset/img/not-found.png') }}" alt=""
                                    class="img-fluid-icon w-50 mb-2" srcset="">
                                <br>
                                <Strong>Data belum ditambahkan</Strong>
                            </div>
                        @endif

                    </div>
                </div>
            </div>

            <div class="col-6 text-center d-none d-lg-block">
                <img src="{{ url('/asset/img/exam.png') }}" class="img-fluid-icon w-100" alt="">
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteConfirmationModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteConfirmationModalLabel">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menghapus Mapel ini?
                </div>
                <div class="modal-footer">
                    <form action="{{ route('destroyMapel') }}" method="post">
                        @csrf
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <input type="hidden" name="idHapus" id="deleteButton" value="">
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

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
                            <a href="{{ route('contohMapel') }}"><button type="button" class="btn btn-warning"><i
                                        class="fa-solid fa-download"></i> Download
                                    Contoh<span class="small">(.xls)</span></button></a>
                        </div>
                        <div>
                            <form method="POST" action="{{ route('importMapel') }}" enctype="multipart/form-data">
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
        // Fungsi untuk mengubah nilai tombol hapus
        function changeValue(itemId) {
            const deleteButton = document.getElementById('deleteButton');
            deleteButton.setAttribute('value', itemId);
        }

        // URL untuk pencarian mapel
        const url = "{{ route('searchMapel') }}";
    </script>

    <script src="{{ url('/asset/js/customJS/s-mapel.js') }}"></script>

    <script>
        // Buat fungsi untuk menangani klik halaman paginasi dengan AJAX
    </script>

@endsection
