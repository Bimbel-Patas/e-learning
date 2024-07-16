@extends('layout.template.mainTemplate')

@section('container')
    @if (Auth()->user()->roles_id == 1)
        @include('menu.admin.adminHelper')
    @endif

    <div class="ps-4 pe-4 mt-4  pt-4">
        <h2 class="fw-bold display-6">{{ $data['action'] }} Siswa</h2>

        <nav style="" aria-label="breadcrumb">
            <ol class="breadcrumb bg-light">
                <li class="breadcrumb-item text-info" aria-current="page">Step 1</li>
                <li class="breadcrumb-item text-info">Step 2</li>
            </ol>
        </nav>
    </div>

    <div class="w-100 mb-4">
        <div class=" mx-auto bg-white border rounded-3 p-4 col-lg-6 col-sm-12">
            <div class="text-center">
                <img src="{{ url('/asset/img/check.png') }}" class="w-50 img-fluid mb-2" alt="">
                <h1>Siswa Berhasil {{ $data['prompt'] }}</h1>
                <i class="fa-regular fa-circle-check fa-shake fa-2xl text-success"></i>
                <hr>
                <div class="mx-auto" id="content">
                    @if ($data['action'] == 'Tambah')
                        <a href="{{ route('viewTambahSiswa') }}"> <button
                                class="mb-3 btn btn-lg btn-secondary w-50">Menambahkan
                                Lagi</button></a>
                    @endif
                    <a href="{{ route('viewSiswa') }}"><button class="btn-lg btn btn-primary w-50">Kembali</button></a>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ url('/asset/js/customJS/scrollToBottom.js') }}"></script>
@endsection
