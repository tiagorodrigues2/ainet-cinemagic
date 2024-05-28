
@php
    $screening = $ticket->screening()->first();
    $seat = $ticket->seat()->first();
@endphp

<div>

    <span style="font-size: 22px; font-weight: bold; margin-bottom: 12px">
        CineMagic Ticket
    </span>
    <hr>
    <div>
        Status:
    </div>
    <div style="margin-bottom: 8px; font-weight: 700">
        {{ $ticket->status }}
    </div>
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
