@extends('layout.template.mainTemplate')

@section('container')
    {{-- Cek peran pengguna --}}
    @if (Auth()->user()->roles_id == 1)
        @include('menu.admin.adminHelper')
    @endif

    {{-- Navigasi Breadcrumb --}}
    <div class="col-12 ps-4 pe-4 mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-white">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item">
                    <a
                        href="{{ route('viewKelasMapel', ['mapel' => $mapel['id'], 'token' => encrypt($kelas['id']), 'mapel_id' => $mapel['id']]) }}">
                        {{ $mapel['name'] }}
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page"> Materi</li>
            </ol>
        </nav>
    </div>

    {{-- Judul Halaman --}}
    <div class="ps-4 pe-4 mt-4  pt-4">
        <h2 class="display-6 fw-bold">
            <a
                href="{{ route('viewKelasMapel', ['mapel' => $mapel['id'], 'token' => encrypt($kelas['id']), 'mapel_id' => $mapel['id']]) }}">
                <button type="button" class="btn btn-outline-secondary rounded-circle">
                    <i class="fa-solid fa-arrow-left"></i>
                </button>
            </a> Materi
        </h2>
    </div>

    {{-- Baris utama --}}
    <div class="col-12 ps-4 pe-4 mb-4">
        <div class="row">
            {{-- Bagian Kiri --}}
            <div class="col-xl-9 col-lg-12 col-md-12">
                <div class="row">
                    {{-- Tampilan Materi --}}
                    <div class="col-12 mb-4">
                        <div class="p-4 bg-white rounded-4">
                            <div class="h-100 p-4">
                                <h2 class="fw-bold text-primary">
                                    {{ $materi->name }}
                                    @if ($materi->isHidden == 1)
                                        <i class="fa-solid fa-lock fa-bounce text-danger"></i>
                                    @endif
                                </h2>
                                <hr>
                                <p>
                                    {!! $materi->content !!}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Bagian Kanan --}}
            <div class="col-xl-3 col-lg-12 col-md-12">
                {{-- Info Pengajar --}}
                <div class="mb-4 p-4 bg-white rounded-4">
                    <div class="h-100 p-4">
                        <h4 class="fw-bold mb-2">Pengajar</h4>
                        <hr>
                        <div class="row">
                            <div class="col-lg-4 d-none d-lg-none d-xl-block">
                                @if ($editor->gambar == null)
                                    <img src="/asset/icons/profile-women.svg" class="rounded-circle  img-fluid"
                                        alt="">
                                @else
                                    <img src="{{ asset('storage/user-images/' . $editor->gambar) }}" alt="placeholder"
                                        class="rounded-circle  img-fluid">
                                @endif
                            </div>
                            <div class="col-lg-8">
                                <a href="{{ route('viewProfilePengajar', ['token' => encrypt($editor['id'])]) }}">
                                    {{ $editor['name'] }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Daftar Materi --}}
                <div class="mb-4 p-4 bg-white rounded-4">
                    <div class="h-100 p-4">
                        <h4 class="fw-bold mb-2">List Materi</h4>
                        <hr>
                        <ul class="list-group">
                            @foreach ($materiAll as $key)
                                @if ($key->isHidden != 1 || Auth()->User()->roles_id == 2)
                                    {{-- Disabled link karena active --}}
                                    @if ($materi['id'] != $key->id)
                                        <a
                                            href="{{ route('viewMateri', ['token' => encrypt($key->id), 'kelasMapelId' => encrypt($materi['kelas_mapel_id']), 'mapelId' => $mapel['id']]) }}">
                                    @endif
                                    <li class="list-group-item  @if ($materi['id'] == $key->id) active @endif">
                                        {{ $key->name }} @if ($key->isHidden == 1)
                                            <i class="fa-solid fa-lock fa-bounce text-danger"></i>
                                        @endif
                                    </li>
                                    @if ($materi['id'] != $key->id)
                                        </a>
                                    @endif
                                @endif
                            @endforeach
                        </ul>
                    </div>
                </div>

                {{-- Daftar File --}}
                <div class="mb-4 p-4 bg-white rounded-4">
                    <div class="h-100 p-4">
                        <h4 class="fw-bold mb-2">Files</h4>
                        <hr>
                        @if (count($materi->MateriFile) > 0)
                            <ul class="list-group">
                                @foreach ($materi->MateriFile as $key)
                                    <a href="{{ route('getFile', ['namaFile' => $key->file]) }}">
                                        <li class="list-group-item">
                                            @if (Str::endsWith($key->file, ['.jpg', '.jpeg', '.png', '.gif']))
                                                <i class="fa-solid fa-image"></i>
                                            @elseif (Str::endsWith($key->file, ['.mp4', '.avi', '.mov']))
                                                <i class="fa-solid fa-video"></i>
                                            @elseif (Str::endsWith($key->file, ['.pdf']))
                                                <i class="fa-solid fa-file-pdf"></i>
                                            @elseif (Str::endsWith($key->file, ['.doc', '.docx']))
                                                <i class="fa-solid fa-file-word"></i>
                                            @elseif (Str::endsWith($key->file, ['.ppt', '.pptx']))
                                                <i class="fa-solid fa-file-powerpoint"></i>
                                            @elseif (Str::endsWith($key->file, ['.xls', '.xlsx']))
                                                <i class="fa-solid fa-file-excel"></i>
                                            @elseif (Str::endsWith($key->file, ['.txt']))
                                                <i class="fa-solid fa-file-alt"></i>
                                            @elseif (Str::endsWith($key->file, ['.mp3']))
                                                <i class="fa-solid fa-music"></i>
                                            @else
                                                <i class="fa-solid fa-file"></i>
                                            @endif
                                            {{ Str::substr($key->file, 5, 20) }}
                                        </li>
                                    </a>
                                @endforeach
                            @else
                                <span class="small">(Tidak ada file untuk materi ini)</span>
                        @endif

                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Script untuk mengatur gambar agar responsif --}}
    <script>
        var img = document.querySelectorAll('img');

        img.forEach(function(element) {
            element.classList.add('img-fluid');
        });
    </script>

    {{-- Script tambahan jika diperlukan --}}
    <script src="{{ url('/asset/js/lottie.js') }}"></script>
    <script src="{{ url('/asset/js/customJS/simpleAnim.js') }}"></script>
    <script></script>
@endsection
