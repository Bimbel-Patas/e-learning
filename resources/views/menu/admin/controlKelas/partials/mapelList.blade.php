<div class="p-4">
    {{-- Nama --}}
    <div class="mb-3">
        <label for="nama" class="form-label">Nama</label>
        <input type="text" class="form-control" id="nama" name="nama" placeholder="Inputkan nama kelas..."
            value="{{ $kelas->name }}" required readonly>
    </div>

    {{-- Bagian tabel untuk menampilkan mapel yang ditambahkan --}}
    <table id="tabelMapel" class="table">
        <thead>
            <tr>
                <th>Nama Mapel</th>
                <th>Pengajar</th>
            </tr>
        </thead>
        <tbody>
            <!-- Data mapel akan ditambahkan oleh JavaScript -->
            @foreach ($enrolledMapel as $key)
                <tr data-mapel-id="{{ $key['id'] }}">
                    <td>{{ $key['name'] }}</td>
                    <td>
                        <select name="" id="" class="form-select">
                            <option value="delete" selected>-</option>
                            @foreach ($pengajar as $key2)
                                <option value="{{ $key2->id }}" @if ($key['pengajarName'] == $key2->name) selected @endif>
                                    {{ $key2->name }}</option>
                            @endforeach
                        </select>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script>
    $(document).ready(function() {
        // Menangani perubahan pada elemen <select>
        $('select.form-select').on('change', function() {
            // Dapatkan nilai id mapel dari data atribut
            var mapelId = $(this).closest('tr').data('mapel-id');

            // Dapatkan nilai id pengajar yang dipilih
            var pengajarId = $(this).val();

            // Kirim data ke server menggunakan AJAX
            $.ajax({
                type: 'POST',
                url: "{{ route('addChangeEditorAccess') }}", // Ganti dengan URL yang sesuai
                data: {
                    mapelId: mapelId,
                    kelasId: kelasId,
                    pengajarId: pengajarId,
                    _token: "{{ csrf_token() }}",
                },
                success: function(response) {
                    // Tanggapan dari server (jika perlu)
                    console.log(response);
                },
                error: function(error) {
                    // Penanganan kesalahan (jika perlu)
                    console.error(error);
                }
            });
        });
    });
</script>
