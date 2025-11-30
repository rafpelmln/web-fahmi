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

        <!-- Bootstrap CSS & Fonts -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body { background-color: #f4f6f9; }
            .auth-card { border-radius: 12px; box-shadow: 0 6px 20px rgba(23, 28, 34, 0.08); }
            .brand { font-weight: 700; color: #0d6efd; }
        </style>
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-vh-100 d-flex align-items-center justify-content-center py-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-12 col-md-8 col-lg-6">
                        <div class="text-center mb-4">
                            <a href="/" class="text-decoration-none d-inline-flex align-items-center gap-2">
                                <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
                                <span class="brand fs-4">{{ config('app.name', 'Laravel') }}</span>
                            </a>
                        </div>

                        <div class="card auth-card bg-white p-4">
                            <div class="card-body">
                                {{ $slot }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
