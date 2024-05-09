<x-master-layout>
    <x-slot:title>Booked Successfully | {{$booking->event->name}}</x-slot:title>

    <h1 class="text-3xl font-bold text-center">Booked Successfully #BOOKING-{{$booking->id}}</h1>

    <dl class="mt-5 w-1/2 mx-auto">
        <dt class="text-lg font-bold">Name</dt>
        <dd class="text-lg">{{ $booking->event->name }}</dd>
        <dt class="text-lg font-bold">Date</dt>
        <dd class="text-lg">{{ $booking->event->date }}</dd>
        <dt class="text-lg font-bold">Location</dt>
        <dd class="text-lg">{{ $booking->event->location }}</dd>
        <dt class="text-lg font-bold">Ticket Price</dt>
        <dd class="text-lg">{{ $booking->event->ticket_price }} USD</dd>
    </dl>

    <h3 class="text-2xl font-bold text-center mt-5">Booking Details</h3>

    <dl class="mt-5 w-1/2 mx-auto">
        <dt class="text-lg font-bold">Name</dt>
        <dd class="text-lg">{{ $booking->name }}</dd>
        <dt class="text-lg font-bold">Email</dt>
        <dd class="text-lg">{{ $booking->email }}</dd>
        <dt class="text-lg font-bold">Quantity</dt>
        <dd class="text-lg">{{ $booking->quantity }}</dd>
        <dt class="text-lg font-bold">Amount</dt>
        <dd class="text-lg">{{ $booking->quantity* $booking->unit_cost }} USD</dd>
    </dl>
</x-master-layout>
