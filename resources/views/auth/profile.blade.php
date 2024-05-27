@extends('layouts.main')

@section('head')

    <script>

        document.addEventListener('DOMContentLoaded', function() {
            const payment_type = document.getElementById('payment_type');

            if (payment_type.value == 'VISA') {
                payment_ref.placeholder = 'Enter Credit Card Number';
                ref_label.innerText = 'Credit Card Number';
            } else if (payment_type.value == 'PAYPAL') {
                payment_ref.placeholder = 'Enter Paypal Email';
                ref_label.innerText = 'Paypal Email';
            } else if (payment_type.value == 'MBWAY') {
                payment_ref.placeholder = 'Enter MBWay Phone Number';
                ref_label.innerText = 'MBWay Phone Number';
            }

            payment_type.addEventListener('change', function() {
                const payment_ref = document.getElementById('payment_ref');
                const ref_label = document.getElementById('ref_label');

                payment_ref.value = '';
                if (payment_type.value == 'VISA') {
                    payment_ref.placeholder = 'Enter Credit Card Number';
                    ref_label.innerText = 'Credit Card Number';
                } else if (payment_type.value == 'PAYPAL') {
                    payment_ref.placeholder = 'Enter Paypal Email';
                    ref_label.innerText = 'Paypal Email';
                } else if (payment_type.value == 'MBWAY') {
                    payment_ref.placeholder = 'Enter MBWay Phone Number';
                    ref_label.innerText = 'MBWay Phone Number';
                }
            });
        });

    </script>

@endsection

@section('content')
    <div class="max-w-md mx-auto flex-col justify-center items-center">
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
    </div>

    <div class="max-w-md mx-auto flex justify-center items-start">

        <div class="bg-white shadow-md rounded-lg p-6 mb-8">
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

                @if(!$user->isCustomer())
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
                @endif

                <div class="mt-16">
                    <form method="post" action="{{ route('profile.password.update') }}">
                        @method('PUT')
                        @csrf
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-semibold mb-2" for="current_password">
                                Current Password
                            </label>
                            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="current_password" name="current_password" type="password" placeholder="Enter current password">
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-semibold mb-2" for="new_password">
                                New Password
                            </label>
                            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="new_password" name="new_password" type="password" placeholder="Enter new password">
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-semibold mb-2" for="confirm_password">
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

        @if(isset($customer) && $customer)

            <div class="bg-white shadow-md rounded-lg p-6 ms-8">
                <label class="block text-gray-700 font-bold mb-6">
                    Customer & Payment Information
                </label>
                <form method="post" action="{{ route('profile.update') }}">
                    @method('PUT')
                    @csrf


                    <div class="flex-col items-center mb-4">
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-semibold mb-2">
                                Name <span class="text-red-700">*</span>
                            </label>
                            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" style="width: 340px" id="name" name="name" type="text" placeholder="Enter Name" required value="{{ $user->name }}">
                        </div>
                    </div>

                    <div class="flex-col items-center mb-4">
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-semibold mb-2">
                                Email <span class="text-red-700">*</span>
                            </label>
                            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" style="width: 340px" id="email" name="email" type="text" placeholder="Enter Email" required value="{{ $user->email }}">
                        </div>
                    </div>

                    <div class="flex-col items-center mb-4">
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-semibold mb-2">
                                NIF
                            </label>
                            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" style="width: 340px" id="nif" name="nif" type="text" max="9" placeholder="Enter NIF" value={{ $customer->nif }}>
                        </div>
                    </div>

                    <div class="flex-col items-center mb-4">
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-semibold mb-2">
                                Prefered Payment Method
                            </label>
                            <div class="relative">
                                <select id="payment_type" name="payment_id" class="block appearance-none w-full bg-white border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline" style="cursor: pointer">
                                    <option @selected($customer->payment_type == 'VISA') value="VISA">Credit Card</option>
                                    <option @selected($customer->payment_type == 'PAYPAL') value="PAYPAL">Paypal</option>
                                    <option @selected($customer->payment_type == 'MBWAY') value="MBWAY">MBWay</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path d="M10 12l-6-6 1.5-1.5L10 9l4.5-4.5L16 6l-6 6z"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex-col items-center mb-4">
                        <div class="mb-4">
                            <label id="ref_label" class="block text-gray-700 text-sm font-semibold mb-2" for="payment_ref">

                            </label>
                            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" style="width: 340px" id="payment_ref" name="payment_ref" type="text" value={{ $customer->payment_ref }}>
                        </div>
                    </div>

                    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline mt-4" type="submit">
                        Save Changes
                    </button>
                </form>
            </div>

        @endif

    </div>
@endsection
