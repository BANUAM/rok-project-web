<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@500;600;700&family=Teko:wght@600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <script>
        (function() {
            try {
                const savedTheme = localStorage.getItem('rok-theme') || 'dark';
                document.documentElement.setAttribute('data-theme', savedTheme);
            } catch (e) {}
        })();
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @inertiaHead
</head>
<body class="rok-app">
    <div class="hex-ambient" aria-hidden="true">
        <div class="hex-ambient__cell hex-ambient__cell--1"></div>
        <div class="hex-ambient__cell hex-ambient__cell--2"></div>
        <div class="hex-ambient__cell hex-ambient__cell--3"></div>
        <div class="hex-ambient__cell hex-ambient__cell--4"></div>
        <div class="hex-ambient__cell hex-ambient__cell--5"></div>
    </div>

    @inertia
</body>
</html>
