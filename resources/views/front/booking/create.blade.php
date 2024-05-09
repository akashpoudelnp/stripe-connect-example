<x-master-layout>
    <x-slot:title>Book {{$event->name}}</x-slot:title>
    <h1 class="text-3xl font-bold text-center">Book Event</h1>

    <dl class="mt-5 w-1/2 mx-auto">
        <dt class="text-lg font-bold">Name</dt>
        <dd class="text-lg">{{ $event->name }}</dd>
        <dt class="text-lg font-bold">Date</dt>
        <dd class="text-lg">{{ $event->date }}</dd>
        <dt class="text-lg font-bold">Location</dt>
        <dd class="text-lg">{{ $event->location }}</dd>
        <dt class="text-lg font-bold">Ticket Price</dt>
        <dd class="text-lg">{{ $event->ticket_price }} USD</dd>
    </dl>


    <div class="w-1/2 mx-auto mt-5">
        <form action="{{ route('events.bookings.store',$event) }}" method="post">
            @csrf
            <div class="mb-4">
                <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Name</label>
                <input type="text" name="name" id="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div class="mb-4">
                <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                <input type="email" name="email" id="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div class="mb-4">
                <label for="quantity" class="block text-gray-700 text-sm font-bold mb-2">Quantity</label>
                <input type="number" name="quantity" id="quantity" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div class="mb-4">
                <button type="submit"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Payment
                </button>
            </div>
        </form>
    </div>
</x-master-layout>
