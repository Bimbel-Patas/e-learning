@extends('layout.template.mainTemplate')

@section('container')
    @if (Auth()->user()->roles_id == 1)
        @include('menu.admin.adminHelper')
    @endif

    <div class="col-12 ps-4 pe-4 mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-white">
                <li class="breadcrumb-item"><a href="{{ route('viewKelas') }}">Data Kelas</a></li>
                <li class="breadcrumb-item active" aria-current="page">Update Kelas</li>
            </ol>
        </nav>
    </div>

    <div class="ps-4 pe-4  pt-4">
        <h2 class="fw-bold display-6"><a href="{{ route('viewKelas') }}"><button type="button"
                    class="btn btn-outline-secondary rounded-circle">
                    <i class="fa-solid fa-arrow-left"></i></button></a> Update Kelas : {{ $kelas->name }}
        </h2>
    </div>

    <div class="">
        <div class="row p-4">
            <h4 class="fw-bold text-primary"><i class="fa-solid fa-pen"></i> Data Kelas</h4>
            <div class="col-12 col-lg-6">

                @if (session()->has('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class=" bg-white rounded-2">
                    <div class=" p-4">

                        <form action="{{ route('updateKelas') }}" method="POST">
                            @csrf
                            {{-- Nama --}}
                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama</label>
                                <input type="hidden" name="id" value="{{ $kelas->id }}">
                                <input type="text" class="form-control" id="nama" name="nama"
                                    placeholder="Inputkan nama kelas... " value="{{ old('nama', $kelas->name) }}" required>
                                @error('nama')
                                    <div class="text-danger small">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- Mapel --}}
                            <div class="mb-3">
                                <label for="mapel" class="form-label">Mapel</label>
                                <div class="row">
                                    <div class="col-8">
                                        <select class="form-select" id="mapel" aria-label="Default select example">
                                            <option value="" selected>Pilih Mapel</option>
                                            @foreach ($dataMapel as $key)
                                                <option value="{{ $key->id }}">{{ $key->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-4">
                                        <button class="btn btn-primary" type="button" id="tambahMapel">
                                            Tambah Mapel
                                        </button>
                                    </div>
                                </div>
                            </div>

                            {{-- Bagian tabel untuk menampilkan mapel yang ditambahkan --}}
                            <table id="tabelMapel" class="table">
                                <thead>
                                    <tr>
                                        <th>Nama Mapel</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Data mapel akan ditambahkan oleh JavaScript -->
                                    @foreach ($kelasMapel as $key)
                                        <tr>
                                            <td>{{ $key->name }}</td>
                                            <td><button type="button" class="btn btn-danger delete-mapel">X</button></td>
                                            <input type="hidden" name="mapels[]" value="{{ $key->id }}">
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <div class="">
                                <button type="submit" class="btn-lg btn btn-primary w-100">Simpan Perubahan</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>

            <div class="col-6 text-center d-none d-lg-block">
                <img src="{{ url('/asset/img/office.png') }}" class="img-fluid w-100" alt="">
            </div>
        </div>
    </div>

    <script src="{{ url('/asset/js/lottie.js') }}"></script>
    <script src="{{ url('/asset/js/customJS/simpleAnim.js') }}"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const tambahMapelButton = document.getElementById("tambahMapel");
            const mapelDropdown = document.getElementById("mapel");
            const tabelMapelBody = document.querySelector("#tabelMapel tbody");

            tambahMapelButton.addEventListener("click", function() {
                const selectedMapel = mapelDropdown.value;
                const selectedMapelText = mapelDropdown.options[mapelDropdown.selectedIndex].text;

                const existingMapels = Array.from(tabelMapelBody.querySelectorAll("td:nth-child(1)")).map(
                    cell => cell.textContent);
                if (existingMapels.includes(selectedMapelText)) {
                    alert("Mapel sudah ada dalam tabel!");
                    return;
                }

                if (selectedMapel) {
                    const newRow = document.createElement("tr");
                    const newCell = document.createElement("td");
                    newCell.textContent = selectedMapelText;
                    newRow.appendChild(newCell);

                    const deleteCell = document.createElement("td");
                    const deleteButton = document.createElement("button");
                    deleteButton.type = "button";
                    deleteButton.className = "btn btn-danger delete-mapel";
                    deleteButton.innerHTML = '<i class="fas fa-times"></i>';
                    deleteCell.appendChild(deleteButton);
                    newRow.appendChild(deleteCell);

                    tabelMapelBody.appendChild(newRow);

                    // Tambahkan input field tersembunyi untuk setiap data mapel yang ditambahkan
                    const hiddenInput = document.createElement("input");
                    hiddenInput.type = "hidden";
                    hiddenInput.name = "mapels[]";
                    hiddenInput.value = selectedMapel;
                    newRow.appendChild(hiddenInput);
                }
            });

            // Fungsi untuk menghapus baris mapel
            tabelMapelBody.addEventListener("click", function(event) {
                if (event.target.classList.contains("delete-mapel")) {
                    console.log('asdase');
                    const row = event.target.closest("tr");
                    const hiddenInput = row.querySelector("input[name='mapels[]']");

                    if (hiddenInput) {
                        hiddenInput.remove();
                    }

                    row.remove();
                }
            });
        });
    </script>
@endsection
