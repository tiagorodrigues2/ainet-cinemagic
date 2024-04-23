@extends('layouts.main')

@section('content')
    <div class="bg white p-16">

        <form class="max-w-md" method="POST" action="{{ route('register.submit') }}">
            @csrf

            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    <x-toast type="error" message="{{ $error }}" />
                @endforeach
            @endif

            <div class="mb-4">
                <label for="name" class="block mb-2 text-sm font-medium text-gray-700">Name</label>
                <input id="name" type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="name" value="{{ old('name') }}" required autofocus>
            </div>

            <div class="mb-4">
                <label for="email" class="block mb-2 text-sm font-medium text-gray-700">Email</label>
                <input id="email" type="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="email" value="{{ old('email') }}" required>
            </div>

            <div class="mb-4">
                <label for="password" class="block mb-2 text-sm font-medium text-gray-700">Password</label>
                <input id="password" type="password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="password" required>
            </div>

            <div class="mb-4">
                <label for="password_confirmation" class="block mb-2 text-sm font-medium text-gray-700">Confirm Password</label>
                <input id="password_confirmation" type="password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="password_confirmation" required>
            </div>

            <div class="flex items-center justify-between">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Register
                </button>
            </div>
        </form>

        <div class="mt-4 text-left">
            Already have an account? <a href="{{ route('login') }}" class="text-blue-500 hover:text-blue-700">Sign in</a>
        </div>

    </div>
@endsection
