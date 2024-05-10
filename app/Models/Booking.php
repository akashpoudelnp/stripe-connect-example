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
        'payment_intent_id',
        'charge_type'
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function createStripeCheckoutSession(): Session
    {
        $options = [];
        $payload = [
            'mode'                => 'payment',
            'ui_mode'             => 'embedded',
            'line_items'          => [
                [
                    'price_data' => [
                        'currency'     => 'usd',
                        'product_data' => [
                            'name'        => $this->event->name,
                            'description' => $this->event->description,
                        ],
                        'unit_amount'  => $this->unit_cost * 100,
                    ],
                    'quantity'   => $this->quantity,
                ]
            ],
            'return_url'          => route('events.bookings.payment.callback') . '?checkout_session_id={CHECKOUT_SESSION_ID}',
            'client_reference_id' => $this->id,
            'customer_email'      => $this->email,
            'payment_intent_data' => [
                'application_fee_amount' => config('stripe.fees.application_fee'),
            ],
        ];

        if ($this->charge_type === 'destination') {
            $payload['payment_intent_data']['transfer_data'] = ['destination' => $this->event->client->stripe_account_id];
        }else {
            $options['stripe_account'] = $this->event->client->stripe_account_id;
        }

        $checkout_session = Stripe::getClient()->checkout->sessions->create($payload, $options);

        $this->update([
            'checkout_session_id' => $checkout_session->id,
            'payment_intent_id'   => $checkout_session->payment_intent,
            'status'              => 'payment_pending'
        ]);

        return $checkout_session;
    }
}
