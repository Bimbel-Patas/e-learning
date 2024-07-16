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
    <link rel="icon" type="image/png" href="asset/img/cbt.png" />
    <style>
        body {
            font-family: 'Poppins';
        }
    </style>
</head>

<body>
    <div class="container mt-5 mb-5">

        @yield('container')
        <div class="text-center">
            <hr>
            <strong>Academify-CBT</strong> made by Sunday Interactive 2023 discover more
            built-in app via <a href="#">This Link</a>
        </div>
    </div>


    <script src="{{ url('/asset/js/lottie.js') }}"></script>
    <script src="{{ url('/asset/js/customJS/loginRegist.js') }}"></script>

    <script src="https://kit.fontawesome.com/68f43c1324.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous">
    </script>
</body>

</html>
