@extends('layout.template.mainTemplate')

@section('container')
    @if (Auth()->user()->roles_id == 1)
        @include('menu.admin.adminHelper')
    @endif

    <div class="ps-4 pe-4 mt-4 pt-4">
        <h2 class="display-6 fw-bold">{{ $data['action'] }} Mapel</h2>

        <nav style="" aria-label="breadcrumb">
            <ol class="breadcrumb bg-light">
                <li class="breadcrumb-item text-info" aria-current="page">Step 1</li>
                <li class="breadcrumb-item text-info">Step 2</li>
            </ol>
        </nav>
    </div>

    <div class="w-100 mb-4">
        <div class="mx-auto bg-white border rounded-3 p-4 col-lg-6 col-sm-12">
            <div class="">
                @if ($data['action'] == 'Tambah')
                    <div class="text-center">
                        <h4>
                            Tambahkan Gambar untuk mapel ini
                        </h4>
                        <span class="small text-info">
                            (Opsional)
                        </span>
                    </div>
                    <div class="bg-body-tertiary rounded-2 mb-3 p-4">
                        <div class="p-4 col-12 row mb-4">
                            <div class="d-flex flex-column col-12 text-center">
                                <img src="/asset/icons/placeholder-image.png"
                                    class="image-previewer mx-auto image-class rounded-2 img-fluid" width="250"
                                    alt="">
                            </div>
                            <div class="col-12 my-auto mt-4">
                                <div class="mx-auto">
                                    <div class="input-group">
                                        <div class="custom-file">
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
                @else
                    <img src="{{ url('/asset/img/check.png') }}" class="w-50 img-fluid mb-2" alt="">
                @endif
                <div class="text-center">
                    <h1>Mapel Berhasil {{ $data['prompt'] }}</h1>
                    <i class="fa-regular fa-circle-check fa-shake fa-2xl text-success"></i>
                    <hr>
                    <div class="mx-auto" id="content">
                        @if ($data['action'] == 'Tambah')
                            <a href="{{ route('viewTambahMapel') }}"> <button
                                    class="mb-3 btn-lg btn btn-outline-primary w-50">Menambahkan
                                    Lagi</button></a>
                        @endif
                        <a href="{{ route('viewMapel') }}"><button class="btn-lg btn btn-primary w-50">Kembali</button></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ url('/asset/js/customJS/scrollToBottom.js') }}"></script>

    @if ($data['action'] == 'Tambah')
        <script src="{{ url('/asset/js/ijaboCropTool.min.js') }}"></script>
        <script>
            // Menggunakan plugin ijaboCropTool untuk mengelola gambar yang diunggah
            $('#file').ijaboCropTool({
                preview: '.image-previewer',
                setRatio: 2,
                allowedExtensions: ['jpg', 'jpeg', 'png'],
                buttonsText: ['Simpan', 'Batalkan'],
                buttonsColor: ['#30bf7d', '#ee5155', -15],
                processUrl: "{{ route('mapelTambahGambar', ['id' => $data['id']]) }}",
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
    @endif
@endsection
