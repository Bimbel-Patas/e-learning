<div id="pengajar-table">
    @if ($pengajar->count() > 0)
        Jumlah Pengajar : {{ $pengajar->total() }}
        <div class="table-responsive col-12 ">
            <table class="table table-striped table-lg ">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">-</th>
                        <th scope="col">Nama</th>
                        <th scope="col">Mengajar</th>
                        <th scope="col">Mapel</th>
                        <th scope="col">Email</th>
                        <th scope="col">No Telp</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach ($pengajar as $key)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                @if (!$key->gambar)
                                    <img src="/asset/icons/profile-women.svg" width="50" alt=""
                                        class="rounded-circle">
                                @else
                                    <img src="{{ asset('storage/user-images/' . $key->gambar) }}" width="50"
                                        class="rounded-circle" alt="">
                                @endif
                            </td>
                            <td>{{ $key->name }}</td>
                            @if ($key->EditorAccess)
                                <td>{{ count($key->EditorAccess) }} Kelas</td>
                            @else
                                <td>0 Kelas</td>
                            @endif
                            <td>Mapel</td>
                            <td>{{ Str::substr($key->email, 0, 7) }}...</td>
                            <td>{{ $key->Contact->no_telp }} </td>
                            <td>
                                <a href="{{ route('viewProfileAdmin', ['token' => encrypt($key->id)]) }}"
                                    class="badge bg-info p-2 mb-1 animate-btn-small"><i
                                        class="fa-regular fa-eye fa-xl"></i></a>
                                <a href="{{ route('viewUpdatePengajar', ['token' => encrypt($key->id)]) }}"
                                    class="badge bg-secondary mb-1 p-2 animate-btn-small"><i
                                        class="fa-solid fa-pen-to-square fa-xl"></i></a>
                                <a href="#table" class="badge bg-secondary mb-1 p-2 animate-btn-small"><i
                                        class="fa-solid fa-xl fa-trash" data-bs-toggle="modal"
                                        data-bs-target="#deleteConfirmationModal"
                                        onclick="changeValue('{{ $key->id }}');"></i></a>
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
            <div class="d-flex justify-content-center" id="pagination-container">
                {{ $pengajar->links('pagination::bootstrap-5') }}
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
        console.log(url);

        $.ajax({
            url: url,
            method: 'GET',
            data: {
                search: searchValue,
            },
            success: function(data) {
                $('#pengajar-table').html(data);
                $('html, body').animate({
                    scrollTop: 0
                }, 'fast');
            }
        });
    });
</script>
