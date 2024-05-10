<?php

namespace App\Services;

use App\Contracts\IStripe;
use App\Facades\Stripe;
use Stripe\Account;
use Stripe\Checkout\Session;
use Stripe\Price;
use Stripe\Product;
use Stripe\StripeClient;

class StripeService implements IStripe
{
    protected StripeClient $client;

    public function __construct()
    {
        $this->client = new StripeClient(config('stripe.keys.secret'));
    }

    public function createConnectAccount(array $data = []): Account
    {
        return $this->client->accounts->create($data);
    }

    public function retrieveConnectAccount(string $accountId): Account
    {
        return $this->client->accounts->retrieve($accountId);
    }

    public function retrieveCheckoutSession(string $checkout_session_id, string $connected_account_id = null): Session
    {
        // If there is connected account id then it is a direct charge
        if (!$connected_account_id) {
            return Stripe::getClient()->checkout->sessions->retrieve($checkout_session_id);
        }

        return Stripe::getClient()->checkout->sessions->retrieve($checkout_session_id, [], [
            'stripe_account' => $connected_account_id
        ]);
    }

    public function createLink(string $accountId, string $returnUrl, string $refreshUrl)
    {
        return $this->client->accountLinks->create([
            'account'            => $accountId,
            'refresh_url'        => $refreshUrl,
            'return_url'         => $returnUrl,
            'type'               => 'account_onboarding',
            'collection_options' => ['fields' => 'eventually_due'],
        ]);
    }

    public function createProduct(array $data = []): Product
    {
        return $this->client->products->create($data);
    }

    public function createPrice(array $data = []): Price
    {
        return $this->client->prices->create($data);
    }

    public function updateProduct(string $productId, array $data = []): Product
    {
        return $this->client->products->update($productId, $data);
    }

    public function updatePrice(string $priceId, array $data = []): Price
    {
        return $this->client->prices->update($priceId, $data);
    }

    public function getClient(): StripeClient
    {
        return $this->client;
    }
}
