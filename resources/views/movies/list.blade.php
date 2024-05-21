@extends('layouts.main')


@section('head')

<script>

    document.addEventListener('DOMContentLoaded', function() {
        var searchLabel = document.getElementById('search-label');
        var searchInput = document.getElementById('search-input');
        var items = document.getElementById('items');

        var prev = document.getElementById('prev');
        var curr = document.getElementById('curr');
        var next = document.getElementById('next');

        prev?.addEventListener('click', function() {
            var page = parseInt(document.querySelector('input[name="page"]').value);
            if (page > 1) {
                document.querySelector('input[name="page"]').value = page - 1;
                document.querySelector('button[type="submit"]').click();
            }
        });

        next.addEventListener('click', function() {
            var page = parseInt(document.querySelector('input[name="page"]').value);
            document.querySelector('input[name="page"]').value = page + 1;
            document.querySelector('button[type="submit"]').click();
        });

        curr.addEventListener('click', function() {
            document.querySelector('button[type="submit"]').click();
        });

        items.addEventListener('change', function() {
            document.querySelector('input[name="items"]').value = items.value;
            document.querySelector('button[type="submit"]').click();
        });

        searchLabel.style.display = 'none';

        searchInput.addEventListener('blur', function() {
            searchLabel.style.display = 'none';
        });

        searchInput.addEventListener('focus', function() {
            searchLabel.style.display = 'inline';
        });
    });
</script>

@endsection

@section('content')

    <div class="container mx-auto">
        <div class="flex justify-between items-center mb-4">
            <div class="flex-col">
                <form method="GET">
                    <input type="text" id="search-input" class="px-2 py-1 border border-gray-300" placeholder="Search..." name="search" style="width: 400px" value={{ $search }}>
                    <label id="search-label">Press Enter to search</label>
                    <div class="mt-4">
                        <input type="checkbox" @checked(isset($synopsis)) name="synopsis">
                        Search Synopsis
                    </div>
                    <input hidden type="number" name="page" value={{ $page }}>
                    <input hidden type="number" name="items" value={{ isset($items) ? $items : 5 }}>
                    <button hidden type="submit" class="px-2 py-1 bg-gray-200 text-gray-800 ring-1 hover:ring-blue-800 ring-gray-500" style="border-radius: 3px">Search</button>
                </form>
            </div>
            <div class="flex items-center">
                <label for="items" class="mr-2">Items Per Page:</label>
                <select name="items" id="items" class="px-2 py-1 bg-gray-200 text-gray-800 ring-1 hover:ring-blue-800 ring-gray-500 mr-8" style="border-radius: 3px">
                    <option value="5" @selected(isset($items) && $items == 5)>5</option>
                    <option value="10" @selected(isset($items) && $items == 10)>10</option>
                    <option value="15" @selected(isset($items) && $items == 15)>15</option>
                    <option value="20" @selected(isset($items) && $items == 20)>30</option>
                </select>

                <label for="page" class="mr-2">Page:</label>
                @if ($page > 1)
                    <button id="prev">
                        <a class="px-2 py-1 bg-gray-200 text-gray-800 ring-1 hover:ring-blue-800 ring-gray-500" style="border-radius: 3px"><<</a>
                    </button>
                @endif
                <button id="curr">
                    <a class="px-2 py-1 bg-gray-200 text-gray-800 ring-1 hover:ring-blue-800 ring-gray-500" style="border-radius: 3px">{{ $page }}</a>
                </button>
                <button id="next">
                    <a class="px-2 py-1 bg-gray-200 text-gray-800 ring-1 hover:ring-blue-800 ring-gray-500" style="border-radius: 3px">>></a>
                </button>
            </div>
        </div>
        <table class="min-w-full bg-white border border-gray-300">
            <thead>
                <tr>
                    <th class="px-4 py-2 border-b text-start">Poster</th>
                    <th class="px-4 py-2 border-b text-start">Title</th>
                    <th class="px-4 py-2 border-b text-start">Genre</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($movies as $movie)
                    <tr class="odd:bg-slate-100">
                        <td class="px-4 py-2 border-b">
                            <img src="{{ asset('storage/posters/'. $movie->poster_filename) }}" alt="{{ $movie->title }}" class="w-16 h-24">
                        </td>
                        <td class="px-4 py-2 border-b">{{ $movie->title }}</td>
                        <td class="px-4 py-2 border-b">{{ $movie->genre }}</td>
                        <td>
                            <a href={{ route('movie', [ 'id' => $movie->id ]) }}><button class="px-4 py-2 text-black rounded-md hover:bg-blue-300 ring-1 ring-blue-500" style="border-radius: 3px">Info</button></a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

@endsection
