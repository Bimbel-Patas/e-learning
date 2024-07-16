@extends('layout.template.mainTemplate')

@section('container')
    @if (Auth()->user()->roles_id == 1)
        @include('menu.admin.adminHelper')
    @endif

    <div class="col-12 ps-4 pe-4 mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-white">
                <li class="breadcrumb-item"><a href="{{ route('viewMapel') }}">Data Mapel</a></li>
                <li class="breadcrumb-item active" aria-current="page">Update Mapel</li>
            </ol>
        </nav>
    </div>

    <div class="ps-4 pe-4 mt-4 pt-4">
        <h2 class="display-6 fw-bold"><a href="{{ route('viewMapel') }}"><button type="button"
                    class="btn btn-outline-secondary rounded-circle"><i class="fa-solid fa-arrow-left"></i></button></a>
            Update Mapel : {{ $mapel->name }}</h2>
    </div>

    <div class="">
        <div class="row p-4">
            <h4 class="fw-bold text-primary"><i class="fa-solid fa-pen"></i> Data Mapel</h4>
            <div class="col-12 col-lg-6 bg-white rounded-2">

                @if (session()->has('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="mt-4">
                    <div class=" p-4">
                        <form action="{{ route('updateMapel') }}" method="POST">
                            @csrf
                            <div class="bg-body-tertiary rounded-2 mb-3 p-4">
                                <div class="p-4 col-12 row mb-4">
                                    <div class="d-flex flex-column col-12 text-center">
                                        @if ($mapel->gambar == null)
                                            <img src="/asset/icons/placeholder-image.png"
                                                class="image-previewer mx-auto image-class rounded-2 img-fluid"
                                                width="250" alt="">
                                        @else
                                            <img src="{{ url('storage/mapel/' . $mapel->gambar) }}"
                                                class="image-previewer mx-auto image-class rounded-2 img-fluid"
                                                width="250" alt="">
                                        @endif
                                    </div>
                                    <div class="col-12 my-auto mt-4">
                                        <div class="mx-auto">
                                            <div class="input-group">
                                                <div class="custom-file ">
                                                    <input type="file" class="custom-file-input d-none" name="file"
                                                        id="file">
                                                    <label class="btn btn-outline-info text-center mx-auto mt-4"
                                                        for="file">Upload Gambar</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Nama --}}
                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama</label>
                                <input type="hidden" name="id" value="{{ $mapel->id }}">
                                <input type="text" class="form-control" id="nama" name="nama"
                                    placeholder="Inputkan nama mapel... " value="{{ old('nama', $mapel->name) }}" required>
                                @error('nama')
                                    <div class="text-danger small">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="nama" class="form-label">Deskripsi <span
                                        class="small text-info">(Opsional)</span></label>
                                <textarea class="form-control" name="deskripsi" placeholder="Inputkan deskripsi mapel...">{{ old('deskripsi', $mapel->deskripsi) }}</textarea>
                            </div>

                            <div class="">
                                <button type="submit" class="btn-lg btn btn-primary w-100">Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-6 text-center d-none d-lg-block">
                <img src="{{ url('/asset/img/exam.png') }}" class="img-fluid w-100" alt="">
            </div>
        </div>
    </div>

    <script src="{{ url('/asset/js/ijaboCropTool.min.js') }}"></script>
    <script>
        // Menggunakan plugin ijaboCropTool untuk mengelola gambar yang diunggah
        $('#file').ijaboCropTool({
            preview: '.image-previewer',
            setRatio: 2,
            allowedExtensions: ['jpg', 'jpeg', 'png'],
            buttonsText: ['Simpan', 'Batalkan'],
            buttonsColor: ['#30bf7d', '#ee5155', -15],
            processUrl: "{{ route('mapelTambahGambar', ['id' => $mapel->id]) }}",
            withCSRF: ['_token', '{{ csrf_token() }}'],
            resetFileInput: false,
            onSuccess: function(message, element, status) {
                alert(message);
            },
            onError: function(message, element, status) {
                alert(message);
            }
        });
    </script>
@endsection
