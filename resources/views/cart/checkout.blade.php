@extends('layouts.main')

@section('head')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tipo_pagamento = document.getElementById('tipo_pagamento');

            const form_paypal = document.getElementById('form_paypal');
            const form_visa = document.getElementById('form_visa');
            const form_mbway = document.getElementById('form_mbway');

            form_paypal.style.display = tipo_pagamento.value === 'paypal' ? 'block' : 'none';
            form_visa.style.display = tipo_pagamento.value === 'cartao' ? 'block' : 'none';
            form_mbway.style.display = tipo_pagamento.value === 'mbway' ? 'block' : 'none';

            tipo_pagamento.addEventListener('change', function() {
                form_paypal.style.display = tipo_pagamento.value === 'paypal' ? 'block' : 'none';
                form_visa.style.display = tipo_pagamento.value === 'cartao' ? 'block' : 'none';
                form_mbway.style.display = tipo_pagamento.value === 'mbway' ? 'block' : 'none';
            });

        });

    </script>

@endsection

@section('content')

    <div class="container mx-auto flex-col justify-center">

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

        @if(count($cart) <= 0)
            <x-toast type="info" message="No items in the cart" />
        @endif

        <h1 class="text-3xl font-bold mb-4">Checkout</h1>

        @if(count($cart) > 0)
            <div class="flex justify-end mb-4">
                <form action="{{ route('cart.clear') }}" method="post">
                    @method('PATCH')
                    @csrf
                    <button type="submit" class="bg-gray-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                        Clear Cart
                    </button>
                </form>
            </div>
        @endif

        <table class="min-w-full bg-white border border-gray-300 mb-8">
            <thead>
                <tr>
                    <th class="px-4 py-2 border-b text-start">Movie</th>
                    <th class="px-4 py-2 border-b text-start">Theater</th>
                    <th class="px-4 py-2 border-b text-start">Starts at</th>
                    <th class="px-4 py-2 border-b text-start">Seat</th>
                    <th class="px-4 py-2 border-b text-end">Price</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($cart as $item)
                    <tr class="odd:bg-slate-100">
                        <td class="px-4 py-2 border-b">{{ $item['movie'] }}</td>
                        <td class="px-4 py-2 border-b">{{ $item['theater'] }}</td>
                        <td class="px-4 py-2 border-b">{{ $item['screening_date'] }}</td>
                        <td class="px-4 py-2 border-b">{{ $item['seat'] }}</td>
                        <td class="px-4 py-2 border-b text-end">{{ $item['ticket_price'] }} €</td>
                        <td class="px-4 py-2 border-b text-end">
                            <form action="{{ route('cart.remove', ['seat_id' => $item['seat_id']]) }}" method="post">
                                @method('DELETE')
                                @csrf
                                <button type="submit" class="bg-gray-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                    Remove
                                </button>
                            </form>
                    </tr>
                @endforeach
                <tr class="bg-amber-100">
                    <td colspan="4" class="text-start ps-4 font-bold">Total:</td>
                    <td colspan="1" class="text-end text-green-700 font-bold pr-4">{{ $total }} €</td>
                    <td></td>
                </tr>
            </tbody>
        </table>

        @if(count($cart) > 0)
            <form action="{{ route('cart.checkout.submit') }}" method="post">
                @csrf
                <div class="flex justify-between items-end mb-12">
                    <div>

                        @if (!auth()->check())
                            <div>
                                <input type="text" id="customer_name" name="customer_name" class="border border-gray-300 rounded px-3 py-2 mb-8" style="width: 400px" placeholder="Costumer Name" value="{{ old('customer_name') }}">
                                <input type="text" id="customer_email" name="customer_email" class="border border-gray-300 rounded px-3 py-2 mb-8" style="width: 400px" placeholder="Costumer Email" value="{{ old('customer_email') }}">
                            </div>

                        @endif

                        <label for="tipo_pagamento" class="block text-sm font-medium text-gray-700">Payment Method</label>
                        <select id="tipo_pagamento" name="tipo_pagamento" class="border border-gray-300 rounded px-3 py-2" style="cursor: pointer">
                            <option value="paypal" @selected( old('tipo_pagamento', isset($customer) ? $customer->payment_type : null ) == 'paypal')>Paypal</option>
                            <option value="cartao" @selected( old('tipo_pagamento', isset($customer) ? $customer->payment_type : null ) == 'cartao')>Cartão de Crédito</option>
                            <option value="mbway" @selected( old('tipo_pagamento', isset($customer) ? $customer->payment_type : null ) == 'mbway')>MBWay</option>
                        </select>

                        <div id="form_paypal" class="mt-8">
                            <input type="text" name="paypal_email" id="paypal_email" class="border border-gray-300 rounded px-3 py-2" style="width: 400px" placeholder="Paypal Email" value={{ old('paypal_email', isset($customer) ? $customer->payment_ref : null) }}>
                        </div>

                        <div id="form_visa" class="mt-8">
                            <input type="number" name="visa_number" id="visa_number" class="border border-gray-300 rounded px-3 py-2" style="width: 350px" placeholder="Card Number" value={{ old('visa_number', isset($customer) ? $customer->payment_ref : null) }}>
                            <input type="number" name="visa_cvv" id="visa_cvv" class="border border-gray-300 rounded px-3 py-2" placeholder="Security Code (CVV)">
                        </div>

                        <div id="form_mbway" class="mt-8">
                            <input type="number" name="mbway_phone" id="mbway_phone" class="border border-gray-300 rounded px-3 py-2" style="width: 250px" placeholder="Phone Number" value={{ old('mbway_phone', isset($customer) ? $customer->payment_ref : null) }}>
                        </div>

                        <div class="mt-8">
                            <input name="nif" id="nif" class="border border-gray-300 rounded px-3 py-2" style="width: 250px" placeholder="NIF" value={{ old('nif', isset($customer) ? $customer->nif : null) }}>
                        </div>

                    </div>
                    <button type="submit" class="bg-green-700 hover:bg-green-800 text-white font-bold py-2 px-4 rounded">
                        Checkout
                    </button>
                </div>
            </form>
        @endif
    </div>

@endsection
