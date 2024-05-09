<?php

namespace App\Models;

use App\Facades\Stripe;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Stripe\Checkout\Session;

class Booking extends Model
{
    protected $fillable = [
        'event_id',
        'quantity',
        'unit_cost',
        'email',
        'name',
        'status',
        'checkout_session_id',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function createStripeCheckoutSession(): Session
    {
        $checkout_session = Stripe::getClient()->checkout->sessions->create([
            'mode'                => 'payment',
            'ui_mode'             => 'embedded',
            'line_items'          => [[
                                          'price'    => $this->event->stripe_price_id,
                                          'quantity' => $this->quantity,
                                      ]],
            'return_url'          => route('events.bookings.payment.callback') . '?checkout_session_id={CHECKOUT_SESSION_ID}',
            'client_reference_id' => $this->id,
            'customer_email'      => $this->email,
            'payment_intent_data' => [
                'application_fee_amount' => config('services.stripe.application_fee'),
                'transfer_data'          => ['destination' => $this->event->client->stripe_account_id],
            ],
        ]);

        // There are 2 ways of deciding how amounts are split between the platform and the client
        // 1. This way the payout is done immediately and application_fee_amount is deducted from the payment and sent to the platform
        //  'payment_intent_data' => [
        //                'application_fee_amount' => config('stripe.fees.application_fee'),
        //                'transfer_data'          => ['destination' => $this->event->client->stripe_account_id],
        //            ],
        // And another is
//        'payment_intent_data' => [
//                                'on_behalf_of'   => $this->event->client->stripe_account_id,
//                                'transfer_group' => 'BANTIX-CLIENT#'.$this->event->client_id,
//                            ],


        $this->update([
            'checkout_session_id' => $checkout_session->id,
            'status'              => 'payment_pending'
        ]);

        return $checkout_session;
    }
}
