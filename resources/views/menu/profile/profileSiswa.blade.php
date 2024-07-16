@extends('layout.template.mainTemplate') {{-- Extends layout utama aplikasi --}}

@section('container')
    {{-- Bagian konten yang akan digantikan di layout utama --}}

    {{-- Mengimpor file CSS --}}
    <link rel="stylesheet" href="{{ url('/asset/css/card-img-full.css') }}">

    <div class="row">

        {{-- Tampilkan menu admin jika pengguna adalah admin --}}
        @if (Auth()->user()->roles_id == 1)
            @include('menu.admin.adminHelper')
        @endif

        <div class="col-12 ps-4 pe-4">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-white">
                    <li class="breadcrumb-item active" aria-current="page">Profile</li>
                </ol>
            </nav>
        </div>

        {{-- Header --}}
        <div class="ps-4 pe-4 mt-4">
            <h2 class="fw-bold display-6">
                <a href="
                    @if (Auth()->user()->roles_id == 1) {{ route('viewSiswa') }}
                    @else
                        # @endif"
                    @if (Auth()->user()->roles_id != 1) onclick="window.history.back()" @endif>
                    <button type="button" class="btn btn-outline-secondary rounded-circle ">
                        <i class="fa-solid fa-arrow-left"></i>
                    </button>
                </a>
                User Profile
            </h2>
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

                    {{-- Tampilkan nama kelas jika tersedia --}}
                    <div class="text-center mt-2">
                        @if ($kelas != null)
                            <a href="#" data-bs-toggle="modal" data-bs-target="#modal-view"
                                onclick="getData('{{ $kelas }}')">{{ $kelas['name'] }}</a>
                        @endif
                    </div>

                    {{-- Tampilkan email pengguna --}}
                    <div class="text-center mt-2">
                        <i class="fa-solid fa-envelope"></i>
                        {{ $user->email }}
                    </div>

                    {{-- Tampilkan nomor telepon jika tersedia --}}
                    <div class="text-center mt-2">
                        @if ($user->contact->no_telp != null)
                            <i class="fa-solid fa-phone"></i>
                            {{ $user->contact->no_telp }}
                        @endif
                    </div>
                </div>
            </div>

            <div class="mt-4 text-center">
                <div class="mb-2">
                    {{-- Tampilkan tombol WhatsApp jika nomor telepon tersedia --}}
                    @if ($user->contact->no_telp != null)
                        <a href="{{ 'https://wa.me/62' . ltrim($user->contact->no_telp, '0') }}">
                            <button type="button" class="btn btn-md w-100 btn-success animate-btn-small"><i
                                    class="fa-solid fa-phone"></i>
                                Chat
                                Whatsapp
                            </button>
                        </a>
                    @endif
                </div>

                {{-- Tampilkan tombol Edit User jika pengguna adalah admin --}}
                @if (Auth()->user()->roles_id == 1)
                    <span class="badge badge-dark p-2 mb-2"><i class="fa-solid fa-screwdriver-wrench fa-bounce"></i>
                        Admin Panel</span>
                    <div class="mb-2">
                        <a href="{{ route('viewUpdateUserSiswa', ['token' => encrypt($user->id)]) }}">
                            <button type="button"
                                class="btn btn-md w-100 btn-dark btn-outline-warning text-white animate-btn-small"><i
                                    class="fa-solid fa-user-pen"></i>
                                Edit User</button>
                        </a>
                    </div>
                @endif

                {{-- Tampilkan tombol Setting jika pengguna adalah pemilik profil --}}
                @if ($user->id == Auth()->User()->id)
                    <div class="mb-2">
                        <a href="{{ route('viewProfileSetting', ['token' => encrypt($user->id)]) }}">
                            <button type="button" class="btn btn-md w-100 btn-outline-dark animate-btn-small"><i
                                    class="fa-solid fa-gear"></i>
                                Setting</button>
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <div class="col-sm-7 col-md-7 col-lg col-12 p-4 ">
            <div class="bg-white p-4 rounded-2">
                <div id="header-kelas">
                    <h4 class="fw-bold text-primary">Mapel saya : </h4>
                </div>

                <div class="row">
                    <div class="d-sm-none d-block">
                        {{-- Tampilkan kartu-kartu kelas yang dimiliki oleh pengguna --}}
                        @foreach ($mapelKelas as $mapelKelasItem)
                            <div class="card w-100 my-4" style="width: 18rem;">
                                @if ($mapelKelasItem['gambar'] != null)
                                    <img src="{{ asset('storage/mapel/' . $mapelKelasItem['gambar']) }}"
                                        class="card-img-top" height="150px" alt="...">
                                @else
                                    <img src="{{ url('/asset/img/placeholder-3.jpg') }}" class="card-img-top"
                                        alt="..." height="150px">
                                @endif
                                <div class="card-body">
                                    {{-- Tampilkan nama mapel --}}
                                    @if ($user->id == Auth()->User()->id)
                                        <a href="#" class="text-dark" style="text-decoration: none;">
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
                        <div class="card mb-3 col-12 col-sm-12 col-md-12 mx-2">
                            <div class="row g-0">
                                <div class="col-md-4">

                                    @if ($mapelKelasItem['gambar'] != null)
                                        <div class="card-img-full"
                                            style="background-image: url('{{ asset('storage/mapel/' . $mapelKelasItem['gambar']) }}')">
                                        </div>
                                    @else
                                        <div class="card-img-full"
                                            style="background-image: url('{{ url('/asset/img/placeholder-3.jpg') }}')">
                                        </div>
                                    @endif


                                </div>
                                <div class="col-md-8">
                                    <div class="card-body">

                                        {{-- Tampilkan nama mapel --}}
                                        <h5 class="card-title">{{ $mapelKelasItem['mapel_name'] }}</h5>

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
