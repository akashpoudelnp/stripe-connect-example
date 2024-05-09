<x-master-layout>
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold">Events</h2>
        <a href="{{ route('admin.events.create') }}"
           class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Create Event</a>
    </div>

    <table class="table-auto w-full mt-5">
        <thead>
        <tr>
            <th class="px-4 py-2">ID</th>
            <th class="px-4 py-2">Name</th>
            <th class="px-4 py-2">Date</th>
            <th class="px-4 py-2">Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach($events as $event)
            <tr>
                <td class="border px-4 py-2">{{ $event->id }}</td>
                <td class="border px-4 py-2">{{ $event->name }}</td>
                <td class="border px-4 py-2">{{ $event->date }}</td>
                <td class="border px-4 py-2">
                    <a href="{{ route('admin.events.edit', $event) }}"
                       class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Edit</a>
                    <form class="inline" action="{{ route('admin.events.destroy', $event) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Delete
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</x-master-layout>
