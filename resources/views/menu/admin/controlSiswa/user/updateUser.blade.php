@extends('layout.template.mainTemplate')

@section('container')
    @if (Auth()->user()->roles_id == 1)
        @include('menu.admin.adminHelper')
    @endif

    <div class="col-12 ps-4 pe-4 mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-white">
                <li class="breadcrumb-item"><a href="{{ route('viewSiswa') }}">Data Siswa</a></li>
                <li class="breadcrumb-item active" aria-current="page">Update Siswa</li>
            </ol>
        </nav>
    </div>

    <div class="ps-4 pe-4 mt-4 pt-4">
        <h2 class="fw-bold display-6">
            <a href="{{ route('viewSiswa') }}">
                <button type="button" class="btn btn-outline-secondary rounded-circle">
                    <i class="fa-solid fa-arrow-left"></i>
                </button>
            </a>
            <i class="fa-solid fa-pen-to-square"></i> Update Siswa
        </h2>
    </div>

    <div class="">
        <div class="row p-4">
            <div class="col-12 col-lg-6">
                @if (session()->has('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div>
                    <div class="mt-4">
                        <div>
                            <div class="mb-4">
                                <h3><i class="fa-solid fa-user"></i> Data Profil</h3>
                            </div>
                            <form action="{{ route('updateUserSiswa') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="bg-white rounded-2 mb-3">
                                    <div class="rounded-2 mb-3 p-4">
                                        <div class="p-4 col-12 row mb-4">
                                            <div class="d-flex flex-column col-12 text-center">
                                                @if ($user->gambar == null)
                                                    <img src="/asset/icons/placeholder-image.png"
                                                        class="image-previewer mx-auto image-class rounded-circle img-fluid"
                                                        width="250" alt="">
                                                @else
                                                    <img src="{{ asset('storage/user-images/' . $user->gambar) }}"
                                                        class="image-previewer mx-auto image-class rounded-circle img-fluid"
                                                        width="250" alt="">
                                                @endif
                                            </div>
                                            <div class="col-12 my-auto mt-4">
                                                <div class="mx-auto">
                                                    <div class="input-group">
                                                        <div class="custom-file">
                                                            <input type="file" class="custom-file-input d-none"
                                                                name="file" id="file">
                                                            <label class="btn btn-outline-info text-center mx-auto mt-4"
                                                                for="file">Upload Gambar</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Gambar --}}
                                <div class="bg-white rounded-2 mb-3 p-4">
                                    <input type="hidden" name="id" value="{{ $user['id'] }}">
                                    {{-- Nama --}}
                                    <div class="mb-3">
                                        <label for="nama" class="form-label">Nama:</label>
                                        <input type="text" class="form-control" id="nama" name="nama"
                                            placeholder="Aditya Kesuma, S.H, M.Kom" value="{{ old('nama', $user['name']) }}"
                                            required>
                                        @error('nama')
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Email --}}
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email:</label>
                                        <input type="text" class="form-control" id="email" name="email"
                                            placeholder="email@gmail.com" value="{{ old('email', $user['email']) }}"
                                            required>
                                        @error('email')
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- no telepon --}}
                                    <div class="mb-3">
                                        <label for="noTelp" class="form-label">Nomor Telepon <span
                                                class="text-secondary small">(Optional)</span>:</label>
                                        <input type="number" class="form-control" id="noTelp" name="noTelp"
                                            placeholder="0851xxxxxxxx" value="{{ old('noTelp', $contact['no_telp']) }}">
                                        @error('noTelp')
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="bg-body-tertiary rounded-4 p-4">
                                        <div class="my-4">
                                            <h4><i class="fa-solid fa-lock"></i> Ganti Password</h4>
                                        </div>

                                        {{-- Password --}}
                                        <div class="mb-3">
                                            <div class="row">
                                                <div class="col-6 mb-3">
                                                    <div class="form-group">
                                                        <label for="password">Password Baru <span
                                                                class="text-secondary small">(Min: 8)</span>:</label>
                                                        <input class="form-control" id="password" name="password"
                                                            type="password" placeholder="****" value="">
                                                        @error('password')
                                                            <div class="text-danger small">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-6 mb-3">
                                                    <div class="form-group">
                                                        <label for="password">Confirm Password Baru:</label>
                                                        <input class="form-control" id="password" name="password"
                                                            type="password" placeholder="****" value="">
                                                        @error('password')
                                                            <div class="text-danger small">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <p class="small">Isi jika ingin mengganti password untuk user.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn-lg btn btn-primary w-100">Simpan dan Lanjutkan</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6 text-center d-none d-lg-block">
                <img src="{{ url('/asset/img/teacher.png') }}" class="img-fluid w-100" alt="">
            </div>

        </div>
    </div>
    </form>

    <script>
        var url = "{{ route('searchKelasMapel') }}";
        var urlCek = "{{ route('cekKelasMapel') }}";
        var urlTambah = "{{ route('tambahEditorAccess') }}";
        var urlDelete = "{{ route('deleteEditorAccess') }}";
        var userId = "{{ $user->id }}";
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $('#kelas').on('change', function() {
                if ($('#kelas').val() != 'kosong') {
                    $('#mapel').prop('disabled', true);
                    $('#loading').show();

                    const selectedKelasId = $('#kelas').val();
                    var apiUrl = url;

                    $.ajax({
                        url: apiUrl,
                        method: 'GET',
                        data: {
                            kelasId: selectedKelasId
                        },
                        success: function(dataMapel) {
                            $('#mapel').empty().append(
                                '<option value="" selected>Pilih Mapel</option>');

                            $.each(dataMapel, function(index, mapel) {
                                if (mapel.exist) {
                                    $('#mapel').append('<option disabled value="' +
                                        mapel.id + '">' + mapel.name + '</option>');
                                } else {
                                    $('#mapel').append('<option value="' + mapel.id +
                                        '">' + mapel.name + '</option>');
                                }
                            });

                            $('#mapel').prop('disabled', false);
                            $('#loading').hide();
                            $('#btnTambah').prop('disabled', false);
                        },
                        error: function(xhr, status, error) {
                            console.error('Terjadi kesalahan:', error);
                            $('#mapel').prop('disabled', true);
                            $('#loading').hide();
                            $('#btnTambah').prop('disabled', true);
                        }
                    });
                } else {
                    $('#mapel').prop('disabled', true).append(
                        '<option value="" selected>Pilih Kelas terlebih dahulu</option>');
                    $('#btnTambah').prop('disabled', true);
                }
            });
        });
    </script>

    <script src="{{ url('/asset/js/ijaboCropTool.min.js') }}"></script>

    <script>
        $('#file').ijaboCropTool({
            preview: '.image-previewer',
            setRatio: 1,
            allowedExtensions: ['jpg', 'jpeg', 'png'],
            buttonsText: ['Simpan', 'Batalkan'],
            buttonsColor: ['#30bf7d', '#ee5155', -15],
            processUrl: "{{ route('cropImageUser', ['id' => $user->id]) }}",
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
