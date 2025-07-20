<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-light">
    <div class="min-vh-100 d-flex flex-column justify-content-center align-items-center py-5">
        <div class="text-center mb-4">
            <a href="/" class="text-decoration-none">
                <div class="d-flex align-items-center justify-content-center mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="#2563eb" class="me-3"
                        viewBox="0 0 16 16">
                        <path d="M8 0a8 8 0 1 0 8 8A8 8 0 0 0 8 0Zm0 15A7 7 0 1 1 15 8 7 7 0 0 1 8 15Z" />
                        <circle cx="8" cy="8" r="4" />
                    </svg>
                    <h1 class="fw-bold mb-0" style="color: #2563eb; font-size: 2rem;">Finance Tracker</h1>
                </div>
            </a>
            <p class="text-muted mb-0">Take Control of Your Financial Future</p>
        </div>

        <div class="card border-0 shadow-sm rounded-4" style="min-width: 400px; max-width: 450px;">
            <div class="card-body p-4">
                {{ $slot }}
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>