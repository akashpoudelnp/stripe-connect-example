<?php

namespace App\Http\Controllers;

use App\Models\Client;

class StripeConnectController extends Controller
{
    public function connect(Client $client)
    {
        $connectLink = $client->initiateStripeConnectOnboarding(
            returnUrl: route('stripe.connect.return', $client),
            refreshUrl: route('stripe.connect.refresh', $client),
        );

        return redirect($connectLink);
    }

    public function return(Client $client)
    {
        $client->checkAndUpdateOnboardingReturn();

        return redirect()->route('front.index');
    }

    public function refresh(Client $client)
    {
        return redirect($client->getStripeConnectRefreshUrl(
            returnUrl: route('stripe.connect.return', $client),
            refreshUrl: route('stripe.connect.refresh', $client),
        ));
    }
}
