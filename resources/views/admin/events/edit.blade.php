<x-master-layout>
    <h1 class="text-3xl font-bold text-center">Edit Event</h1>

    <form action="{{ route('admin.events.update', $event) }}" method="POST" class="mt-5">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Name</label>
            <input type="text" name="name" id="name" value="{{ $event->name }}"
                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>

        <div class="mb-4">
            <label for="location" class="block text-gray-700 text-sm font-bold mb-2">Location</label>
            <input type="text" name="location" id="location" value="{{ $event->location }}"
                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>

        <div class="mb-4">
            <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Description</label>
            <textarea name="description" id="description"
                      class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ $event->description }}</textarea>
        </div>

        <div class="mb-4">
            <label for="client_id" class="block text-gray-700 text-sm font-bold mb-2">Client</label>

            <select name="client_id" id="client_id"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                @foreach($clients as $client)
                    <option value="{{ $client->id }}" {{ $client->id == $event->client_id ? 'selected' : '' }}>{{ $client->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label for="date" class="block text-gray-700 text-sm font-bold mb-2 ">Date</label>
            <input type="date" name="date" id="date" value="{{ $event->date->format('Y-m-d') }}"
                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>

        <div class="mb-4">
            <label for="ticket_price" class="block text-gray-700 text-sm font-bold mb-2">Ticket Price</label>
            <input type="number" name="ticket_price" id="ticket_price" value="{{ $event->ticket_price }}"
                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>

        <div class="mb-4">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Update Event</button>
        </div>
    </form>
</x-master-layout>
