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

        @if(count($cart) <= 0)
            <x-toast type="info" message="No items in the cart" />
        @endif

        <h1 class="text-3xl font-bold mb-4">Checkout</h1>

        @if(count($cart) > 0)
            <div class="flex justify-end mb-4">
                <form action="{{ route('cart.clear') }}" method="post">
                    @method('PATCH')
                    @csrf
                    <button type="submit" class="bg-gray-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                        Clear Cart
                    </button>
                </form>
            </div>
        @endif

        <table class="min-w-full bg-white border border-gray-300 mb-8">
            <thead>
                <tr>
                    <th class="px-4 py-2 border-b text-start">Movie</th>
                    <th class="px-4 py-2 border-b text-start">Theater</th>
                    <th class="px-4 py-2 border-b text-start">Starts at</th>
                    <th class="px-4 py-2 border-b text-start">Seat</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($cart as $item)
                    <tr class="odd:bg-slate-100">
                        <td class="px-4 py-2 border-b">{{ $item['movie'] }}</td>
                        <td class="px-4 py-2 border-b">{{ $item['theater'] }}</td>
                        <td class="px-4 py-2 border-b">{{ $item['screening_date'] }}</td>
                        <td class="px-4 py-2 border-b">{{ $item['seat'] }}</td>
                        <td class="px-4 py-2 border-b text-end">
                            <form action="{{ route('cart.remove', ['seat_id' => $item['seat_id']]) }}" method="post">
                                @method('DELETE')
                                @csrf
                                <button type="submit" class="bg-gray-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                    Remove
                                </button>
                            </form>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>

@endsection
