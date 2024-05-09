<?php

namespace App\Http\Controllers;

use App\Facades\Stripe;
use App\Models\Client;
use Exception;

class StripeConnectController extends Controller
{
    public function connect(Client $client)
    {
        if (!$client->stripe_account_id) {
            $account = Stripe::createConnectAccount();

            $client->update([
                'stripe_account_id' => $account->id,
            ]);
        }

        $link = Stripe::createLink($client->stripe_account_id, route('stripe.connect.return', $client), route('stripe.connect.refresh', $client));

        if ($link instanceof Exception) {
            dd($link);
        }

        return redirect($link->url);
    }

    public function return(Client $client)
    {
        $accountDetails = Stripe::getClient()->accounts->retrieve($client->stripe_account_id);

        if ($accountDetails->details_submitted) {
            $client->update([
                'stripe_account_status' => $accountDetails->details_submitted ? 'connected' : 'pending',
            ]);
        }

        return redirect()->route('front.index');
    }

    public function refresh(Client $client)
    {
        $link = Stripe::createLink($client->stripe_account_id, route('stripe.connect.return', $client), route('stripe.connect.refresh', $client));

        return redirect($link->url);
    }
}
