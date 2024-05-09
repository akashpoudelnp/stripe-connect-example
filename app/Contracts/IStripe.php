<?php

namespace App\Contracts;

use Stripe\Account;

interface IStripe
{
    public function createConnectAccount(array $data = []): Account;
}
