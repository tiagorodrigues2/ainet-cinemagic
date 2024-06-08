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

            <div class="flex justify-between itens-start">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Save Changes
                </button>

                <form action="{{ route('theaters.delete', ['id' => $theater->id]) }}" method="post">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                        Delete Theater
                    </button>
                </form>
            </div>
        </form>

        <div class="flex w-full justify-center mt-12">
            <form method="post" action={{ route('theaters.row.add') }}>
                @csrf
                <input type="hidden" name="theater_id" value="{{ $theater->id }}">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded me-4">
                    Add Seat Row
                </button>
            </form>
            <form method="post" action={{ route('theaters.col.add') }}>
                @csrf
                <input type="hidden" name="theater_id" value="{{ $theater->id }}">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Add Seat Column
                </button>
            </form>
        </div>

        <div class="flex-col w-full mb-4 mt-4">
            <div class="flex w-full justify-center">
                <table>
                    <tbody>
                        <tr>
                            <td></td>
                            @for ($n = 0; $n < $numberOfColumns; $n++)
                                <td class="text-red-800 font-bold text-center">
                                    <form method="post" action="{{ route('theaters.col.delete', ['id' => $theater->id, 'col' => $n + 1]) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-4 py-2 hover:bg-red-100">X</button>
                                    </form>
                                </td>
                            @endfor
                        </tr>
                        @foreach ($seats as $row)
                            <tr>
                                <td class="text-red-800 font-bold text-center">
                                    <form method="post" action="{{ route('theaters.row.delete', ['id' => $theater->id, 'row' => $row->first()->row]) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-4 py-2 hover:bg-red-100">X</button>
                                    </form>
                                </td>
                                @foreach ($row as $seat)
                                    <td class="border border-gray-300 bg-white px-4 py-2">
                                        {{ $seat->row }}{{ $seat->seat_number }}
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>

    </div>


@endsection
