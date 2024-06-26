@extends('layouts.main')

@section('content')
    <div class="container mx-auto pt-16">

        <h1 class="text-3xl font-bold mb-8">Customers</h1>

        @isset($sucesso)
            <x-toast type="success" :message="$sucesso" />
        @endisset

        @isset($erro)
            <x-toast type="error" :message="$erro" />
        @endisset

        <form action="{{ route('customers') }}" method="GET">
            <div class="mb-4 flex-row">
                <input type="text" value="{{ $search }}" name="search" class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 w-300" placeholder="Search">
                <button type="submit" class="px-4 py-2 bg-indigo-500 text-white rounded-md hover:bg-indigo-600">Search</button>
            </div>
        </form>

        <x-UserList :users="$customers" type="customers" />
    </div>
@endsection
