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
                <li class="breadcrumb-item active" aria-current="page">Tambah Ujian</li>
            </ol>
        </nav>
    </div>

    {{-- Judul Halaman --}}
    <div class="ps-4 pe-4 mt-4  pt-4">
        <h2 class="display-6 fw-bold">
            <a
                href="{{ route('viewKelasMapel', ['mapel' => $mapel['id'], 'token' => encrypt($kelasId), 'mapel_id' => $mapel['id']]) }}">
                <button type="button" class="btn btn-outline-secondary rounded-circle">
                    <i class="fa-solid fa-arrow-left"></i>
                </button>
            </a> Tambah Ujian
        </h2>
    </div>

    {{-- Formulir Tambah Ujian --}}
    <div class="">
        <div class="row p-4">
            <div class="col-12 col-lg-12">
                <div class="mt-4 row ">
                    {{-- Section Left --}}
                    <div class="p-4 col-lg-6 col-12  bg-white rounded-2">
                        <form action="{{ route('createTugas') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            {{-- Nama Ujian --}}
                            <div class="mb-3">
                                <label for="name" class="form-label">Judul Ujian</label>
                                <input type="hidden" name="kelasId" value="{{ encrypt($kelasId) }}" readonly>
                                <input type="hidden" name="mapelId" value="{{ $mapel['id'] }}" readonly>
                                <input type="text" class="form-control" id="name" name="name"
                                    placeholder="Inputkan judul Tugas..." value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="text-danger small">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- Reserved grid --}}
                            <div class="row mb-3">
                                <div class="col-12 col-lg-12">
                                    <label class="form-label">Tipe Ujian</label>
                                    <select class="form-select" aria-label="Default select example">
                                        <option value="1" selected>Pilihan Ganda</option>
                                        <option value="2">Essay</option>
                                    </select>
                                </div>
                            </div>

                            {{-- Waktu Ujian (Timer) --}}
                            <div class="mb-3">
                                <div class="">
                                    <label for="time" class="form-label">Waktu Ujian <span class="small">(dalam
                                            menit)</span></label>
                                    <input type="number" class="form-control" id="time" name="time" placeholder="0"
                                        value="{{ old('time') }}" required>
                                    @error('time')
                                        <div class="text-danger small">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Tombol Submit --}}
                            <div class="">
                                <button type="submit" class="btn-lg btn btn-primary w-100"
                                    id="btnSimpan">Lanjutkan</button>
                            </div>
                        </form>
                    </div>
                    {{-- Section Kanan --}}
                    <div class="col-12 col-lg-6">
                        <img src="{{ url('/asset/img/work.png') }}" class="d-block mx-lg-auto img-fluid " alt=""
                            loading="lazy">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
