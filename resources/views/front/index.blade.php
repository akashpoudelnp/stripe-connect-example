<x-master-layout>
    <h1 class="text-3xl font-bold text-center">Clients</h1>

    <table class="table-auto w-full mt-5">
        <thead>
        <tr>
            <th class="px-4 py-2">ID</th>
            <th class="px-4 py-2">Name</th>
            <th class="px-4 py-2">Website</th>
            <th class="px-4 py-2">Stripe</th>
            <th class="px-4 py-2">Events</th>
        </tr>
        </thead>
        <tbody>
        @foreach($clients as $client)
            <tr>
                <td class="border px-4 py-2">{{ $client->id }}</td>
                <td class="border px-4 py-2">{{ $client->name }}</td>
                <td class="border px-4 py-2">{{ $client->website }}</td>
                <td class="border px-4 py-2">
                    @if($client->stripe_account_status === 'connected')
                        <span class="text-green font-bold">Connected</span>
                    @elseif($client->stripe_account_status === 'not_connected')
                        <a href="{{ route('stripe.connect.connect', $client->id) }}" class="text-blue-500">Connect Link</a>
                    @elseif($client->stripe_account_status === 'pending')
                        <span class="text-yellow-500 font-bold">Pending</span>
                        <a href="{{ route('stripe.connect.connect', $client->id) }}" class="text-blue-500">Re-Connect Link</a>
                    @endif
                </td>
                <td class="border px-4 py-2">
                    @foreach($client->events as $event)
                        <a class="underline" href="{{ route('events.bookings.create',$event) }}">{{ $event->name }}</a>
                    @endforeach
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</x-master-layout>
