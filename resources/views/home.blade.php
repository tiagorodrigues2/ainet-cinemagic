@extends('layouts.main')

@section('content')
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 mt-8 mb-16">
        <h1 class="text-3xl font-bold">Welcome to CineMagic</h1>
        <p class="mt-4">The best place to find your favorite movies.</p>
    </div>

    <div class="flex flex-wrap justify-center mx-auto px-4 sm:px-6 lg:px-8">
        @foreach ($movies as $movie)
            <x-PosterCard :movie="$movie" />
        @endforeach
    </div>
    </div>

@endsection
