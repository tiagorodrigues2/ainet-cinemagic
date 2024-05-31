@extends('layouts.main')

@section('content')
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 mt-8 mb-16">

        @if(session()->has('printTickets'))
            <span>You have bought tickets recently.</span>
            <div class="mt-4 mb-8">
                @foreach (session()->get('printTickets') as $t)
                    <div class="mb-4">
                        <a href="{{ route('ticket', ['id' => $t->id]) }}" target="__blank" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            Print Ticket #{{ $t->id }}
                        </a>
                    </div>
                @endforeach
            </div>
        @endif

        <h1 class="text-3xl font-bold">Welcome to CineMagic</h1>
        <p class="mt-4">The best place to find your favorite movies.</p>

        @isset($sucesso)
            <x-toast type="success" :message="$sucesso" />
        @endisset

        @isset($erro)
            <x-toast type="error" :message="$erro" />
        @endisset
    </div>

    <div class="flex flex-wrap justify-center items-stretch mx-auto px-4 sm:px-6 lg:px-8">
        @foreach ($movies as $movie)
            <x-PosterCard :movie="$movie" />
        @endforeach
    </div>
    </div>

@endsection
