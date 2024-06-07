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


        <form class="max-w-md mx-auto" action="{{ route('theaters.save', ['id' => $theater->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <span class="text-lg font-semibold">Photo</span>
            <div class="mb-4 flex">
            <img src="{{ asset('storage/theater/' . $theater->photo_filename) }}" class="w-20 h-20 object-cover">
            <input type="file" name="photo" class="border border-gray-300 px-4 py-2 w-full" onchange="this.form.submit()">
            </div>

            <span class="text-lg font-semibold">Name</span>
            <input type="text" name="name" class="border border-gray-300 px-4 py-2 w-full mb-4" value="{{ $theater->name }}">

            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Save Changes
            </button>
        </form>
        </form>

        <div class="flex-col justify-between mb-4 mt-16">
            <span class="text-lg font-semibold">Manage Seats</span>
            <div class="flex">
                <button type="button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded me-8">
                    Add Seat Row
                </button>
                <button type="button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Add Seat Column
                </button>
            </div>
        </div>


    </div>


@endsection
