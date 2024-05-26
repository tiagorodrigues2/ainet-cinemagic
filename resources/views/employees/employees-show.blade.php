@extends('layouts.main')

@section('content')
    <div class="max-w-md mx-auto">

        @isset($sucesso)
            <x-toast type="success" :message="$sucesso" />
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
                    <img src="{{ asset('storage/photos/' . $employee->photo_filename) }}" alt="{{ $employee->name }}" class="w-16 h-16 rounded-full mr-4 mb-8">

                    @if ($isAtual == false)
                        <div class="flex-col justify-center items-center mt-8">
                            <form action="{{ route('customers.toggle-block', ['id' => $employee->id]) }}" method="post">
                                @csrf
                                <button class="mb-4 bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                                    {{ $employee->blocked ? 'Unblock' : 'Block' }}
                                </button>
                            </form>
                            <form action="{{ route('customers.delete', ['id' => $employee->id]) }}" method="post">
                                @method('DELETE')
                                @csrf
                                <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                                    Delete
                                </button>
                            </form>
                        </div>
                    @endif


                </div>

                <form action="{{ route('employees.save') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" value="{{ $employee->id }}" readonly>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="type" @readonly($isAtual)>
                            Name
                        </label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="name" name="name" type="text" placeholder="Enter name" value={{ $employee->name }} @readonly($isAtual)>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="type">
                            Email
                        </label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="email" name="email" type="text" placeholder="Enter Email" value={{ $employee->email }} @readonly($isAtual)>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="type">
                            Type
                        </label>
                        @if ($isAtual)
                            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" type="text" placeholder="Enter name" value="{{ $employee->type == 'E' ? 'Employee' : 'Admin' }}" readonly>
                        @else
                            <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="type" name="type" style="cursor: pointer">
                                @if ($employee->type == 'E')
                                    <option value="E" selected>Employee</option>
                                    <option value="A">Admin</option>
                                @else
                                    <option value="E">Employee</option>
                                    <option value="A" selected>Admin</option>
                                @endif
                            </select>
                        @endif
                    </div>

                    @if ($isAtual == false)
                        <div class="mb-8">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="type">
                                Change Photo
                            </label>
                            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="photo" name="photo" type="file" accept=".png, .jpeg, .jpg">
                        </div>

                        <div class="flex justify-between flex-wrap">
                            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                                Save
                            </button>
                        </div>
                    @endif
                </form>

            </div>
        </div>

    </div>
@endsection
