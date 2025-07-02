<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Tech Store</title>
    <script src="https://js.stripe.com/v3/"></script>
    <link href="https://cdn.tailwindcss.com" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="max-w-xl mx-auto p-6 bg-white shadow-lg rounded-lg mt-10">
        <h2 class="text-2xl font-bold mb-4">Secure Checkout</h2>
        <form id="payment-form">
            <label for="card-element" class="block text-sm font-medium text-gray-700 mb-2">Credit or debit card</label>
            <div id="card-element" class="p-3 border border-gray-300 rounded-md"></div>
            <div id="card-errors" class="mt-2 text-sm text-red-600" role="alert"></div>
            <button id="submit" class="mt-4 w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Pay Now</button>
        </form>
    </div>

    <script>
        const stripe = Stripe('{{ config('services.stripe.publishable_key') }}');
        const elements = stripe.elements();
        const cardElement = elements.create('card');

        cardElement.mount('#card-element');

        const form = document.getElementById('payment-form');
        form.addEventListener('submit', async (event) => {
            event.preventDefault();
            const { setupIntent, error } = await stripe.confirmCardSetup(
                '{{ $clientSecret }}',
                {
                    payment_method: {
                        card: cardElement,
                    },
                }
            );

            if (error) {
                const errorElement = document.getElementById('card-errors');
                errorElement.textContent = error.message;
            } else {
                // The payment has been processed!
                window.location.href = '{{ route('checkout.success') }}';
            }
        });
    </script>
</body>
</html>

