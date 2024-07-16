@extends('layout.template.mainTemplate')

@section('container')
    @if (Auth()->user()->roles_id == 1)
        @include('menu.admin.adminHelper')
    @endif

    <div class="col-12 ps-4 pe-4">
        <nav aria-label="breadcrumb bg-white">
            <ol class="breadcrumb bg-white">
                <li class="breadcrumb-item active" aria-current="page">Data Pengajar /</li>
            </ol>
        </nav>
    </div>

    <div class="ps-4 pe-4">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="display-6 fw-bold">Data Pengajar <a href="{{ route('viewTambahPengajar') }}"><button
                        class="btn btn-primary animate-btn-small">+
                        Tambah</button></a> <a href="{{ route('exportPengajar') }}"><button
                        class="btn btn-success animate-btn-small"><i class="fa-solid fa-file-export"></i> Export<span
                            class="small">(.xls)</span></button></a>
                <button class="btn btn-success animate-btn-small" data-bs-toggle="modal" data-bs-target="#importModal"
                    type="button"><i class="fa-solid fa-file-import"></i>
                    Import<span class="small">(.xls, .xlsx)</span></button>
            </h1>
        </div>
    </div>

    <div class="row p-4">
        <div class="col-12 col-lg-7 bg-white rounded-2">
            <div class="mt-4">
                <div class=" p-4">
                    @if (session()->has('delete-success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('delete-success') }}
                            <button type="button" class="btn-close " data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    @if (session()->has('import-success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('import-success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    @if (session()->has('import-error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('import-error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    @if ($pengajar->count() > 0)
                        <div class="row mb-2">
                            <div class="col-12">
                                <label for="search">Cari :</label>
                                <div class="input-group mb-3">
                                    <input type="text" id="search" class="form-control" placeholder="Cari Pengajar..."
                                        aria-label="Cari berdasarkan Pengajar..." aria-describedby="search">
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
                                    <span class="visually-impaired">Loading...</span>
                                </div>
                            </div>

                            Jumlah Pengajar: {{ $pengajar->total() }}
                            <div class="table-responsive col-12">
                                <table class="table table-striped table-lg">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">-</th>
                                            <th scope="col">Nama</th>

                                            <th scope="col">Mengajar</th>
                                            <th scope="col">Email</th>
                                            <th scope="col">No Telp</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @foreach ($pengajar as $key)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>
                                                    @if (!$key->gambar)
                                                        <img src="/asset/icons/profile-men.svg" width="50"
                                                            class="rounded-circle" alt="">
                                                    @else
                                                        <img src="{{ asset('storage/user-images/' . $key->gambar) }}"
                                                            class="rounded-circle" width="50" alt="">
                                                    @endif
                                                </td>
                                                <td>{{ $key->name }}</td>
                                                @if ($key->EditorAccess)
                                                    <td>{{ count($key->EditorAccess) }} Kelas</td>
                                                @else
                                                    <td>0 Kelas</td>
                                                @endif
                                                <td>{{ Str::substr($key->email, 0, 7) }}...</td>
                                                <td>{{ $key->Contact->no_telp }} </td>
                                                {{-- Action  {{ Route('viewProfile', ['User' => $key->id]) }} --}}
                                                <td>
                                                    <a href="{{ route('viewProfileAdmin', ['token' => encrypt($key->id)]) }}"
                                                        class="badge bg-info p-2 mb-1 animate-btn-small"><i
                                                            class="fa-regular fa-eye fa-xl"></i></a>
                                                    <a href="{{ route('viewUpdatePengajar', ['token' => encrypt($key->id)]) }}"
                                                        class="badge bg-secondary mb-1 p-2 animate-btn-small"><i
                                                            class="fa-solid fa-pen-to-square fa-xl"></i></a>
                                                    <a href="#table"
                                                        class="badge bg-secondary mb-1 p-2 animate-btn-small"><i
                                                            class="fa-solid fa-xl fa-trash" data-bs-toggle="modal"
                                                            data-bs-target="#deleteConfirmationModal"
                                                            onclick="changeValue('{{ $key->id }}');"></i></a>
                                                </td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-center" id="pagination-container">
                                    {{ $pengajar->links('pagination::bootstrap-5') }}
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center">
                            <img src="{{ url('/asset/img/not-found.png') }}" alt="" class="img-fluid w-50"
                                srcset="">
                            <br>
                            <Strong>Data belum ditambahkan</Strong>
                        </div>
                    @endif

                </div>
            </div>
        </div>

        <div class="col-5 text-center d-none d-lg-block">
            <img src="{{ url('/asset/img/teacher.png') }}" class="img-fluid-icon " alt="">
        </div>
    </div>

    <div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteConfirmationModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteConfirmationModalLabel">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close animate-btn-small" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menghapus Pengajar ini?
                </div>
                <div class="modal-footer">
                    <form action="{{ route('destroyPengajar') }}" method="post">
                        @csrf
                        <button type="button" class="btn btn-secondary animate-btn-small"
                            data-bs-dismiss="modal">Tutup</button>
                        <input type="hidden" name="idHapus" id="deleteButton" value="">
                        <button type="submit" class="btn btn-danger animate-btn-small">Hapus</button>
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
                    <button type="button" class="btn-close animate-btn-small" data-bs-dismiss="modal"
                        aria-label="Close"></button>
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
                            <a href="{{ route('contohPengajar') }}"><button type="button"
                                    class="btn btn-warning animate-btn-small"><i class="fa-solid fa-download"></i>
                                    Download
                                    Contoh<span class="small">(.xls)</span></button></a>
                        </div>
                        <div>
                            <form method="POST" action="{{ route('importPengajar') }}" enctype="multipart/form-data">
                                @csrf
                                <label for="file">Upload File<span class="small">(.xls, .xlsx)</span></label>
                                <input type="file" name="file" accept=".xlsx, .xls" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary animate-btn-small"
                        data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary animate-btn-small"><i class="fa-solid fa-upload"></i>
                        Import
                        Data</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function changeValue(itemId) {
            console.log(itemId);
            const deleteButton = document.getElementById('deleteButton');
            deleteButton.setAttribute('value', itemId);
        }
    </script>

    <script>
        const url = "{{ route('searchPengajar') }}";
    </script>
    <script src="{{ url('/asset/js/customJS/s-pengajar.js') }}"></script>
@endsection
