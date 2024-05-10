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
        if (config('stripe.charge_type') === 'direct') {
            return $this->createStripeCheckoutSessionAsDirectCharges();
        }

        return $this->createStripeCheckoutSessionAsDestinationCharges();
    }

    private function createStripeCheckoutSessionAsDestinationCharges(): Session
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
                'application_fee_amount' => config('stripe.fees.application_fee'),
                'transfer_data'          => ['destination' => $this->event->client->stripe_account_id],
            ],
        ]);

        $this->update([
            'checkout_session_id' => $checkout_session->id,
            'status'              => 'payment_pending'
        ]);

        return $checkout_session;
    }

    private function createStripeCheckoutSessionAsDirectCharges(): Session
    {
        // This differs from the previous method in that we are creating a new price for the event directly as
        // We cannot crud the product and prices for the connected account

        $checkout_session = Stripe::getClient()->checkout->sessions->create([
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
        ], [
            'stripe_account' => $this->event->client->stripe_account_id,
        ]);

        $this->update([
            'checkout_session_id' => $checkout_session->id,
            'status'              => 'payment_pending'
        ]);

        return $checkout_session;
    }

}
