@extends('layouts.main')

@section('content')
<div class="bg-white rounded-lg shadow-lg overflow-hidden flex justify-center m-16 p-8">
    <div class="flex-col justify-center">
        <div class="flex justify-center">
            <img src="{{ asset('storage/posters/'. $movie->poster_filename) }}" alt="{{ $movie->title }}" style="width: 300px; height: auto">
            <div class="p-4" style="max-width: 500px">
                <h1 class="text-3xl font-bold mb-2">{{ $movie->title }}</h1>
                <p class="text-gray-600 mb-2">{{ $movie->genre }}</p>
                <p class="text-gray-800 mb-4">{{ $movie->synopsis }}</p>
            </div>
        </div>
        @if (isset($movie->trailer_url) && !empty($movie->trailer_url))
            <div class="mt-8">
                <iframe src="https://www.youtube.com/embed/{{ $movie->trailer_url }}" frameborder="0" allowfullscreen style="width: 400px; height: 260px"></iframe>
            </div>
        @else
            <p class="text-red-500 mt-4">No trailer available</p>
        @endif
    </div>

    <div>
        <table class="mt-8">
            <thead>
                <tr class="bg-slate-200">
                    <th class="px-4 py-2">Theater</th>
                    <th class="px-4 py-2">Date</th>
                    <th class="px-4 py-2">Time</th>
                    <th class="px-4 py-2">Price</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($nextScreenings as $screening)
                    <tr class="odd:bg-slate-50">
                        <td class="border px-4 py-2">{{ $screening->theater }}</td>
                        <td class="border px-4 py-2">{{ $screening->date }}</td>
                        <td class="border px-4 py-2">{{ $screening->start_time }}</td>
                        <td class="border px-4 py-4">
                            <a href="#" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Buy Ticket
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
