<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'POS Frontend')</title>

    <!-- Tailwind CSS & Vue JS via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.ts'])
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">

    <!-- Header -->
    <header class="bg-primary text-white shadow">
        <div class="container mx-auto flex justify-between items-center py-4 px-6">
            <nav class="space-x-4">
                <a href="{{ route('frontend.home') }}" class="hover:underline">Home</a>
                <!-- <a href="{{ route('frontend.about') }}" class="hover:underline">About</a>
                <a href="{{ route('frontend.contact') }}" class="hover:underline">Contact</a> -->
            </nav>
        </div>
    </header>

    <!-- Main content -->
    <main class="flex-1 container mx-auto p-6">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white p-4 text-center mt-auto">
        &copy; {{ date('Y') }} POS System. All rights reserved.
    </footer>

</body>
</html>
