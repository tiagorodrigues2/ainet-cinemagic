
@php
    $screening = $ticket->screening()->first();
    $seat = $ticket->seat()->first();
    $purchase = $ticket->purchase()->first();
@endphp

<div>

    <span style="font-size: 22px; font-weight: bold; margin-bottom: 12px">
        CineMagic Ticket
    </span>
    <hr>

    <div>
        Customer:
    </div>
    <div style="margin-bottom: 8px; font-weight: 700">
        {{ $purchase->customer_name }}
    </div>
    <div>
        Email:
    </div>
    <div style="margin-bottom: 8px; font-weight: 700">
        {{ $purchase->customer_email }}
    </div>

    <div>
        NIF:
    </div>
    <div style="margin-bottom: 8px; font-weight: 700">
        {{ $purchase->nif }}
    </div>

    <hr>
    <div>
        Movie:
    </div>
    <div style="margin-bottom: 8px; font-weight: 700">
        {{ $screening ->movie()->first()->title }}
    </div>
    <div>
        Theater:
    </div>
    <div style="margin-bottom: 8px; font-weight: 700">
        {{ $screening->theater()->first()->name }}
    </div>
    <div>
        Start Time:
    </div>
    <div style="margin-bottom: 8px; font-weight: 700">
        {{ $screening->date }} {{ $screening->start_time }}
    </div>
    <div>
        Seat:
    </div>
    <div style="margin-bottom: 8px; font-weight: 700">
        {{ $seat->row }}{{ $seat->seat_number }}
    </div>

    <hr>

    <div style="margin-bottom: 8px; font-weight: 700; font-size: 18px">
       Ticket ID #{{ $ticket->id }}
    </div>

</div>
