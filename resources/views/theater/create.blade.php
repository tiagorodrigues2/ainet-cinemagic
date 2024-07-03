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


        <form class="max-w-md mx-auto" action="{{ route('theaters.create') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <span class="text-lg font-semibold">Photo</span>
            <div class="mb-4 flex">
                <input type="file" name="photo" class="border border-gray-300 px-4 py-2 w-full">
            </div>

            <span class="text-lg font-semibold">Name</span>
            <input type="text" name="name" class="border border-gray-300 px-4 py-2 w-full mb-4" value="{{ old('name', '') }}">

            <span class="text-lg font-semibold">Number of Rows</span>
            <input type="number" name="rows" class="border border-gray-300 px-4 py-2 w-full mb-4" value="{{ old('rows', 5) }}">

            <span class="text-lg font-semibold">Number of Columns</span>
            <input type="number" name="cols" class="border border-gray-300 px-4 py-2 w-full mb-4" value="{{ old('columns', 8) }}">

            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Create
            </button>
        </form>

    </div>


@endsection
