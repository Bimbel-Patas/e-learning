<div id="mapel-table">
    @if ($mapel->count() > 0)
        Jumlah Mapel : {{ $mapel->total() }}
        <div class="table-responsive col-12">
            <table class="table table-striped table-lg">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nama</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($mapel as $key)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $key->name }}</td>
                            <td>
                                <a href="{{ route('viewUpdateMapel', ['mapel' => $key->id]) }}"
                                    class="badge bg-info p-2 mb-1 animate-btn-small"><i
                                        class="fa-solid fa-pen-to-square fa-xl mb-1"></i></a>
                                <a href="#table" class="badge bg-secondary p-2 animate-btn-small"><i
                                        class="fa-solid fa-xl fa-trash mb-1" data-bs-toggle="modal"
                                        data-bs-target="#deleteConfirmationModal"
                                        onclick="changeValue('{{ $key->id }}');"></i></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-center" id="pagination-container">
                {{ $mapel->links('pagination::bootstrap-5') }}
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
    // Ambil nilai pencarian saat halaman dimuat
    var searchValue = $("#search").val();

    // Tangani klik pada tombol halaman paginasi
    $("#pagination-container").on("click", ".pagination a", function(e) {
        e.preventDefault();
        var url = $(this).attr("href");

        // Lakukan permintaan AJAX untuk memperbarui konten tabel
        $.ajax({
            url: url,
            method: "GET",
            data: {
                search: searchValue,
            },
            success: function(data) {
                $("#mapel-table").html(data);

                // Gulir ke atas halaman setelah perubahan tabel
                $("html, body").animate({
                    scrollTop: 0,
                }, "fast");
            },
        });
    });
</script>
