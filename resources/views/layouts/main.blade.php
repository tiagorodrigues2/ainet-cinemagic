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

    @yield('head')
</head>

<body class="bg-gray-100">
    <div id="app">
        <nav class="toolbar fixed top-0 left-0 right-0">
            <div class="mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-around h-16">
                    <div class="flex items-center">
                        <a href="{{ url('/') }}" class="flex-shrink-0 flex items-center" style="font-size: 27px">
                            CineMagic
                        </a>

                        <a href="{{ route('movies', ['page' => 1]) }}" class="flex-shrink-0 flex items-center ms-16">
                            All Movies
                        </a>
                    </div>
                    <div class="flex items-center">
                        @auth
                            <div class="relative inline-block text-left">
                                <div>
                                    <button id="user-dropdown" type="button" class="inline-flex w-full justify-center gap-x-1.5 rounded-md px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-900" id="menu-button" aria-expanded="true" aria-haspopup="true">
                                      {{ auth()->user()->name }}
                                      <svg class="-mr-1 h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                                      </svg>
                                    </button>
                                </div>
                                <div id="user-dropdown-content" class="hidden absolute right-0 z-10 mt-2 w-56 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1">
                                    <div class="py-1" role="none">
                                        <a href="{{ route('profile') }}" class="text-gray-700 block px-4 py-2 text-sm" role="menuitem" tabindex="-1" id="menu-item-0">Edit Profile</a>
                                        <a href="{{ route('purchases') }}" class="text-gray-700 block px-4 py-2 text-sm" role="menuitem" tabindex="-1" id="menu-item-0">My Purchases</a>
                                        @if (auth()->user()->isAdmin())
                                            <a href="{{ route('employees') }}" class="text-gray-700 block px-4 py-2 text-sm" role="menuitem" tabindex="-1" id="menu-item-1">Manage Employees <span class="text-red-800 text-sm ml-auto">A</span></a>
                                            <a href="{{ route('costumers') }}" class="text-gray-700 block px-4 py-2 text-sm" role="menuitem" tabindex="-1" id="menu-item-1">Manage Costumers <span class="text-red-800 text-sm ml-auto">A</span></a>
                                            <a href="#" class="text-gray-700 block px-4 py-2 text-sm" role="menuitem" tabindex="-1" id="menu-item-1">Manage Theaters <span class="text-red-800 text-sm ml-auto">A</span></a>
                                        @endif
                                        @if (auth()->user()->isManager() || auth()->user()->isAdmin())
                                            <a href="#" class="text-gray-700 block px-4 py-2 text-sm" role="menuitem" tabindex="-1" id="menu-item-1">Manage Movies <span class="text-yellow-800 text-sm ml-auto">E</span></a>
                                            <a href="#" class="text-gray-700 block px-4 py-2 text-sm" role="menuitem" tabindex="-1" id="menu-item-1">Manage Screenings <span class="text-yellow-800 text-sm ml-auto">E</span></a>
                                        @endif
                                        <form method="post" action={{ route('logout') }}>
                                            @csrf
                                            <button type="submit" class="text-gray-700 block w-full text-left px-4 py-2 text-sm" role="menuitem" tabindex="-1" id="menu-item-2">Logout</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="font-semibold text-white shadow-sm bg-red-900 hover:bg-red-800 px-3 py-2 rounded-md text-sm">
                                Sign In
                            </a>
                        @endauth

                        <a href="#" class="font-semibold text-white shadow-sm hover:bg-red-900 px-3 py-2 rounded-md text-sm ms-8">
                            Cart
                            @if (session()->has('cart'))
                                <span class="bg-green-500 text-white rounded-full px-2 py-1 ms-2">{{ count(session('cart')) }}</span>
                            @endif
                        </a>
                    </div>
                    </div>
                </div>
                </div>
                </div>
        </nav>

        <main class="mt-24">
            @yield('content')
        </main>
    </div>
</body>

</html>
