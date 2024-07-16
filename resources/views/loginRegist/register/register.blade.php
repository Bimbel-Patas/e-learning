@extends('layout.template.loginRegistTemplate')
@section('container')
    <div class="row-login">
        {{-- Bagian Kiri --}}
        <div class="col-sm-5 col-12 col-md-4" style="margin-top: 0px;">

            {{-- Tampilan animasi untuk perangkat mobile --}}
            {{-- <div class="col-sm-7 col-md-8 col-12 text-center d-block d-sm-none">
                <div id="anim2" class="p-4"></div>
            </div> --}}

            {{-- Logo CBT --}}
            {{-- <div class="col-12 mt-4">
                <img src="asset/img/cbt-b.png" width="400px" class="img-fluid" alt="">
            </div> --}}

            {{-- Kartu Registrasi --}}
            <div class="card px-1 py-4 mt-4">
                <div class="card-body">
                    <h1>Registrasi</h1>
                    {{-- Mulai Form Registrasi --}}
                    <form action="{{ route('validate') }}" method="POST">
                        @csrf

                        {{-- Alert Kesalahan --}}
                        @if (session()->has('nis-error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('nis-error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        {{-- Input Email --}}
                        <div class="row">
                            <div class="col-sm-12 mb-3">
                                <div class="form-group">
                                    <label for="email">Email : </label>
                                    <input class="form-control" id="email" type="email"
                                        placeholder="Masukan email anda..." name="email" value="{{ old('email') }}">
                                    @error('email')
                                        <div class="text-danger small">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Input Nomor Telepon (Opsional) --}}
                        <div class="row">
                            <div class="col-sm-12 mb-3">
                                <div class="form-group">
                                    <label for="email">Nomor Telepon <span class="text-secondary small">(Opsional)</span>
                                        :
                                    </label>
                                    <input class="form-control" id="noTelp" type="number" placeholder="0851xxx"
                                        name="noTelp" value="{{ old('noTelp') }}">
                                    @error('noTelp')
                                        <div class="text-danger small">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Input Password --}}
                        <div class="row">
                            <div class="col-6 mb-3">
                                <div class="form-group">
                                    <label for="password">Password <span class="text-secondary small">(Min : 8)</span> :
                                    </label>
                                    <input class="form-control" id="password" name="password" type="password"
                                        placeholder="****">
                                    @error('password')
                                        <div class="text-danger small">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Konfirmasi Password --}}
                            <div class="col-6 mb-3">
                                <div class="form-group">
                                    <label for="confirm-password">Confirm Password : </label>
                                    <input class="form-control" id="confirm-password" name="confirm-password"
                                        type="password" placeholder="****">
                                    @error('confirm-password')
                                        <div class="text-danger small">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Input Nomor Induk Siswa (NIS) --}}
                        <div class="row">
                            <div class="col-sm-12 mb-3">
                                <div class="form-group">
                                    <label for="email">Kode Peserta : </label>
                                    <input class="form-control" id="text" name="nis"
                                        placeholder="Masukan Kode Peserta anda..." value="{{ old('nis') }}">
                                    @error('nis')
                                        <div class="text-danger small">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Checkbox Persetujuan --}}
                        <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" required>
                        <label class="form-check-label" for="flexCheckDefault">
                            Saya mengisi data saya dengan benar.
                        </label>

                        {{-- Tombol Registrasi --}}
                        <button class="btn btn-primary w-100 mt-4" type="submit"><i class="fa-regular fa-circle-check"></i>
                            Registrasi</button>
                    </form>

                    {{-- Form Selesai --}}

                    {{-- Informasi untuk Login atau Lupa Password --}}
                    <div class="mt-2">
                        <hr>
                        <span class="small text-secondary">Sudah memiliki akun? <a href="/login">Login</a></span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Bagian Kanan --}}
        {{-- <div class="col-sm-7 col-md-8 mt-4 col-12 text-center d-none d-sm-block">
            <div id="anim" class="p-4"></div> --}}
        {{-- <img src="asset/img/illustration-1.jpg" width="700px" class=" img-fluid" alt=""> --}}
        {{-- </div> --}}
    </div>
@endsection
