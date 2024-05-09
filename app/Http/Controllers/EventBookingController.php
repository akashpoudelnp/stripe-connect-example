<?php

namespace App\Http\Controllers;

use App\Facades\Stripe;
use App\Models\Booking;
use App\Models\Event;
use Illuminate\Http\Request;

class EventBookingController extends Controller
{
    public function create(Event $event)
    {
        return view('front.booking.create', compact('event'));
    }

    public function store(Request $request, Event $event)
    {
        $data = $request->validate([
            'name'     => 'required',
            'email'    => 'required|email',
            'quantity' => 'required|numeric|min:1',
        ]);

        $data['unit_cost'] = $event->ticket_price;

        $booking = $event->bookings()->create($data);

        return to_route('events.bookings.payment', $booking->id);
    }

    public function payment(Booking $booking)
    {
        if ($booking->status === 'completed') {
            return view('front.booking.success', compact('booking'));
        }

        return view('front.booking.payment', compact('booking'));
    }

    public function initiatePayment(Booking $booking)
    {
        return response()->json([
            'clientSecret' => $booking->createStripeCheckoutSession()->client_secret,
        ]);
    }

    public function success(Booking $booking)
    {
        if (!$booking->checkout_session_id) {
            return redirect()->route('events.bookings.payment', $booking);
        }

        $checkout_session = Stripe::getClient()->checkout->sessions->retrieve($booking->checkout_session_id);

        if ($checkout_session->payment_status !== 'paid') {
            return redirect()->route('events.bookings.payment', $booking);
        }

        $booking->update([
            'status' => 'completed'
        ]);

        return view('front.booking.success', compact('booking'));
    }

    public function callback(Request $request)
    {
        $checkout_session_id = $request->query('checkout_session_id');


        $booking = Booking::where('checkout_session_id', $checkout_session_id)->firstOrFail();

        $checkout_session = Stripe::getClient()->checkout->sessions->retrieve($checkout_session_id);

        if ($checkout_session->payment_status !== 'paid') {
            return redirect()->route('events.bookings.payment', $booking);
        }

        $booking->update([
            'status' => 'completed'
        ]);

        return view('front.booking.success', compact('booking'));
    }
}
