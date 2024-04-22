<!DOCTYPE html>
<html>

<head>
    <title>CineMagic</title>

    @vite(['resources/js/app.js', 'resources/css/app.css'])

    <style>
        .toolbar {
            background-color: rgb(70, 10, 10);
            color: white;
            font-family: Arial, sans-serif;
            font-size: 16px;
            font-weight: bold;
            padding: 10px;
        }
    </style>
</head>

<body>
    <div id="app">
        <nav class="bg-gray-800 toolbar">
            <div class="mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-around h-16">
                    <div class="flex items-center">
                        <a href="{{ url('/') }}" class="flex-shrink-0 flex items-center">
                            CineMagic
                        </a>
                    </div>
                    <div class="flex items-center">
                        <a href="#" class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Login</a>
                    </div>
                </div>
        </nav>

        <main>
            @yield('content')
        </main>
    </div>
</body>

</html>
