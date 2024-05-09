<?php

namespace App\Services;

use App\Contracts\IStripe;
use Stripe\Account;
use Stripe\Price;
use Stripe\Product;
use Stripe\StripeClient;

class StripeService implements IStripe
{
    protected StripeClient $client;

    public function __construct()
    {
        $this->client = new StripeClient(config('services.stripe.secret'));
    }

    public function createConnectAccount(array $data = []): Account
    {
        return $this->client->accounts->create($data);
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
