@extends('layout.template.mainTemplate')

@section('container')
    @if (Auth()->user()->roles_id == 1)
        @include('menu.admin.adminHelper')
    @endif

    <div class="col-12 ps-4 pe-4 mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-white">
                <li class="breadcrumb-item"><a href="{{ route('viewPengajar') }}">Data Pengajar</a></li>
                <li class="breadcrumb-item active" aria-current="page">Update Pengajar</li>
            </ol>
        </nav>
    </div>

    <div class="ps-4 pe-4 mt-4 pt-4">
        <h2 class="display-6 fw-bold"><a href="{{ route('viewPengajar') }}"><button type="button"
                    class="btn btn-outline-secondary rounded-circle">
                    <i class="fa-solid fa-arrow-left"></i></button></a> Update Pengajar</h2>
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

                <div class="">
                    <div class="mt-4">
                        <div class=" ">
                            <div class="mb-4">
                                <h4 class="fw-bold text-primary"><i class="fa-solid fa-pen"></i> Data Pengajar</h4>
                            </div>
                            <form action="{{ route('updatePengajar') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="bg-white rounded-2 mb-3">
                                    <div class=" rounded-2 mb-3 p-4 ">
                                        <div class="p-4 col-12 row mb-4">
                                            <div class="d-flex flex-column col-12 text-center">
                                                @if ($user->gambar == null)
                                                    <img src="/asset/icons/placeholder-image.png"
                                                        class="image-previewer mx-auto image-class rounded-circle img-fluid"
                                                        width="250" alt="">
                                                @else
                                                    <img src="{{ url('storage/user-images/' . $user->gambar) }}"
                                                        class="image-previewer mx-auto image-class rounded-circle img-fluid"
                                                        width="250" alt="">
                                                @endif
                                            </div>
                                            <div class="col-12 my-auto mt-4">
                                                <div class="mx-auto">
                                                    <div class="input-group">
                                                        <div class="custom-file ">
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
                                        <label for="nama" class="form-label">Nama : </label>
                                        <input type="text" class="form-control" id="nama" name="nama"
                                            placeholder="Aditya Kesuma, S.H, M.Kom" value="{{ old('nama', $user['name']) }}"
                                            required>
                                        @error('nama')
                                            <div class="text-danger small">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    {{-- Email --}}
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email : </label>
                                        <input type="text" class="form-control" id="email" name="email"
                                            placeholder="email@gmail.com" value="{{ old('email', $user['email']) }}"
                                            required>
                                        @error('email')
                                            <div class="text-danger small">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    {{-- no telepon --}}
                                    <div class="mb-3">
                                        <label for="noTelp" class="form-label">Nomor Telepon <span
                                                class="text-secondary small">(Optional)</span> : </label>
                                        <input type="number" class="form-control" id="noTelp" name="noTelp"
                                            placeholder="0851xxxxxxxx" value="{{ old('noTelp', $contact['no_telp']) }}">
                                        @error('noTelp')
                                            <div class="text-danger small">
                                                {{ $message }}
                                            </div>
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
                                                                class="text-secondary small">(Min
                                                                :
                                                                8)</span> :
                                                        </label>
                                                        <input class="form-control" id="password" name="password"
                                                            type="password" placeholder="****" value="">
                                                        @error('password')
                                                            <div class="text-danger small">
                                                                {{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-6 mb-3">
                                                    <div class="form-group">
                                                        <label for="password">Confirm Password Baru <span
                                                                class="text-secondary small"></span> :
                                                        </label>
                                                        <input class="form-control" id="confirm-password" name="password"
                                                            type="password" placeholder="****" value="">
                                                        @error('password')
                                                            <div class="text-danger small">
                                                                {{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <p class="small">Isi jika ingin mengganti password untuk pengajar.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6 text-center d-none d-lg-block">
                <img src="{{ url('/asset/img/teacher.png') }}" class="img-fluid w-100" alt="">
            </div>

            <div class="col-12 mt-4 bg-white rounded-2">
                <div class="mt-4">
                    <div class="p-4">
                        <div class="row">
                            <div class="col-lg-3 col-12">
                                <h4 class="fw-bold text-primary"><i class=" fa-solid fa-book-bookmark"></i>
                                    Mengajar</h4>
                            </div>
                            <div class="col-lg-9 col-12 small">
                                Kelas serta mapel dapat dikosongi dan dapat ditambahkan dikemudian waktu.
                            </div>
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-6 col-lg-5">
                                        <label for="kelas">Kelas :</label>
                                        <select class="form-select" id="kelas" aria-label="Default select example">
                                            <option value="kosong" selected>Pilih Kelas</option>
                                            @foreach ($kelas as $key)
                                                <option value="{{ $key->id }}">{{ $key->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-6 col-lg-5">
                                        <label for="mapel" class="form-label">Mapel :</label>
                                        <select class="form-select" id="mapel" aria-label="Default select example"
                                            disabled>
                                            <option value="" selected>Pilih Kelas terlebih dahulu</option>
                                        </select>
                                        <div id="loading" style="display: none;">Loading...</div>
                                    </div>
                                    <div class="col-12 col-lg-2">
                                        <label for="" class="form-label"></label>
                                        <button disabled class="d-block mt-2 btn btn-primary w-100" id="btnTambah"
                                            type="button">Tambah
                                            +</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 mt-4 bg-white rounded-2">
                <div class="mt-4">
                    <div class="p-4">
                        <div class="">
                            <table id="tabelKelas" class="table">
                                <thead>
                                    <tr>
                                        <th>Nama Kelas</th>
                                        <th>Nama Mapel</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Data mapel akan ditambahkan oleh JavaScript -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="">
            <button type="submit" class="btn-lg btn btn-primary w-100">Simpan dan Lanjutkan</button>
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

                    // Mengganti URL_API dengan URL sesuai dengan API Anda
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
                                        mapel.id +
                                        '">' +
                                        mapel.name + '</option>');
                                } else {
                                    $('#mapel').append('<option value="' + mapel.id +
                                        '">' +
                                        mapel.name + '</option>');
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
        // Menggunakan plugin ijaboCropTool untuk mengelola gambar yang diunggah
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
    <script>
        var rowIndex = 0;
        var rowData = [];

        @if ($mapelEnrolled > 0)
            @foreach ($mapelEnrolled as $key)
                rowIndex++;
                @php
                    $kelas = DB::select('SELECT name FROM kelas WHERE id = ' . $key->kelas_id);
                    $mapel = DB::select('SELECT name FROM mapels WHERE id = ' . $key->mapel_id);
                @endphp;

                var newRow = '<tr>' +
                    '<td>' + '{{ $kelas[0]->name }}' + '</td>' +
                    '<td>' + '{{ $mapel[0]->name }}' + '</td>' +
                    '<td><button type="button" class="btn btn-danger delete-btn">Delete</button></td>' +
                    '</tr>';
                $('#tabelKelas tbody').append(newRow);

                rowData.push({
                    kelas: '{{ $key->kelas_id }}',
                    mapel: '{{ $key->mapel_id }}'
                });

                console.log(rowData);
            @endforeach
        @endif

        // Function to add a row to the table
        function addRowToTable() {
            var kelas = $('#kelas').val();
            var mapel = $('#mapel').val();
            var valid = 0;

            var exists = rowData.some(function(row) {
                return row.kelas === kelas && row.mapel === mapel;
            });

            if (mapel == '') {
                alert('Silahkan pilih mapel terlebih dahulu!');
            } else {
                $.ajax({
                    url: urlCek,
                    method: 'GET',
                    data: {
                        kelasId: kelas,
                        mapelId: mapel,
                    },
                    success: function(dataMapel) {
                        console.log(dataMapel.response)
                        console.log("response : " + dataMapel.response);
                        if (dataMapel.response == 1) {
                            valid = 0;
                            return alert('Mapel sudah memiliki pengajar');
                        } else {
                            console.log("here");
                            valid = 1;
                            console.log("valid : " + valid);
                        }

                        if (valid) {
                            console.log("Oke Sudah Valid");
                            var kelasHtml = $('#kelas option:selected').html();
                            var mapelHtml = $('#mapel option:selected').html();

                            if (kelas !== 'kosong' && mapel !== '' && !exists) {
                                rowIndex++;
                                // Create a new row and append it to the table
                                var newRow = '<tr>' +
                                    '<td>' + kelasHtml + '</td>' +
                                    '<td>' + mapelHtml + '</td>' +
                                    '<td><button type="button" class="btn btn-danger delete-btn">Delete</button></td>' +
                                    '</tr>';
                                $('#tabelKelas tbody').append(newRow);

                                rowData.push({
                                    kelas: kelas,
                                    mapel: mapel,
                                });

                                tambahEditorAccess(kelas, mapel, userId);

                                // Clear the select fields
                                $('#kelas').val('kosong');
                                $('#mapel').empty().append(
                                    '<option value="" selected>Pilih kelas terlebih dahulu</option>'
                                );
                                $('#mapel').prop('disabled', true);
                                $('#btnTambah').prop('disabled', true);

                                console.log(rowData);
                            } else if (exists) {
                                alert('Kelas dan Mapel sudah ada dalam daftar.');
                            }
                        }
                    },
                    error: function(xhr, status, error) {
                        return alert(error);
                    }
                });
            }
        }

        function tambahEditorAccess(kelasId, mapelId, userId) {
            $.ajax({
                url: urlTambah,
                method: 'POST',
                data: {
                    kelasId: kelasId,
                    userId: userId,
                    mapelId: mapelId,
                    _token: '{{ csrf_token() }}'
                },
                success: function(dataMapel) {
                    alert(dataMapel.response);
                }
            })
        }

        function deleteEditorAccess(kelasId, mapelId) {
            console.log(kelasId + " " + mapelId);
            $.ajax({
                url: urlDelete,
                method: 'POST',
                data: {
                    kelasId: kelasId,
                    mapelId: mapelId,
                    _token: '{{ csrf_token() }}'
                },
                success: function(dataMapel) {
                    alert(dataMapel.response);
                },
                failed: function(dataMapel) {
                    console.log(dataMapel.response);
                },
            })
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Add a click event listener to the "Tambah" button
            $('#btnTambah').on('click', function() {
                addRowToTable();
            });

            // Add a click event listener to delete buttons
            // Add a click event listener to delete buttons
            $('#tabelKelas').on('click', '.delete-btn', function() {
                // Get the row index
                var rowIndexToDelete = $(this).closest('tr').index();

                // Delete editor access
                var kelasId = rowData[rowIndexToDelete].kelas;
                var mapelId = rowData[rowIndexToDelete].mapel;
                deleteEditorAccess(kelasId, mapelId);

                // Remove the row from the table
                $(this).closest('tr').remove();

                // Remove the corresponding entry from the rowData array
                rowData.splice(rowIndexToDelete, 1);

                // Decrement the rowIndex variable
                rowIndex--;
            });

        });
    </script>
@endsection
