<div class="p-4">
    {{-- Bagian tabel untuk menampilkan daftar siswa --}}
    <table id="tabelSiswa" class="table">
        <thead>
            <tr>
                <th># Nama</th>
            </tr>
        </thead>
        <tbody style="max-height: 400px; overflow-y: scroll; display: block;">
            <!-- Data siswa akan ditampilkan oleh JavaScript -->
            @foreach ($siswa as $key)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        @if ($key['gambar'] == null)
                            <img src="/asset/icons/profile-women.svg" class="image-previewer image-class rounded-circle"
                                width="40px" alt="">
                        @else
                            <img src="{{ asset('storage/user-images/' . $key['gambar']) }}" alt="placeholder"
                                class="image-previewer image-class rounded-circle" width="40px">
                        @endif
                    </td>
                    <td>
                        @if ($key['user_id'] != null)
                            <a href="{{ route('viewProfileSiswa', ['token' => encrypt($key['user_id'])]) }}">
                                {{ $key['name'] }}
                            </a>
                        @else
                            {{ $key['name'] }}
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
