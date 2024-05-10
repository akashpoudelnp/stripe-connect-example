<?php

namespace App\Contracts;

use Stripe\Account;
use Stripe\Checkout\Session as CheckoutSession;
use Stripe\StripeClient;

// We are aliasing this as there are multiple types of Session classes in the stripe sdk

interface IStripe
{
    public function createConnectAccount(array $data = []): Account;

    public function retrieveConnectAccount(string $accountId): Account;

    public function retrieveCheckoutSession(string $checkout_session_id, string $connected_account_id = null): CheckoutSession;

    public function refundPayment(string $paymentIntent, string $connectedAccount = null);

    public function createLink(string $accountId, string $returnUrl, string $refreshUrl);

    public function getClient(): StripeClient;
}
