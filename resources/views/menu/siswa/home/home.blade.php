@extends('layout.template.mainTemplate')

@section('container')
    {{-- Mengimpor file CSS --}}
    <link rel="stylesheet" href="{{ url('/asset/css/card-img-full.css') }}">

    <div class="row">
        {{-- Pesan Sukses --}}
        @if (session()->has('success'))
            <div class="alert alert-lg alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="col-12 ps-4 pe-4">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-white">
                    <li class="breadcrumb-item active" aria-current="page">Profile</li>
                </ol>
            </nav>
        </div>

        {{-- Informasi Home --}}
        <div class="bg-body-secondary rounded-4 mb-4">
            <div class="container col-xxl-8 px-4 py-5 ">
                <div class="row flex-lg-row-reverse align-items-center g-5 py-5">
                    <div class="col-10 col-sm-8 col-lg-6">

                        <img src="{{ url('/asset/img/work.png') }}" class="d-block mx-lg-auto img-fluid h-50 w-50 "
                            alt="" loading="lazy">

                    </div>
                    <div class="col-lg-6">
                        <h1 class=" fw-bold text-body-emphasis lh-1 mb-3">{{ $user['name'] }}</h1>
                        <p class="lead">Selamat datang!, Selamat belajar!</p>
                        <!-- <button class="btn btn-outline-primary" onclick="getData('{{ $kelas['name'] }}')"
                            data-bs-toggle="modal" data-bs-target="#modal-view"><i class="fa-solid fa-users"></i> View
                            Siswa</button> -->
                    </div>
                </div>
            </div>
        </div>

        {{-- Gambar profil --}}
        <div class="col-12 col-sm-5 col-md-5 col-lg-3 p-4">
            <div class="bg-white rounded-2 p-4">
                <div id="profile">
                    <div class="mx-auto w-75">
                        <div class="text-center">
                            {{-- Tampilkan gambar profil atau avatar default --}}
                            @if ($user->gambar == null)
                                <img src="/asset/icons/profile-women.svg" class="image-previewer image-class rounded-circle"
                                    width="150" alt="">
                            @else
                                <img src="{{ asset('storage/user-images/' . $user->gambar) }}" alt="placeholder"
                                    class="image-previewer image-class rounded-circle" width="150">
                            @endif
                        </div>
                    </div>
                    <div class="text-center mt-2">
                        <h4 class="fs-5">{{ $user->name }}</h4>
                        <span class="badge badge-success p-2">
                            @if ($user->roles_id == 1)
                                Admin
                            @elseif($user->roles_id == 2)
                                Pengajar
                            @elseif($user->roles_id == 3)
                                Siswa
                            @endif
                        </span>
                    </div>


                </div>
            </div>


        </div>

        <div class="col-sm-7 col-md-7 col-lg col-12 p-4 ">
            <div class="border border-light-subtle p-4 rounded-2">

                <div class="row">
                    <div class="d-sm-none d-block">
                        {{-- Tampilkan kartu-kartu kelas yang dimiliki oleh pengguna --}}
                        @foreach ($mapelKelas as $mapelKelasItem)
                            {{-- {{ dd($mapelKelasItem) }} --}}
                            <div class="card w-100 my-4" style="width: 18rem;">
                                @if ($mapelKelasItem['gambar'] != null)
                                    <img src="{{ asset('storage/mapel/' . $mapelKelasItem['gambar']) }}"
                                        class="card-img-top" height="150px" alt="...">
                                @else
                                    <img src="{{ url('/asset/img/placeholder-3.jpg') }}" class="card-img-top" alt="..."
                                        height="150px">
                                @endif
                                <div class="card-body">
                                    {{-- Tampilkan nama mapel --}}
                                    @if ($user->id == Auth()->User()->id)
                                        <a href="{{ route('viewKelasMapel', ['mapel' => $mapelKelasItem['mapel_id'], 'token' => encrypt($kelas['id']), 'mapel_id' => $mapelKelasItem['mapel_id']]) }}"
                                            class="text-dark" style="text-decoration: none;">
                                    @endif
                                    <h5 class="card-title">{{ $mapelKelasItem['mapel_name'] }}</h5>
                                    @if ($user->id == Auth()->User()->id)
                                        </a>
                                    @endif

                                    <h6 class="small">Pengajar :
                                        {{-- Tampilkan nama pengajar jika ada --}}
                                        @if ($mapelKelasItem['pengajar_name'] == '-')
                                            -
                                        @else
                                            <a
                                                href="{{ route('viewProfilePengajar', ['token' => encrypt($mapelKelasItem['pengajar_id'])]) }}">{{ $mapelKelasItem['pengajar_name'] }}</a>
                                        @endif
                                    </h6>
                                    <p class="card-text">{{ Str::substr($mapelKelasItem['deskripsi'], 0, 150) }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="row d-none d-sm-block">
                    {{-- Tampilkan kartu-kartu kelas yang dimiliki oleh pengguna dalam tampilan desktop --}}
                    @foreach ($mapelKelas as $mapelKelasItem)
                        <div class="card mb-3 col-12 col-sm-12 col-md-12 mx-2 bg-white shadow-sm">
                            <div class="row g-0">
                                <div class="col-md-4">
                                    @if ($user->id == Auth()->User()->id)
                                        <a
                                            href="{{ route('viewKelasMapel', ['mapel' => $mapelKelasItem['mapel_id'], 'token' => encrypt($kelas['id']), 'mapel_id' => $mapelKelasItem['mapel_id']]) }}">
                                    @endif

                                    @if ($mapelKelasItem['gambar'] != null)
                                        <div class="card-img-full"
                                            style="background-image: url('{{ asset('storage/mapel/' . $mapelKelasItem['gambar']) }}')">
                                        </div>
                                    @else
                                        <div class="card-img-full"
                                            style="background-image: url('{{ url('/asset/img/placeholder-3.jpg') }}')">
                                        </div>
                                    @endif

                                    @if ($user->id == Auth()->User()->id)
                                        </a>
                                    @endif
                                </div>
                                <div class="col-md-8">
                                    <div class="card-body">
                                        @if ($user->id == Auth()->User()->id)
                                            <a href="{{ route('viewKelasMapel', ['mapel' => $mapelKelasItem['mapel_id'], 'token' => encrypt($kelas['id']), 'mapel_id' => $mapelKelasItem['mapel_id']]) }}"
                                                class="text-dark" style="text-decoration: none;">
                                        @endif

                                        {{-- Tampilkan nama mapel --}}
                                        <h5 class="card-title text-black fw-bold">{{ $mapelKelasItem['mapel_name'] }}</h5>

                                        @if ($user->id == Auth()->User()->id)
                                            </a>
                                        @endif

                                        <h6 class="small">Pengajar :
                                            {{-- Tampilkan nama pengajar jika ada --}}
                                            @if ($mapelKelasItem['pengajar_name'] == '-')
                                                -
                                            @else
                                                <a
                                                    href="{{ route('viewProfilePengajar', ['token' => encrypt($mapelKelasItem['pengajar_id'])]) }}">{{ $mapelKelasItem['pengajar_name'] }}</a>
                                            @endif
                                        </h6>
                                        <p class="card-text">{{ Str::substr($mapelKelasItem['deskripsi'], 0, 150) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- Modal untuk menampilkan daftar siswa dalam kelas --}}
    <div class="modal fade" id="modal-view" tabindex="-1" aria-labelledby="modal-view" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    {{-- Tampilkan judul modal dengan nama kelas --}}
                    <h5 class="modal-title"><i class="fa-solid fa-book"></i> Siswa di {{ $kelas['name'] }}</h5>
                    <button type="button" class="btn-close animate-btn-small" data-bs-dismiss="modal"
                        aria-label="Close"></button>
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

    {{-- Script JavaScript --}}
    <script>
        const loading = `<div id="loadingIndicator2">
                      <div class="spinner-border text-info" role="status">
  <span class="visually-hidden">Loading...</span>
</div>
                    </div>`;

        const modalContent = document.getElementById('modalContent');

        function getData(itemId) {
            console.log(itemId);
            kelasName = "{{ $kelas['name'] }}";
            $('#modalContent').html(loading);
            $.ajax({
                url: "{{ route('viewSiswaKelas') }}", // Ganti dengan URL rute Anda
                type: "GET",
                data: {
                    kelasName: kelasName
                },
                success: function(data) {
                    // Perbarui konten modal dengan data mapel yang diambil
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
@endsection
