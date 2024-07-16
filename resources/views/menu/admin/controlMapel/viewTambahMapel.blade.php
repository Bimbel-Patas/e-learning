@extends('layout.template.mainTemplate')

@section('container')
    @if (Auth()->user()->roles_id == 1)
        @include('menu.admin.adminHelper')
    @endif

    <div class="col-12 ps-4 pe-4 mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-white">
                <li class="breadcrumb-item"><a href="{{ route('viewMapel') }}">Data Mapel</a></li>
                <li class="breadcrumb-item active" aria-current="page">Tambah Mapel</li>
            </ol>
        </nav>
    </div>

    <div class="ps-4 pe-4 mt-4  pt-4">
        <h2 class="display-6 fw-bold"><a href="{{ route('viewMapel') }}"><button type="button"
                    class="btn btn-outline-secondary rounded-circle">
                    <i class="fa-solid fa-arrow-left"></i></button></a> Tambah Mapel</h2>

        <nav style="" aria-label="breadcrumb">
            <ol class="breadcrumb bg-light">
                <li class="breadcrumb-item text-info" aria-current="page">Step 1</li>
                <li class="breadcrumb-item ">Step 2</li>
            </ol>
        </nav>
    </div>

    <div class="">
        <div class="row p-4  ">
            <h4 class="fw-bold text-primary"><i class="fa-solid fa-pen"></i> Data Mapel</h4>
            <div class="col-12 col-lg-6 bg-white rounded-2">
                <div class="mt-4">
                    <div class=" p-4">
                        <form action="{{ route('validateNamaMapel') }}" method="POST">
                            @csrf
                            {{-- Nama --}}
                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama</label>
                                <input type="text" class="form-control" id="nama" name="name"
                                    placeholder="Inputkan nama kelas... " value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="text-danger small">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="nama" class="form-label">Deskripsi <span
                                        class="small text-info">(Opsional)</span></label>
                                <textarea class="form-control" name="deskripsi" value="{{ old('deskripsi') }}" aria-label="With textarea"
                                    placeholder="Inputkan deskripsi mapel...">{{ old('deskripsi') }}</textarea>
                            </div>
                            <div class="">
                                <button type="submit" class="btn-lg btn btn-primary w-100">Simpan dan Lanjutkan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-6 text-center d-none d-lg-block">
                <img src="{{ url('/asset/img/exam.png') }}" class="img-fluid h-100" alt="">
            </div>
        </div>
    </div>

    <script src="{{ url('/asset/js/lottie.js') }}"></script>
    <script src="{{ url('/asset/js/customJS/simpleAnim.js') }}"></script>
@endsection
