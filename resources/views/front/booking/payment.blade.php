<x-master-layout>
    @push('head')
        <script src="https://js.stripe.com/v3/"></script>
    @endpush

    <div class="container mx-auto">
        <div id="checkout">
        </div>
    </div>

    <script>
        const stripe = Stripe("{{config('services.stripe.public')}}");
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
