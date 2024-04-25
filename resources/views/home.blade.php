@extends('layouts.main')

@section('content')
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 mt-8 mb-16">
        <h1 class="text-3xl font-bold">Welcome to CineMagic</h1>
        <p class="mt-4">The best place to find your favorite movies.</p>
    </div>

    <div class="flex flex-wrap justify-center mx-auto px-4 sm:px-6 lg:px-8">
        @foreach ($movies as $movie)
            <div class="bg-white rounded-lg shadow-lg mx-4 my-4">
                <img src="{{ asset('storage/posters/'. $movie->poster_filename) }}" alt="{{ $movie->title }}" class="w-48 h-64 object-cover rounded-t-lg">
                <div class="p-4">
                    <h2 class="text-xl font-bold">{{ $movie->title }}</h2>
                    <p class="text-gray-500">{{ $movie->genre }}</p>
                </div>
            </div>
        @endforeach
    </div>

@endsection
