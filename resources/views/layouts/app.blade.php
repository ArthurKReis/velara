<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'SMT Team Builder')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=roboto:400,500,700|cinzel:400,700" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Identidade Visual - SMT Team Builder */
        body {
            background-color: #0d0d0d;
            color: #f0f0f0;
            font-family: 'Roboto', sans-serif;
            min-height: 100vh;
        }

        .navbar-custom {
            background-color: #1a1a1a !important;
            border-bottom: 2px solid #cc0000;
        }

        .navbar-custom .navbar-brand,
        .navbar-custom .nav-link {
            color: #f0f0f0 !important;
        }

        .navbar-custom .nav-link:hover {
            color: #ff1a1a !important;
        }

        .navbar-custom .dropdown-menu {
            background-color: #2a2a2a;
            border: 1px solid #444;
        }

        .navbar-custom .dropdown-item {
            color: #f0f0f0;
        }

        .navbar-custom .dropdown-item:hover {
            background-color: #cc0000;
            color: #fff;
        }

        .btn-primary {
            background-color: #cc0000;
            border: none;
            color: #fff;
        }

        .btn-primary:hover {
            background-color: #990000;
            color: #fff;
        }

        .btn-secondary {
            background-color: #444;
            border: none;
            color: #f0f0f0;
        }

        .btn-secondary:hover {
            background-color: #555;
            color: #fff;
        }

        .btn-outline-primary {
            border: 1px solid #cc0000;
            color: #cc0000;
            background: transparent;
        }

        .btn-outline-primary:hover {
            background-color: #cc0000;
            color: #fff;
        }

        .card {
            background-color: #2a2a2a;
            border: 1px solid #444;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
        }

        .card-header {
            background-color: #1a1a1a;
            border-bottom: 1px solid #444;
            color: #f0f0f0;
            font-weight: 700;
            font-size: 1.2rem;
        }

        .table {
            color: #f0f0f0;
            background-color: #222;
        }

        .table thead th {
            background-color: #333;
            color: #f0f0f0;
            border-bottom: 2px solid #cc0000;
        }

        .table tbody tr:nth-of-type(odd) {
            background-color: #2a2a2a;
        }

        .table tbody tr:hover {
            background-color: #3a3a3a;
        }

        .table td, .table th {
            border-top: 1px solid #444;
        }

        .form-control {
            background-color: #333;
            border: 1px solid #555;
            color: #f0f0f0;
        }

        .form-control:focus {
            background-color: #333;
            border-color: #cc0000;
            color: #f0f0f0;
            box-shadow: 0 0 0 0.2rem rgba(204, 0, 0, 0.25);
        }

        .form-label {
            color: #f0f0f0;
        }

        .alert-success {
            background-color: #155724;
            border-color: #0f5132;
            color: #d4edda;
        }

        .alert-danger {
            background-color: #721c24;
            border-color: #5a1a1f;
            color: #f8d7da;
        }

        .alert-warning {
            background-color: #856404;
            border-color: #6b5300;
            color: #fff3cd;
        }

        .pagination .page-link {
            background-color: #333;
            border-color: #444;
            color: #f0f0f0;
        }

        .pagination .page-link:hover {
            background-color: #cc0000;
            border-color: #cc0000;
            color: #fff;
        }

        .pagination .active .page-link {
            background-color: #cc0000;
            border-color: #cc0000;
            color: #fff;
        }

        .badge {
            background-color: #e6b800;
            color: #1a1a1a;
        }

        .footer {
            background-color: #1a1a1a;
            border-top: 1px solid #444;
            color: #888;
            padding: 1rem 0;
            text-align: center;
            margin-top: 2rem;
        }

        .container-main {
            max-width: 1200px;
            margin: 0 auto;
            padding: 1rem;
        }

        .section-title {
            color: #ff1a1a;
            font-family: 'Cinzel', serif;
            border-bottom: 2px solid #cc0000;
            padding-bottom: 0.5rem;
        }

        /* Cores para posições dos demônios */
        .position-badge {
            display: inline-block;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background-color: #cc0000;
            color: #fff;
            text-align: center;
            line-height: 28px;
            font-weight: bold;
            font-size: 0.8rem;
        }

        /* Responsividade */
        @media (max-width: 768px) {
            .table {
                font-size: 0.85rem;
            }
            .btn {
                font-size: 0.8rem;
                padding: 0.3rem 0.6rem;
            }
            .container-main {
                padding: 0.5rem;
            }
        }
    </style>
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @if (isset($header))
            <header class="bg-dark shadow">
                <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main class="container-main">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="footer">
            <div class="container">
                &copy; {{ date('Y') }} SMT Team Builder. Todos os direitos reservados.
            </div>
        </footer>
    </div>

    <!-- Bootstrap JS (opcional, mas recomendado) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>