<x-master-layout>
    @push('head')
        <script src="https://js.stripe.com/v3/"></script>
    @endpush

    <div class="container mx-auto">
        <div id="checkout">
        </div>
    </div>

    <script>
        const charge_type = @json($booking->charge_type);
        let stripe;

        if (charge_type === 'direct') {
            stripe = Stripe("{{config('stripe.keys.public')}}", {
                stripeAccount: @json($booking->event->client->stripe_account_id),
            });
        } else {
            stripe = Stripe("{{config('stripe.keys.public')}}");
        }

        const csrf_token = "{{csrf_token()}}";
        const initiatePaymentURL = @json(route('events.bookings.payment.initiate', $booking));
        initialize();

        async function initialize() {
            const fetchClientSecret = async () => {
                const response = await fetch(initiatePaymentURL, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrf_token,
                    },
                });
                const {clientSecret} = await response.json();
                return clientSecret;
            };

            const checkout = await stripe.initEmbeddedCheckout({
                fetchClientSecret,
            });

            // Mount Checkout
            checkout.mount('#checkout');
        }
    </script>
</x-master-layout>
