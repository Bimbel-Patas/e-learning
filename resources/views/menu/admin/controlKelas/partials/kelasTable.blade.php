<div id="kelas-table">
    @if ($kelas->count() > 0)
        Jumlah Kelas : {{ $kelas->total() }}
        <div class="table-responsive col-12">
            <table id="table" class="table table-striped table-lg">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nama</th>
                        <th scope="col">Jumlah Mapel</th>
                        <th scope="col">Jumlah Siswa</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($kelas as $key)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $key->name }}</td>
                            <td>{{ $key->KelasMapel ? $key->KelasMapel->count() : 0 }}</td>
                            <td>0</td>
                            <td>
                                <a href="#table" data-bs-toggle="modal" data-bs-target="#deleteConfirmationModal"
                                    data-kelasid="{{ $key->id }}" class="badge bg-info p-2 animate-btn-small"><i
                                        class="fa-regular fa-eye fa-xl mb-1"
                                        onclick="changeValue('{{ $key->id }}', 'view')"></i></a>
                                <a href="{{ route('viewUpdateKelas', ['kelas' => $key->id]) }}"
                                    class="badge bg-secondary p-2 mb-1 animate-btn-small"><i
                                        class="fa-solid fa-pen-to-square fa-xl mb-1"></i></a>
                                <a href="#table" class="badge bg-secondary p-2 animate-btn-small"><i
                                        class="fa-solid fa-xl fa-trash" data-bs-toggle="modal"
                                        data-bs-target="#deleteConfirmationModal"
                                        onclick="changeValue('{{ $key->id }}', 'delete');"></i></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-center" id="pagination-container">
                {{ $kelas->links('pagination::bootstrap-5') }}
            </div>
        </div>
    @else
        <div class="text-center">
            <img src="{{ url('/asset/img/not-found.png') }}" alt="" class="img-fluid w-50" srcset="">
            <br>
            <strong>Pencarian tidak ditemukan</strong>
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
                search: searchValue,
            },
            success: function(data) {
                $('#kelas-table').html(data);
                $('html, body').animate({
                    scrollTop: 0
                }, 'fast');
            }
        });
    });
</script>
