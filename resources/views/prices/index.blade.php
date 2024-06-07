@extends('layouts.main')

@section('content')

    <div class="container mx-auto">


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


        <form class="mt-8" method="post" action={{ route('prices.save') }}>
            @csrf

            <div class="mb-4" style="max-width: 200px">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="ticket_price">
                    Ticket Price
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="ticket_price" name="ticket_price" type="number" placeholder="Enter ticket price" value={{ $config->ticket_price }}>
            </div>
            <div class="mb-4" style="max-width: 200px">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="registered_customer_discount">
                    Registered Customer Discount
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="registered_customer_ticket_discount" name="registered_customer_ticket_discount" type="number" placeholder="Enter registered customer discount" value={{ $config->registered_customer_ticket_discount }}>
            </div>
            <div class="flex items-center justify-between">
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                    Save
                </button>
            </div>
        </form>
    </div>

@endsection
