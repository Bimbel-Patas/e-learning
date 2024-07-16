@extends('layout.template.mainTemplate')

@section('container')
    @if (Auth()->user()->roles_id == 1)
        @include('menu.admin.adminHelper')
    @endif

    <div class="col-12 ps-4 pe-4 mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-white">
                <li class="breadcrumb-item"><a href="{{ route('viewPengajar') }}">Data Pengajar</a></li>
                <li class="breadcrumb-item active" aria-current="page">Tambah Pengajar</li>
            </ol>
        </nav>
    </div>

    <div class="ps-4 pe-4 mt-4 pt-4">
        <h2 class="display-6 fw-bold"><a href="javascript:history.back()"><button type="button"
                    class="btn btn-outline-secondary rounded-circle"><i class="fa-solid fa-arrow-left"></i></button></a>
            Tambah Pengajar</h2>

        <nav style="" aria-label="breadcrumb">
            <ol class="breadcrumb bg-light">
                <li class="breadcrumb-item text-info">Step 1</li>
                <li class="breadcrumb-item text-info">Step 2</li>
                <li class="breadcrumb-item" aria-current="page">Step 3</li>
            </ol>
        </nav>
    </div>

    <div class="">
        <div class="row ps-4 pe-4">
            <div class="col-12 col-lg-6">
                <div class="">
                    <div class=" p-4">
                        <form action="{{ route('validateDataPengajarKelas') }}" method="POST">
                            @csrf
                            <h4 class="fw-bold text-primary"><i class="fa-solid fa-pen"></i> Data Kelas Pengajar</h4>
                            <div class="bg-white rounded-2 p-4 mt-4">
                                {{-- Nama --}}
                                <div class="mb-3">
                                    <label for="nama" class="form-label">Nama :</label>
                                    <input type="text" class="form-control" id="nama" name="nama"
                                        placeholder="Aditya Kesuma, S.H, M.Kom" value="{{ old('nama', $data['nama']) }}"
                                        readonly required>
                                    @error('nama')
                                        <div class="text-danger small">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>


                                {{-- Email --}}
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email :</label>
                                    <input type="text" class="form-control" id="email" name="email"
                                        placeholder="email@gmail.com" value="{{ old('email', $data['email']) }}" readonly
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
                                            class="text-secondary small">(Optional)</span> :</label>
                                    <input type="text" class="form-control" id="noTelp" name="noTelp"
                                        placeholder="0851xxxxxxxx" value="{{ old('noTelp', $data['noTelp']) }}" readonly
                                        required>
                                    @error('noTelp')
                                        <div class="text-danger small">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                {{-- Password --}}
                                <div class="mb-3">
                                    <div class="row">
                                        <div class="col-12 mb-3">
                                            <div class="form-group">
                                                <label for="password">Password <span class="text-secondary small">(Min :
                                                        8)</span> :</label>
                                                <input class="form-control" id="password" name="password" type="password"
                                                    placeholder="****" value="{{ $data['password'] }}" readonly>
                                                @error('password')
                                                    <div class="text-danger small">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>

                                        <p class="small">Password di atas diisi oleh admin dan ditujukan kepada
                                            Pengajar. Setelahnya pengajar dapat mengganti password mereka jika
                                            diperlukan.</p>
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 text-center d-none d-lg-block">
                <img src="{{ url('/asset/img/teacher.png') }}" class="img-fluid" alt="">
            </div>
        </div>



        <div class="col-12 mt-4 bg-white rounded-2">
            <div class="mt-4">
                <div class="p-4">
                    <div class="row">
                        <div class="col-3">
                            <h3 class="fw-bold text-primary"><i class="fa-solid fa-book-bookmark"></i> Kelas & Mapel</h3>
                        </div>
                        <div class="col-9 small">
                            Kelas serta mapel dapat dikosongi dan dapat ditambahkan di kemudian waktu.
                        </div>
                        <div class="col-12">
                            <div class="row">
                                <div class="col-6 col-lg-5">
                                    <label for="kelas">Kelas :</label>
                                    <select class="form-select" id="kelas" aria-label="Default select example">
                                        <option value="kosong" selected>Pilih Kelas</option>
                                        @foreach ($dataKelas as $key)
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
                                        type="button">Tambah +</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Input tersembunyi untuk menerima data dari tabel -->
        <input type="hidden" name="data[kelas][]" id="kelasInput">

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

    <div class="mt-3">
        <button type="submit" class="btn-lg btn btn-primary w-100">Simpan dan Lanjutkan</button>
    </div>

    </form>
    <script>
        var url = "{{ route('searchKelasMapel') }}";
        var urlCek = "{{ route('cekKelasMapel') }}";
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
                            console.log(dataMapel);
                            $('#mapel').empty().append(
                                '<option value="" selected>Pilih Mapel</option>');

                            $.each(dataMapel, function(index, mapel) {
                                if (mapel.exist) {
                                    $('#mapel').append('<option disabled value="' +
                                        mapel.id + '">' +
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

    <script>
        var rowIndex = 0;
        var rowData = [];

        // Function to add a row to the table
        function addRowToTable() {
            var kelas = $('#kelas').val();
            var mapel = $('#mapel').val();

            var kelasHtml = $('#kelas option:selected').html();
            var mapelHtml = $('#mapel option:selected').html();

            var exists = rowData.some(function(row) {
                return row.kelas === kelas && row.mapel === mapel;
            });

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
                    mapel: mapel
                });

                // Clear the select fields
                $('#kelas').val('kosong');
                $('#mapel').empty().append('<option value="" selected>Pilih Mapel</option>');
                $('#mapel').prop('disabled', true);
                $('#btnTambah').prop('disabled', true);
            } else if (exists) {
                // Tampilkan pesan atau lakukan tindakan lain jika pasangan sudah ada
                alert('Kelas dan Mapel sudah ada dalam daftar');
            } else if (mapel == '') {
                alert('Pilih kelas/mapel terlebih dahulu');
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Add a click event listener to the "Tambah" button
            $('#btnTambah').on('click', function() {
                addRowToTable();
            });

            // Add a click event listener to delete buttons
            $('#tabelKelas').on('click', '.delete-btn', function() {
                var row = $(this).closest('tr');
                var rowIndex = row.index(); // Get the row index of the clicked row
                row.remove(); // Remove the row from the table

                // Remove the corresponding row data from the rowData array
                rowData.splice(rowIndex, 1);
            });

            // Add a submit event listener to the form
            $('form').on('submit', function() {
                // Set the hidden input values with the rowData JSON
                $('#kelasInput').val(JSON.stringify(rowData));
            });
        });
    </script>
@endsection
