@extends('layouts.main')


@section('content')

    <div class="container mx-auto flex-col justify-center">

        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <x-toast type="error" :message="$error"/>
            @endforeach
        @endif

        @if (session()->has('success'))
            <x-toast type="success" :message="session('success')"/>
        @endif

        @if (session()->has('error'))
            <x-toast type="error" :message="session('error')"/>
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
                    <img src="{{ asset('storage/posters/'. $movie->poster_filename) }}" alt="{{ $movie->title }}"
                         class="w-16 h-24">
                </td>
                <td class="px-4 py-2 border-b">{{ $movie->title }}</td>
                <td class="px-4 py-2 border-b">{{ $movie->genre()->first()->name }}</td>
                <td>
                    <a href={{ route('movie', [ 'id' => $movie->id ]) }}>
                        <button class="px-4 py-2 text-black rounded-md hover:bg-blue-300 ring-1 ring-blue-500"
                                style="border-radius: 3px">Info
                        </button>
                    </a>
                </td>
            </tr>
            </tbody>
        </table>

        @if(count($seats) <= 0)
            <p class="text-red-500 mt-4">No seats available</p>
            <a href={{ route('movie', [ 'id' => $movie->id ]) }} class="mt-12 bg-gray-500 hover:bg-green-700 text-white
               font-bold py-2 px-4 rounded">Go back</a>
        @else
            <span class="font-semibold" style="font-size: 19px">Chose a seat</span>
            <form method="post" action={{ route('cart.add') }}>
                @method('POST')
                @csrf
                <input value={{ $screening->id }} id="screening_id" name="screening_id" hidden>
                <div class="relative inline-block w-64">
                    <select name="seat_id" id="seat_id"
                            class="block appearance-none w-full bg-white border border-gray-300 text-gray-700 py-2 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                            style="cursor: pointer">
                        <option value="">Select a seat</option>
                        @foreach($seats as $seatRow)
                            @foreach($seatRow as $seat)
                                <option value="{{ $seat->id }}">{{ $seat->row }} - {{ $seat->seat_number }}</option>
                            @endforeach
                        @endforeach
                    </select>
                </div>

                <div class="container">
                    <h3 class="text-light bg-dark text-center">Ecrã</h3>
                </div>
                <form action="{{route('cart.checkout')}}" id="sessao" method="POST">
                @csrf
                <div class="container">
                    <table class="table table-striped table-responsive-md btn-table">
                        <tbody>
                        @foreach($seats as $seat)
                            <tr>
                                <th class="bg-light">{{$seat[0]->row}}</th>
                                @foreach($seat as $pos)
                                    @if($pos->ocupado == 'ocupado')
                                    <th class="bg-light">{{$pos->seat_number}}</th>
                                    <td class="text-center"><input type="button"
                                                                   class="btn btn-outline-primary btn-sm m-0 waves-effect"
                                                                   style="background-color: greenyellow"
                                                                   value="{{$pos->seat_number}}">
                                    </td>
                                    @else
                                        <td class="text-center">
                                            <input type="button"
                                                   class="btn btn-outline-primary btn-sm m-0 waves-effect"
                                                   value="{{$pos->seat_number}}"
                                                   onclick="submitForm('{{$seat[0]->row}}', '{{$pos->seat_number}}')">
                                        </td>
                                    @endif
                                @endforeach
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="flex justify-end mt-8">
                    <button type="submit" class="bg-gray-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                        Add to Cart
                    </button>
                </div>
            </form>
        @endif
    </div>
    </form>
    <script>
        function submitForm(row, seat) {
            document.getElementById("fila").value = fila;
            document.getElementById("lugar").value = lugar;
            document.getElementById("sessao").submit();
        }
    </script>

@endsection
