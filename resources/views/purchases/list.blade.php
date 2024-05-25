@extends('layouts.main')



@section('content')
    <div class="container mx-auto">

        @if (count($purchases) <= 0)
            <x-toast type="info" :message="__('No purchases found.')" />
        @else
        <table class="min-w-full bg-white border border-gray-300">
            <thead>
                <tr>
                    <th class="px-4 py-2 border-b text-start">Payment List</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($purchases as $p)
                    @php
                        $tickets = $p->tickets()->get();
                    @endphp
                    <tr class="bg-amber-100">
                        <td class="px-4 py-2 border-b flex-col" colspan="2">
                            <div>Payment Method: <span class="font-semibold">{{ $p->payment_type }}</span></div>
                            <div>Ref: <span class="font-semibold">{{ $p->payment_ref }}</span></div>
                        </td>
                    </tr>

                    @foreach ($tickets as $t)
                        @php
                            $s = $t->screening()->first();
                            $movie = $s->movie()->first();
                            $theater = $s->theater()->first()->name;
                            $seat = $t->seat()->first();
                        @endphp
                        <tr class="odd:bg-gray-100">
                            <td colspan="2">
                                <div class="px-16 p-1 flex justify-between">
                                    <span>Ticket <span class="font-semibold">#{{ $t->id }}</span></span>
                                    <span class="font-semibold">{{ $movie->title }}</span>
                                    <span>Theater: <span class="font-semibold">{{ $theater }}</span></span>
                                    <span>Seat: <span class="font-semibold">{{ $seat->row }}-{{ $seat->seat_number }}</span></span>
                                    <span class="font-semibold">{{ $s->date }} {{ $s->start_time }}</span>
                                    <span>Price: <span class="font-semibold text-green-800">{{ $t->price }} €</span></span>
                                </div>
                            </td>
                        </tr>
                    @endforeach

                    @if (count($tickets) > 0)
                        <tr>
                            <td class="py-2 border-b text-end px-16">
                                <span>Total:</span> <span class="text-green-800 font-bold">{{ $p->total_price }} €</span>
                            </td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
        @endif

    </div>
@endsection
