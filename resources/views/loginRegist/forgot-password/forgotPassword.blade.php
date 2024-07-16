@extends('layout.template.loginRegistTemplate')
@section('container')
    <div class="row">

        {{-- Box Kiri --}}
        <div class="col-sm-5 col-12 col-md-4" style="margin-top: 50px;">

            <div class=" col-sm-7 col-md-8  col-12 text-center d-block d-sm-none">
                <img src="asset/img/illustration-1.jpg" width="400px" class=" img-fluid" alt="">
            </div>

            <div class="col-12  mt-4">
                <img src="asset/img/cbt-b.png" width="400px" class="img-fluid" alt="">
            </div>

            <div class="card px-1 py-4 mt-4">
                <div class="card-body">
                    <form action="">
                        @csrf
                        <div class="row">

                            {{-- Start Form --}}

                            <div class="col-sm-12 mb-3">
                                <h1>Lupa Password</h1>
                                <span class="text-secondary">Pastikan emailmu telah terdaftar pada sistem kami.</span>

                                <hr>
                                <div class="form-group">
                                    <label for="email">Email : </label>
                                    <input class="form-control" id="email" type="email"
                                        placeholder="Masukan email anda..." required>
                                </div>
                            </div>
                        </div>

                        <button class="btn btn-primary w-100 mt-4" type="submit"><i class="fa-solid fa-paper-plane"></i>
                            Kirim Email</button>
                    </form>

                    {{-- Form Habis --}}

                    <div class="mt-2">
                        <hr>
                        <span class="small text-secondary"><a href="{{ route('login') }}">
                                < Kembali ke halaman Login</a></span>
                    </div>
                </div>
            </div>
        </div>
        {{-- Box Kanan --}}
        <div class=" col-sm-7 col-md-8 mt-4 col-12 text-center d-none d-sm-block">
            <img src="asset/img/illustration-1.jpg" width="700px" class=" img-fluid" alt="">
        </div>

    </div>
@endsection
