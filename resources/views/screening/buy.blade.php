@extends('layouts.main')

@section('head')

    <script>

        function selectSeat(seatId) {
            let seat = document.getElementById('seat_id');
            seat.value = seatId;

            let buttons = document.querySelectorAll('button');
            buttons.forEach(button => {
                button.classList.remove('bg-green-500');
            });

            let selectedSeat = document.querySelector(`button[onclick="selectSeat(${seatId})"]`);
            selectedSeat.classList.remove('bg-white');
            selectedSeat.classList.add('bg-green-500');
        }

    </script>

@endsection

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
            <form method="post" action={{ route('cart.add') }}>
                @method('POST')
                @csrf
                <input value={{ $screening->id }} id="screening_id" name="screening_id" hidden>
                <input hidden type="number" name="seat_id" id="seat_id">

                <div class="flex justify-end mt-8">
                    <button type="submit" class="bg-gray-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Add to Cart</button>
                </div>
            </form>

            <div class="flex-col justify-center items-center w-full mb-4 mt-4">
                <div class="p-2 flex justify-center"><span class="font-semibold text-lg">Where do you wanna seat?</span></div>
                <div class="p-2 flex justify-center mt-[-20px] mb-2"><span>Screen is here</span></div>
                <div class="flex w-full justify-center">
                    <div class="flex-col">
                        @foreach ($seatRows as $row)
                            <div class="flex justify-center w-full">
                                @foreach ($row as $seat)
                                    <button @disabled($seat->status == 'occupied' || $seat->status == 'reserved') class="border border-gray-300 hover:bg-green-300 w-10 h-10 disabled:bg-red-300 bg-white disabled:font-light font-semibold disabled:cursor-not-allowed" onclick="selectSeat({{ $seat->id }})">
                                        {{ $seat->row }}{{ $seat->seat_number }}
                                    </button>
                                @endforeach
                                </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>

@endsection
