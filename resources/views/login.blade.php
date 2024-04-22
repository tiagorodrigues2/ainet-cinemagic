@extends('layouts.main')

@section('content')
    <div class="bg white p-16">

        <form class="max-w-sm my-8" method="post" action="{{ route('login.submit') }}">
            @csrf
            <div class="mb-4">
                <label for="email" class="block mb-2 text-sm font-medium text-gray-700">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email', '') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
            </div>
            <div class="mb-4">
                <label for="password" class="block mb-2 text-sm font-medium text-gray-700">Password</label>
                <input type="password" id="password" name="password"
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                    required>
            </div>
            <div class="flex items-center justify-between">
                <button type="submit"
                    class="px-4 py-2 text-sm font-medium text-white bg-blue-500 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">Login</button>
                <a href="#" class="text-sm text-blue-500 hover:text-blue-600">Forgot Password?</a>
            </div>
        </form>

        @error('login')
            <x-toast type="error" message="{{ $message }}"></x-toast>
        @enderror

        <div class="mt-8">
            <p class="text-sm text-gray-600">Don't have an account? <a href="#" class="text-blue-500 hover:text-blue-600">Register</a></p>
        </div>
    </div>
@endsection
