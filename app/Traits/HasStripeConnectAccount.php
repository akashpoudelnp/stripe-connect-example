<?php

namespace App\Traits;

use App\Facades\Stripe;

trait HasStripeConnectAccount
{
    /**
     * @var string $stripeAccountIdColumn The column name in the database that stores the Stripe Account ID
     */
    public string $stripeAccountIdColumn = 'stripe_account_id';

    /**
     * @var string $stripeAccountStatusColumn The column name in the database that stores the Stripe Account status
     */
    public string $stripeAccountStatusColumn = 'stripe_account_status';

    /**
     * Create the Stripe Connect account for the model if it doesn't exist and returns the onboarding link
     * @param string $returnUrl
     * @param string $refreshUrl
     * @return string - Onboarding link
     */
    public function initiateStripeConnectOnboarding(
        string $returnUrl,
        string $refreshUrl,
    ): string
    {
        if (!$this->stripe_account_id) {
            $account = Stripe::createConnectAccount();

            $this->update([
                $this->stripeAccountIdColumn => $account->id,
            ]);
        }

        return $this->getStripeConnectRefreshUrl($returnUrl, $refreshUrl);
    }

    /**
     * Check the status of the Stripe Connect account's onboarding status and update the model accordingly
     * @return bool - True if the account has onboarded, false otherwise
     */
    public function checkAndUpdateOnboardingReturn(): bool
    {
        $accountDetails = Stripe::retrieveConnectAccount($this->stripe_account_id);

        if ($accountDetails->details_submitted) {
            $this->update([
                $this->stripeAccountStatusColumn => $accountDetails->details_submitted ? 'connected' : 'pending',
            ]);

            return true;
        }

        return false;
    }

    /**
     * Get the refresh URL for the Stripe Connect account
     * @param string $returnUrl
     * @param string $refreshUrl
     * @return string - Refresh URL
     */
    public function getStripeConnectRefreshUrl(
        string $returnUrl,
        string $refreshUrl,
    ): string
    {
        $link = Stripe::createLink($this->{$this->stripeAccountIdColumn}, $returnUrl, $refreshUrl);

        return $link->url;
    }
}
