@extends('layouts.main')

@section('content')

@if ($errors->any())
@foreach ($errors->all() as $error)
    <x-toast type="error" :message="$error" />
@endforeach
@endif

@if (session()->has('success'))
<x-toast type="success" :message="session('success')" />
@endif

@if (session()->has('error'))
<x-toast type="error" :message="session('error')" />
@endif

<div class="bg-white rounded-lg shadow-lg overflow-hidden flex justify-center flex-wrap m-16 p-8">

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

    @if (count($nextScreenings) == 0)
        <p class="text-red-500 mt-4">No screenings available</p>
    @else
        <div>
            <p class="font-bold">Next Screenings</p>
            <table class="mt-4">
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
                            <td class="border px-4 py-2">{{ $screening->theater()->get()->first()->name }}</td>
                            <td class="border px-4 py-2">{{ $screening->date }}</td>
                            <td class="border px-4 py-2">{{ $screening->start_time }}</td>
                            <td class="border px-4 py-2 text-green-700" style="text-align: right; font-size: 16px; font-weight: 700">{{ $ticketPrice }} €</td>
                            <td class="border px-4 py-4">
                                <a href="{{ route('screening', ['id' => $screening->id]) }}" class="bg-gray-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                    Buy Ticket
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
