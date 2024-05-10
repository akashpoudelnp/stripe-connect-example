<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Event;
use Illuminate\Http\Request;

class AdminEventController extends Controller
{
    public function index()
    {
        $events = Event::all();

        return view('admin.events.index', compact('events'));
    }

    public function create()
    {
        $clients = Client::all();

        return view('admin.events.create', compact('clients'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'         => 'required',
            'description'  => 'required',
            'ticket_price' => 'required',
            'date'         => 'required',
            'location'  => 'required',
            'client_id' => 'required',
        ]);

        $event = Event::create($data);

        return redirect()->route('admin.events.index');
    }

    public function edit(Event $event)
    {
        $clients = Client::all();

        return view('admin.events.edit', compact('event', 'clients'));
    }

    public function update(Request $request, Event $event)
    {
        $data = $request->validate([
            'name'         => 'required',
            'description'  => 'required',
            'ticket_price' => 'required',
            'date'         => 'required',
            'location'     => 'required',
            'client_id'    => 'required',
        ]);

        $event->update($data);

        return redirect()->route('admin.events.index');
    }

    public function destroy(Event $event)
    {
        $event->delete();

        return redirect()->route('admin.events.index');
    }
}
