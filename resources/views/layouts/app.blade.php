<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Tuition Marketplace</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('head')
</head>
<body class="" style="background-color: #ffffffff;">

    <main>
        @hasSection('navbar')
            @yield('navbar')
        @else
            @include('partials.unified-navbar')
        @endif
        @yield('content')
    </main>
    
</main>

    @include('components.footer')
    @stack('scripts')
</body>
</html>