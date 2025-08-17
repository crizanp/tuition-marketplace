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
        @if(auth()->check() && isset(auth()->user()->status) && auth()->user()->status === 'suspended')
            <div class="alert alert-warning text-center mb-0" role="alert" style="border-radius:0;">
                Your account has been suspended. Please
                @if (\Illuminate\Support\Facades\Route::has('contact'))
                    <a href="{{ route('contact') }}">contact support</a>
                @else
                    <a href="{{ url('/contact') }}">contact support</a>
                @endif
                to reactivate your account.
            </div>
        @endif
        @yield('content')
    </main>
    
</main>

    @include('components.footer')
    @stack('scripts')
    @if(session('vacancy_posted'))
        <script>
            try {
                // Clear any vacancy draft keys created per-user
                const prefix = 'vacancy_draft_';
                for (let i = 0; i < localStorage.length; i++) {
                    const key = localStorage.key(i);
                    if (key && key.startsWith(prefix)) {
                        localStorage.removeItem(key);
                    }
                }
            } catch (err) {
                console.warn('Could not clear vacancy draft from localStorage', err);
            }
        </script>
    @endif
</body>
</html>