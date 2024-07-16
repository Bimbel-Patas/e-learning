@extends('layout.template.mainTemplate')

@section('container')
    @if (Auth()->user()->roles_id == 1)
        @include('menu.admin.adminHelper')
    @endif

    <div class="col-12 ps-4 pe-4 mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-white">
                <li class="breadcrumb-item"><a href="{{ route('viewKelas') }}">Data Kelas</a></li>
                <li class="breadcrumb-item active" aria-current="page">Detail Kelas</li>
            </ol>
        </nav>
    </div>

    <div class="ps-4 pe-4 mt-4 pt-4">
        <h2 class="fw-bold display-6">Detail Kelas : {{ $kelas->name }}</h2>
        <a href="{{ route('viewUpdateKelas', ['kelas' => $kelas->id]) }}"><button class="btn btn-warning">Edit</button></a>
        <button class="btn btn-danger">Hapus</button>
    </div>

    <div class="">
        <div class="row p-4  ">
            <div class="col-12 col-lg-6 bg-white rounded-2">
                <div class="mt-4">
                    <div class=" p-4">

                        {{-- Nama --}}
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="nama" name="nama"
                                placeholder="Inputkan nama kelas... " value="{{ old('nama', $kelas->name) }}" required
                                readonly>
                        </div>

                        {{-- Bagian tabel untuk menampilkan mapel yang ditambahkan --}}
                        <table id="tabelMapel" class="table">
                            <thead>
                                <tr>
                                    <th>Nama Mapel</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data mapel akan ditambahkan oleh JavaScript -->
                                @foreach ($kelasMapel as $key)
                                    <tr>
                                        <td>{{ $key->name }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-6 text-center d-none d-lg-block">
                <img src="{{ url('/asset/img/office.png') }}" class="img-fluid w-100" alt="">
            </div>
        </div>
    </div>

    <script src="{{ url('/asset/js/lottie.js') }}"></script>
    <script src="{{ url('/asset/js/customJS/simpleAnim.js') }}"></script>
@endsection
