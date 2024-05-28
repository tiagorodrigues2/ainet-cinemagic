@extends('layouts.main')

@section('content')

    <div class="container mx-auto pt-16 flex-col">

        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <x-toast type="error" :message="$error" />
            @endforeach
        @endif

        @isset($success)
            <x-toast type="success" :message="$success" />
        @endisset

        @isset($erro)
            <x-toast type="error" :message="$erro" />
        @endisset

        <form method="post">
            @csrf
            <div class="mb-4">
                <label for="ticket_id" class="block text-gray-700 text-sm font-bold mb-2">Ticket ID</label>
                <input type="text" id="ticket_id" name="ticket_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <span>Attention: When you scan a ticket, it will be automatically revoked.</span>
            <div class="mt-4">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Scan
                </button>
            </div>
        </form>

    </div>

@endsection
