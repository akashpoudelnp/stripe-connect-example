<?php

namespace App\Http\Controllers;

use App\Facades\Stripe;
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

        $stripeProduct = Stripe::createProduct([
            'name'        => $event->name,
            'description' => $event->description,
            'metadata'    => [
                'event_id' => $event->id,
                'date'     => $event->date,
                'location' => $event->location,
            ]
        ]);

        $stripePrice = Stripe::createPrice([
            'product'     => $stripeProduct->id,
            'unit_amount' => $event->ticket_price * 100,
            'currency'    => 'usd',
        ]);

        $event->update([
            'stripe_product_id' => $stripeProduct->id,
            'stripe_price_id'   => $stripePrice->id,
        ]);

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

        if ($event->ticket_price !== floatval($data['ticket_price'])) {
            Stripe::updatePrice($event->stripe_price_id, [
                'active' => false,
            ]);

            $data['stripe_price_id'] = Stripe::createPrice([
                'product'     => $event->stripe_product_id,
                'unit_amount' => $data['ticket_price'] * 100,
                'currency'    => 'usd',
            ])->id;
        }

        if ($event->name !== $data['name'] || $event->description !== $data['description']) {
            Stripe::updateProduct($event->stripe_product_id, [
                'name'        => $data['name'],
                'description' => $data['description'],
            ]);
        }

        $event->update($data);

        return redirect()->route('admin.events.index');
    }

    public function destroy(Event $event)
    {
        $event->delete();

        return redirect()->route('admin.events.index');
    }
}
