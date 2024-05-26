@extends('layouts.main')


@section('content')

    <div class="container mx-auto flex-col justify-center">

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


        <table class="min-w-full bg-white border border-gray-300 mb-8">
            <thead>
                <tr>
                    <th class="px-4 py-2 border-b text-start"></th>
                    <th class="px-4 py-2 border-b text-start">Title</th>
                    <th class="px-4 py-2 border-b text-start">Genre</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr class="odd:bg-slate-100">
                    <td class="px-4 py-2 border-b">
                        <img src="{{ asset('storage/posters/'. $movie->poster_filename) }}" alt="{{ $movie->title }}" class="w-16 h-24">
                    </td>
                    <td class="px-4 py-2 border-b">{{ $movie->title }}</td>
                    <td class="px-4 py-2 border-b">{{ $movie->genre()->first()->name }}</td>
                    <td>
                        <a href={{ route('movie', [ 'id' => $movie->id ]) }}><button class="px-4 py-2 text-black rounded-md hover:bg-blue-300 ring-1 ring-blue-500" style="border-radius: 3px">Info</button></a>
                    </td>
                </tr>
            </tbody>
        </table>

        @if(count($seats) <= 0)
            <p class="text-red-500 mt-4">No seats available</p>
            <a href={{ route('movie', [ 'id' => $movie->id ]) }} class="mt-12 bg-gray-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Go back</a>
        @else
            <span class="font-semibold" style="font-size: 19px">Chose a seat</span>
            <form method="post" action={{ route('cart.add') }}>
                @method('POST')
                @csrf
                <input value={{ $screening->id }} id="screening_id" name="screening_id" hidden>
                <div class="relative inline-block w-64">
                    <select name="seat_id" id="seat_id" class="block appearance-none w-full bg-white border border-gray-300 text-gray-700 py-2 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" style="cursor: pointer">
                        <option value="">Select a seat</option>
                        @foreach($seats as $seat)
                            <option value="{{ $seat->id }}">{{ $seat->row }} - {{ $seat->seat_number }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex justify-end mt-8">
                    <button type="submit" class="bg-gray-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Add to Cart</button>
                </div>
            </form>
        @endif
    </div>

@endsection
