## Stripe Connect Integration

### Installation

Begin by cloning the repository and follow instructions in order:

1. Dependencies

```ssh
composer install
```

2. Configuration

- copy the .env file `cp .env.example .env`
- Create a new database and update the environment files

3. Migrate the database

```ssh
php artisan migrate
```

4. Stripe API Configuration

- Create a new account on Stripe and get the API keys
- Update the .env file with the Stripe API keys `STRIPE_PUBLIC` and `STRIPE_SECRET`

### Features

1. Connect Account Onboarding

- The `Stripe` facade has method named `createConnectAccount` bind the account to your related mode
- In our case we are binding to the Client model
- The method will return the account id
- You can use the account id to create a new account link for the user to complete the onboarding process
- The `createLink(string $accountId, string $returnUrl, string $refreshUrl)` method is used to create the account link
- In the return url you can perform the check if the account is successfully onboarded or not by looking
  at `details_submitted field in account object, the `StripeConnectController` has implemented the onboarding process

2. Payment for Client Basis

- The `EventBookingController` has implemented the payment process, please refer to the controller for more details


