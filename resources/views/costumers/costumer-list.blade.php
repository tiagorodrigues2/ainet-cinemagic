@extends('layouts.main')

@section('content')
    <div class="container mx-auto pt-16">

        <form action="{{ route('costumers') }}" method="GET">
            <div class="mb-4 flex-row">
                <input type="text" value="{{ $search }}" name="search" class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 w-300" placeholder="Search">
                <button type="submit" class="px-4 py-2 bg-indigo-500 text-white rounded-md hover:bg-indigo-600">Search</button>
            </div>
        </form>

        <table class="min-w-full bg-white">
            <tbody>
                @foreach ($costumers as $index => $costumer)
                    <tr class="even:bg-gray-100">
                        <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-300">
                            <div class="flex items-center">
                                <div class="ml-4">
                                    <div class="text-sm leading-5 font-medium text-gray-900">{{ $costumer->id }}</div>
                                </div>
                            </div>
                        </td>
                        @isset($costumer->photo_filename)
                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-300">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <img class="h-10 w-10 rounded-full" src="{{ asset('storage/photos/' . $costumer->photo_filename) }}" alt="{{ $costumer->name }}">
                                    </div>
                                </div>
                            </td>
                        @else
                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-300">
                            </td>
                        @endif
                        <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-300">
                            <div class="text-sm leading-5 font-medium text-gray-900">{{ $costumer->name }}</div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
