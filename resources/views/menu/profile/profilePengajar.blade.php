@extends('layout.template.mainTemplate')

@section('container')
    <link rel="stylesheet" href="{{ url('/asset/css/card-img-full.css') }}">

    <div class="row">

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
            <h1 class="display-6 fw-bold">
                <a href="  @if (Auth()->user()->roles_id == 1) {{ route('viewPengajar') }}@else # @endif"
                    @if (Auth()->user()->roles_id != 1) onclick="window.history.back()" @endif><button type="button"
                        class="btn btn-outline-secondary rounded-circle">
                        <i class="fa-solid fa-arrow-left"></i></button></a>
                Profile Pengajar
            </h1>
        </div>

        <div class="col-12 col-sm-5 col-md-5 col-lg-3 p-4">
            {{-- img --}}
            <div class="bg-white rounded-2 p-4">
                <div id="profile">
                    <div class="mx-auto w-75">
                        <div class="text-center">
                            @if ($user->gambar == null)
                                <img src="/asset/icons/profile-women.svg" class="image-previewer image-class rounded-circle"
                                    width="150" alt="">
                            @else
                                <img src="{{ asset('/storage/user-images/' . $user->gambar) }}" alt="placeholder"
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

                    {{-- Email --}}
                    <div class="text-center mt-2">
                        <i class="fa-solid fa-envelope"></i>
                        {{ $user->email }}
                    </div>

                    {{-- No Telp --}}
                    <div class="text-center mt-2">
                        <i class="fa-solid fa-phone"></i>
                        @if ($user->contact->no_telp != null)
                            {{ $user->contact->no_telp }}
                        @else
                            <span class="small">(belum ditambahkan)</span>
                        @endif
                    </div>

                </div>
            </div>

            <div class="mt-4 text-center">
                <div class="mb-2">
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

                @if (Auth()->user()->roles_id == 1)
                    <span class="badge badge-dark p-2 mb-2"><i class="fa-solid fa-screwdriver-wrench fa-bounce"></i> Admin
                        Panel</span>
                    <div class="mb-2">
                        <a href="{{ route('viewUpdatePengajar', ['token' => encrypt($user->id)]) }}">
                            <button type="button"
                                class="btn btn-md w-100 btn-dark btn-outline-warning text-white animate-btn-small"><i
                                    class="fa-solid fa-user-pen"></i>
                                Edit User</button>
                        </a>
                    </div>

                    <div class="mb-2">
                        <form action="{{ route('destroyPengajar') }}" method="POST">
                            @csrf
                            <input type="hidden" name="idHapus" value="{{ $user->id }}">
                            <button type="submit"
                                class="btn btn-md w-100 btn-dark btn-outline-warning text-white animate-btn-small"><i
                                    class="fa-solid fa-user-xmark"></i>
                                Delete User</button>
                        </form>
                    </div>
                @endif

                @if ($user->id == Auth()->User()->id && $user->roles_id != 1)
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

            <div class="mb-4 bg-white p-4 rounded-2">
                <div id="Topic">
                    <h4 class="fw-bold">Tentang Saya :</h4>
                    <p>{{ $user->deskripsi }}</p>
                </div>
            </div>

            <div class="bg-white p-4 rounded-2">
                <div id="header-kelas">
                    <h4 class="fw-bold">Kelas saya : </h4>
                </div>

                <div class="row">
                    <div class="d-sm-none d-block">
                        @foreach ($mapelKelas as $mapelKelasItem)
                            <div class="card w-100 my-4" style="width: 18rem;">
                                @if ($mapelKelasItem['mapel']->gambar != null)
                                    <img src="{{ url('storage/mapel/' . $mapelKelasItem['mapel']->gambar) }}"
                                        class="card-img-top" height="150px" alt="...">
                                @else
                                    <img src="{{ url('/asset/img/placeholder-3.jpg') }}" class="card-img-top"
                                        alt="..." height="150px">
                                @endif
                                <div class="card-body">
                                    <h5 class="card-title fw-bold">{{ $mapelKelasItem['mapel']->name }}</h5>
                                    <h6 class="small">Pengajar : {{ $user->name }}</h6>
                                    <p class="card-text">{{ Str::substr($mapelKelasItem['mapel']->deskripsi, 0, 100) }}</p>
                                    @if ($mapelKelasItem['kelas'])
                                        @foreach ($mapelKelasItem['kelas'] as $kelas)
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#modal-view"
                                                onclick="getData('{{ $kelas->name }}')"><span
                                                    class="my-1 badge badge-info p-2">
                                                    {{ $kelas->name }}</span></a>
                                        @endforeach
                                    @else
                                        <p>Tidak ada data kelas yang tersedia.</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>


                <div class="row d-none d-sm-block">
                    {{-- Blade disini --}}
                    {{-- {{ dd($mapelKelas) }} --}}
                    @foreach ($mapelKelas as $mapelKelasItem)
                        <div class="card mb-3 col-12 col-sm-12 col-md-12 mx-2">
                            <div class="row g-0">
                                <div class="col-md-4">
                                    @if ($mapelKelasItem['mapel']->gambar != null)
                                        <div class="card-img-full"
                                            style="background-image: url('{{ asset('storage/mapel/' . $mapelKelasItem['mapel']->gambar) }}')">
                                        </div>
                                    @else
                                        <div class="card-img-full"
                                            style="background-image: url('{{ url('/asset/img/placeholder-3.jpg') }}')">
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-8">
                                    <div class="card-body">
                                        <h5 class="card-title fw-bold">{{ $mapelKelasItem['mapel']->name }}</h5>
                                        <h6 class="small">Pengajar : {{ $user->name }}</h6>
                                        <p class="card-text">
                                            {{ Str::substr($mapelKelasItem['mapel']->deskripsi, 0, 100) }}
                                        </p>
                                        @if ($mapelKelasItem['kelas'])
                                            @foreach ($mapelKelasItem['kelas'] as $kelas)
                                                <a href="#" data-bs-toggle="modal" data-bs-target="#modal-view"
                                                    onclick="getData('{{ $kelas->name }}')"><span
                                                        class="my-1 badge badge-info p-2">
                                                        {{ $kelas->name }}</span></a>
                                            @endforeach
                                        @else
                                            <p>Tidak ada data kelas yang tersedia.</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    {{-- End Blade --}}
                </div>


            </div>

        </div>
    </div>

    <div class="modal fade" id="modal-view" tabindex="-1" aria-labelledby="modal-view" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa-solid fa-book"></i> Siswa di <span id="namaKelas"></span></h5>
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
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        const loading = `<div id="loadingIndicator2">
                      <div class="spinner-border text-info" role="status">
  <span class="visually-hidden">Loading...</span>
</div>
                    </div>`;

        const modalContent = document.getElementById('modalContent');

        function getData(item) {
            console.log(item);
            kelasName = item;
            $('#namaKelas').html(kelasName);
            $('#modalContent').html(loading);
            $.ajax({
                url: "{{ route('viewSiswaKelas') }}",
                type: "GET",
                data: {
                    kelasName: kelasName
                },
                success: function(data) {
                    $('#modalContent').html(data);
                    $("#loadingIndicator2").addClass("d-none");
                },
                error: function() {
                    console.error('Gagal mengambil data kelas.');
                    $("#loadingIndicator2").addClass("d-none");
                }
            });
        }
        const url = "{{ route('searchKelas') }}";
    </script>
@endsection
