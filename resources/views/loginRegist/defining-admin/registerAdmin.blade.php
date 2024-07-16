<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title }} | to Academify</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins">
    <link rel="icon" type="image/png" href="asset/img/logo.png" />
    <style>
        body {
            font-family: 'Poppins';
        }
    </style>
</head>

<body>
    <div class="container mt-5 mb-5">
        <div class="row">
            {{-- Box Kiri --}}
            <div class="col-sm-5 col-12 col-md-4" style="margin-top: 50px;">
                <div class="col-sm-7 col-md-8 col-12 text-center d-block d-sm-none">
                    <img src="asset/img/illustration-1.jpg" width="400px" class=" img-fluid" alt="">
                </div>
                <div class="col-12 mt-4">
                    <img src="asset/img/cbt-b.png" width="400px" class="img-fluid" alt="">
                </div>
                <div class="card px-1 py-4 mt-4">
                    <div class="card-body">
                        {{-- Start Form --}}
                        <form action="{{ route('registAdmin') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-sm-12 mb-3">
                                    <h1>Registrasi Admin (One Time)</h1>
                                    <span class="text-secondary">Admin Academify-CBT hanya berjumlah satu dan akan
                                        mewakili seluruh kegiatan pengelolaan data, pastikan data yang akan disimpan
                                        merupakan data yang global untuk instansi atau orang-orang yang memiliki
                                        wewenang.</span>
                                    <hr>
                                    <div class="form-group">
                                        <label for="nama">Nama User : </label>
                                        <input class="form-control" id="nama" type="text"
                                            placeholder="Masukan Nama Lengkap anda..." required name="nama"
                                            value="{{ old('nama', 'Admin') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12 mb-3">
                                    <div class="form-group">
                                        <label for="email">Email : </label>
                                        <input class="form-control" id="email" type="email"
                                            placeholder="Masukan email anda..." required name="email"
                                            value=" {{ old('email') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6 mb-3">
                                    <div class="form-group">
                                        <label for="password">Password <span class="text-secondary small">(Min :
                                                8)</span> :
                                        </label>
                                        <input class="form-control" name="password" id="password" type="password"
                                            placeholder="****" required>
                                    </div>
                                </div>
                                <div class="col-6 mb-3">
                                    <div class="form-group">
                                        <label for="confirm-password">Confirm Password : </label>
                                        <input class="form-control" name="confirm-password" id="confirm-password"
                                            type="password" placeholder="****" required>
                                    </div>
                                </div>
                            </div>
                            <div class="form-check">
                                <p class="text-secondary">Setelah Akun teregistrasi maka halaman ini tidak akan bisa
                                    diakses lagi!, maka pastikan data yang diinput benar.</p>
                                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault"
                                    required>
                                <label class="form-check-label" for="flexCheckDefault">
                                    Saya mengerti dan telah mengamankan Password akun admin.
                                </label>
                            </div>
                            <button class="btn btn-danger w-100 mt-4" type="submit"><i
                                    class="fa-regular fa-circle-check"></i>
                                Registrasi Admin</button>
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
        <div class="text-center">
            <hr>
            <strong>Academify-CBT</strong> made by CV.Kodevio IT Performance 2023 discover more
            built-in app via <a href="#">This Link</a>
        </div>
    </div>
    <script src="https://kit.fontawesome.com/68f43c1324.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous">
    </script>
</body>

</html>
