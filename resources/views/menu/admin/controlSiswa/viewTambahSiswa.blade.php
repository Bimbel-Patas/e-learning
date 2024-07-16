@extends('layout.template.mainTemplate')

@section('container')
    @if (Auth()->user()->roles_id == 1)
        @include('menu.admin.adminHelper')
    @endif

    {{-- Navigasi Breadcrumb --}}
    <div class="col-12 ps-4 pe-4 mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-white">
                <li class="breadcrumb-item"><a href="{{ route('viewSiswa') }}">Data Siswa</a></li>
                <li class="breadcrumb-item active" aria-current="page">Tambah Siswa</li>
            </ol>
        </nav>
    </div>

    {{-- Judul Halaman --}}
    <div class="ps-4 pe-4 mt-4 pt-4">
        <h2 class="fw-bold display-6">
            <a href="{{ route('viewSiswa') }}">
                <button type="button" class="btn btn-outline-secondary rounded-circle">
                    <i class="fa-solid fa-arrow-left"></i>
                </button>
            </a> Tambah Data Siswa
        </h2>
        {{-- Navigasi Step --}}
        <nav style="" aria-label="breadcrumb">
            <ol class="breadcrumb bg-light">
                <li class="breadcrumb-item text-info" aria-current="page">Step 1</li>
                <li class="breadcrumb-item">Step 2</li>
            </ol>
        </nav>
    </div>

    {{-- Isi Konten --}}
    <div class="">
        <div class="row p-4  ">
            <h4 class="fw-bold text-primary"><i class="fa-solid fa-pen"></i> Data Siswa</h4>
            <div class="col-12 col-lg-6 bg-white rounded-2">
                <div class="mt-4">
                    <div class="p-4">
                        <form action="{{ route('validateDataSiswa') }}" method="POST">
                            @csrf
                            {{-- Nama --}}
                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama</label>
                                <input type="text" class="form-control" id="nama" name="nama"
                                    placeholder="Inputkan nama siswa..." value="{{ old('nama') }}" required>
                                @error('nama')
                                    <div class="text-danger small">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- Kelas --}}
                            <div class="mb-3">
                                <label for="kelas" class="form-label">Kelas</label>
                                <select class="form-select" aria-label="Default select example" name="kelas">
                                    <option value="" selected>Pilih Kelas</option>
                                    @foreach ($dataKelas as $key)
                                        <option value="{{ $key->id }}"
                                            @if (old('kelas') == $key->id) selected @endif>{{ $key->name }}</option>
                                    @endforeach
                                </select>
                                @error('kelas')
                                    <div class="text-danger small">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- NIS --}}
                            <div class="mb-3">
                                <label for="nis" class="form-label">NIS (Nomor Induk Siswa)</label>
                                <input type="number" class="form-control" id="nis" name="nis"
                                    placeholder="Inputkan NIS..." value="{{ old('nis') }}" required>
                                @error('nis')
                                    <div class="text-danger small">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- Tombol Simpan --}}
                            <div class="">
                                <button type="submit" class="btn-lg btn btn-primary w-100">Simpan dan Lanjutkan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Gambar Illustrasi --}}
            <div class="col-6 text-center d-none d-lg-block">
                <img src="{{ url('/asset/img/work.png') }}" class="img-fluid w-100" alt="">
            </div>
        </div>
    </div>

    {{-- Script JavaScript --}}
    <script src="{{ url('/asset/js/lottie.js') }}"></script>
    <script src="{{ url('/asset/js/customJS/simpleAnim.js') }}"></script>
@endsection
