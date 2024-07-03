@extends('layouts.main')

@section('head')
    <script>
        function generatePassword() {
            var passwordInput = document.getElementById('password');
            var generatedPassword = generateRandomPassword();
            passwordInput.value = generatedPassword;
        }

        function generateRandomPassword() {
            // Replace this with your own password generation logic
            // This is just a simple example
            var length = 6;
            var charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
            var password = "";
            for (var i = 0, n = charset.length; i < length; ++i) {
                password += charset.charAt(Math.floor(Math.random() * n));
            }
            return password;
        }
    </script>
@endsection

@section('content')
<div class="max-w-md mx-auto">
    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <x-toast type="error" message="{{ $error }}" />
        @endforeach
    @endif


    <form action="{{ route('employees.register.submit') }}" method="POST" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4" enctype="multipart/form-data">
        @csrf
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                Name
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="name" name="name" type="text" placeholder="Enter name" value={{ old('name', '') }}>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                Email
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="email" name="email" type="email" placeholder="Enter email" value={{ old('email', '') }}>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
                Password
            </label>
            <div class="flex">
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="password" name="password" placeholder="Enter password" value={{ old('password', '') }}>
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 ml-2 rounded focus:outline-none focus:shadow-outline" type="button" onclick="generatePassword()">
                    Generate
                </button>
            </div>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="type">
                Type
            </label>
            <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" style="cursor: pointer" id="type" name="type" value={{ old('type', '') }}>
                <option value="E">Employee</option>
                <option value="A">Admin</option>
            </select>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="photo">
                Photo
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="photo" name="photo" type="file" accept=".png, .jpeg, .jpg">
        </div>
        <div class="flex items-center justify-between">
            <a class="bg-gray-200 hover:bg-gray-300 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" style="color: black" href="{{ route('employees') }}">
                Voltar
            </a>
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                Create
            </button>
        </div>
    </form>
</div>
@endsection
