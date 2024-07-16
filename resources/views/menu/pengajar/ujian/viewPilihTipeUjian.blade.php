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
                <li class="breadcrumb-item active" aria-current="page">Tipe Ujian</li>
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

        {{-- Breadcrumb --}}
        <nav style="" aria-label="breadcrumb">
            <ol class="breadcrumb bg-light">
                <li class="breadcrumb-item text-info" aria-current="page">Step 1</li>
                <li class="breadcrumb-item ">Step 2</li>
            </ol>
        </nav>
    </div>

    {{-- Formulir Tambah Ujian --}}
    <div class="">
        <div class="row p-4">
            <h4 class="fw-bold text-primary"><i class="fa-solid fa-pen"></i> Pilih Tipe Ujian</h4>
            <div class="col-12 col-lg-12">
                <div class="mt-4 row my-auto mx-auto">
                    <div class="card m-2 col-lg-2 col-md-3 col-12">
                        <img src="{{ url('/asset/img/essay.png') }}" class="card-img-top" alt="...">
                        <div class="card-body">
                            <h5 class="card-title fw-bold">Pilihan Ganda <i class="fa-solid fa-circle-check fa-bounce"></i>
                            </h5>
                            <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                            <a href="{{ route('viewCreateUjian', ['token' => encrypt($kelasId), 'type' => 'multiple', 'mapelId' => $mapel['id']]) }}"
                                class="w-100 btn btn-primary">Pilih</a>
                        </div>
                    </div>
                    <div class="card m-2 col-lg-2 col-md-3 col-12">
                        <img src="{{ url('/asset/img/multiple.png') }}" class="card-img-top" alt="...">
                        <div class="card-body">
                            <h5 class="card-title fw-bold">Essay <i class="fa-solid fa-pencil fa-bounce"></i></h5>
                            <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                            <a href="{{ route('viewCreateUjian', ['token' => encrypt($kelasId), 'type' => 'essay', 'mapelId' => $mapel['id']]) }}"
                                class="w-100 btn btn-primary">Pilih</a>
                        </div>
                    </div>
                    <div class="card m-2 col-lg-2 col-md-3 col-12">
                        <img src="{{ url('/asset/img/multiple.png') }}" class="card-img-top" alt="...">
                        <div class="card-body">
                            <h5 class="card-title fw-bold">Kecermatan <i class="fa-solid fa-pencil fa-bounce"></i></h5>
                            <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                            <a href="{{ route('viewCreateUjian', ['token' => encrypt($kelasId), 'type' => 'kecermatan', 'mapelId' => $mapel['id']]) }}"
                                class="w-100 btn btn-primary">Pilih</a>
                        </div>
                    </div>
                    <div class="card m-2 bg-transparent col-lg-2 col-md-3 col-12">
                        <img src="{{ url('/asset/img/coming_soon.png') }}" class="card-img-top"
                            style="filter: saturate(0);" alt="...">
                        <div class="card-body text-secondary">
                            <h5 class="card-title fw-bold">Tipe Ujian Lainya...</h5>
                            <p class="card-text">akan segera hadir</p>
                            {{-- <button href="#" class="btn btn-dark" disabled>Tambah tipe</button> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
