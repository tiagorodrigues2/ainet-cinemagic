@extends('layouts.main')

@section('content')
    <div class="max-w-md mx-auto">

        @isset($success)
            <x-toast type="success" :message="$success" />
        @endisset

        @isset($erro)
            <x-toast type="error" :message="$erro" />
        @endisset

        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <x-toast type="error" message="{{ $error }}" />
            @endforeach
        @endif

        <div class="bg-white shadow-md rounded-lg p-6">
            <div class="flex-col items-center mb-4">

                <div class="flex justify-between items-center">
                    <img src="{{ asset('storage/photos/' . $user->photo_filename) }}" alt="{{ $user->name }}" class="w-16 h-16 rounded-full mr-4 mb-8">
                    <form action="{{ route('profile.photo.update') }}" method="post" enctype="multipart/form-data">
                        @method('PATCH')
                        @csrf
                        <div class="mb-8" class="flex-col">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="type">
                                Change Photo
                            </label>
                            <input style="width: 300px" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="photo" name="photo" type="file" accept=".png, .jpeg, .jpg">
                            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline mt-4" type="submit">
                                Save Photo
                            </button>
                        </div>
                    </form>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="type">
                        Name
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="name" name="name" type="text" placeholder="Enter name" value={{ $user->name }} @readonly(true)>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="type">
                        Email
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="email" name="email" type="text" placeholder="Enter Email" value={{ $user->email }} @readonly(true)>
                </div>


                <div class="mt-16">
                    <form method="post" action="{{ route('profile.password.update') }}">
                        @method('PUT')
                        @csrf
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="current_password">
                                Current Password
                            </label>
                            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="current_password" name="current_password" type="password" placeholder="Enter current password">
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="new_password">
                                New Password
                            </label>
                            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="new_password" name="new_password" type="password" placeholder="Enter new password">
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="confirm_password">
                                Confirm Password
                            </label>
                            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="confirm_password" name="confirm_password" type="password" placeholder="Confirm new password">
                        </div>
                        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline mt-4" type="submit">
                            Change Password
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
@endsection
