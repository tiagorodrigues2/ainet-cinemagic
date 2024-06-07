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


        <table class="min-w-full bg-white">
            <thead>
                <tr>
                    <th class="px-4 py-2 text-start">Image</th>
                    <th class="px-4 py-2 text-start">Name</th>
                    <th class="px-4 py-2 text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($theaters as $t)
                    <tr class="odd:bg-gray-100">
                        <td class="p-2 min-h-24 text-center">
                            @if (!empty($t->photo_filename))
                                <img src="{{ asset('storage/theater/' . $t->photo_filename) }}" alt="{{ $t->name }}" class="w-20 h-20 object-cover rounded-full">
                            @endif
                        </td>
                        <td class="p-4">
                            <a href="#">{{ $t->name }}</a>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('theaters.show', $t->id) }}" class="px-4 py-2 bg-blue-500 text-white rounded-md">View</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>


    </div>


@endsection
