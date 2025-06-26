<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Order Form')</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 min-h-screen">
    <header class="bg-white shadow mb-6">
        <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
            <div class="text-2xl font-bold text-blue-600">
                Qls Assessment Filip
            </div>
            <nav class="space-x-4">
            <a href="/" class="text-gray-700 hover:text-blue-600">Tool</a>
                <a href="/process" class="text-gray-700 hover:text-blue-600">Process</a>
                <!-- meer links hier -->
            </nav>
        </div>
    </header>
    <div class="container mx-auto px-4">
        @yield('content')
    </div>
    <footer class="bg-white  mt-10 py-4 text-center text-gray-500 text-sm w-full">
        &copy; {{ date('Y') }}
    </footer>
</body>
</html> 