<div class="" id="siswa-table">
    @if ($siswa->count() > 0)
        Jumlah Siswa : {{ $siswa->total() }}
        <div class="table-responsive col-12">
            <table id="table" class="table table-striped table-lg">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nama</th>
                        <th scope="col">Kelas</th>
                        <th scope="col">NIS</th>
                        <th scope="col">Akun</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($siswa as $key)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $key->name }}</td>
                            @if (isset($key->kelas->name))
                                <td>{{ $key->Kelas->name }}</td>
                            @else
                                <td>-</td>
                            @endif
                            <td>{{ $key->nis }}</td>
                            @if ($key->punya_akun)
                                <td><span class="badge badge-primary">Terdaftar</span></td>
                            @else
                                <td><span class="badge badge-secondary">Belum Terdaftar</span></td>
                            @endif
                            <td>
                                @if ($key->punya_akun)
                                    <a href="{{ route('viewProfileSiswa', ['token' => encrypt($key->user_id)]) }}"
                                        class="badge bg-info p-2 animate-btn-small"><i
                                            class="fa-regular fa-eye fa-xl"></i></a>
                                @endif
                                <a href="{{ route('viewUpdateDataSiswa', ['data_siswa' => $key->id]) }}"
                                    class="badge bg-info p-2 animate-btn-small"><i
                                        class="fa-solid fa-pen-to-square fa-xl"></i></a>
                                <a href="#table" class="badge bg-secondary p-2 animate-btn-small"><i
                                        class="fa-solid fa-xl fa-trash" data-bs-toggle="modal"
                                        data-bs-target="#deleteConfirmationModal"
                                        onclick="changeValue('{{ $key->id }}');"></i></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-center" id="pagination-container">
                {{ $siswa->links('pagination::bootstrap-5') }}
            </div>
        </div>
    @else
        <div class="text-center">
            <img src="{{ url('/asset/img/not-found.png') }}" alt="" class="img-fluid w-50" srcset="">
            <br>
            <Strong>Pencarian tidak ditemukan</Strong>
        </div>
    @endif
</div>

<script>
    var searchValue = $("#search").val();

    // Buat fungsi untuk menangani klik halaman paginasi dengan AJAX
    $('#pagination-container').on('click', '.pagination a', function(e) {
        e.preventDefault();
        var url = $(this).attr('href');

        $.ajax({
            url: url,
            method: 'GET',
            data: {
                search: searchValue
            },
            success: function(data) {
                $('#siswa-table').html(data);
                $('html, body').animate({
                    scrollTop: 0
                }, 'fast');
            }
        });
    });
</script>
