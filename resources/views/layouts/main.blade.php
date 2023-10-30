<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Food Delivery</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        main {
            background-image: url('../../../condiments-prepare-italian-pasta.jpg');
            background-size: cover;
            background-position: center;
            font-family: Arial, sans-serif;
        }

        nav {
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            width: 100%;
        }

        footer {
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            margin-top: auto;
        }
    </style>
</head>
<body>
<header>
    @include('layouts.header')
</header>
<main>
    @yield('content')
</main>
<footer>
    @include('layouts.footer')
</footer>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.0/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
