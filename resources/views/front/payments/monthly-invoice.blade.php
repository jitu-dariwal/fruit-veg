<tr>
    <td>
        @if(isset($payment['name']))
            {{ ucwords(str_replace("-", " ", $payment['name'])) }}
        @else
            <p class="alert alert-danger">You need to have <strong>name</strong> key in your config</p>
        @endif
    </td>
    <td>
        @if(isset($payment['description']))
            {{ $payment['description'] }}
        @else
        {{ ucwords(str_replace("-", " ", $payment['name'])) }}
        @endif
    </td>
    <td align="right">
       <input type="radio" name="payment_name" @if(Session::get('payment_name') == "monthly-invoice") checked="checked"  @endif value="monthly-invoice">
    </td>
</tr>
@section('js')
    <script src="{{ url('https://checkout.stripe.com/checkout.js') }}"></script>
    <script type="text/javascript">

        function setTotal(total, shippingCost) {
            let computed = +shippingCost + parseFloat(total);
            $('#total').html(computed.toFixed(2));
        }

        function setShippingFee(cost) {
            el = '#shippingFee';
            $(el).html(cost);
            $('#shippingFeeC').val(cost);
        }

        function setCourierDetails(courierId) {
            $('.courier_id').val(courierId);
        }

        $(document).ready(function () {

            let clicked = false;

            $('#sameDeliveryAddress').on('change', function () {
                clicked = !clicked;
                if (clicked) {
                    $('#sameDeliveryAddressRow').show();
                } else {
                    $('#sameDeliveryAddressRow').hide();
                }
            });

            let billingAddress = 'input[name="billing_address"]';
            $(billingAddress).on('change', function () {
                let chosenAddressId = $(this).val();
                $('.address_id').val(chosenAddressId);
                $('.delivery_address_id').val(chosenAddressId);
            });

            let deliveryAddress = 'input[name="delivery_address"]';
            $(deliveryAddress).on('change', function () {
                let chosenDeliveryAddressId = $(this).val();
                $('.delivery_address_id').val(chosenDeliveryAddressId);
            });

            let courier = 'input[name="courier"]';
            $(courier).on('change', function () {
                let shippingCost = $(this).data('cost');
                let total = $('#total').data('total');

                setCourierDetails($(this).val());
                setShippingFee(shippingCost);
                setTotal(total, shippingCost);
            });

            if ($(courier).is(':checked')) {
                let shippingCost = $(courier + ':checked').data('cost');
                let courierId = $(courier + ':checked').val();
                let total = $('#total').data('total');

                setShippingFee(shippingCost);
                setCourierDetails(courierId);
                setTotal(total, shippingCost);
            }

            let handler = StripeCheckout.configure({
                key: "{{ config('stripe.key') }}",
                locale: 'auto',
                token: function(token) {
                    // You can access the token ID with `token.id`.
                    // Get the token ID to your server-side code for use.
                    console.log(token.id);
                    $('input[name="stripeToken"]').val(token.id);
                    $('#stripeForm').submit();
                }
            });

            document.getElementById('paywithstripe').addEventListener('click', function(e) {
                let total = parseFloat("{{ $total }}");
                let shipping = parseFloat($('#shippingFeeC').val());
                let amount = total + shipping;
                // Open Checkout with further options:
                handler.open({
                    name: "{{ config('stripe.name') }}",
                    description: "{{ config('stripe.description') }}",
                    currency: "{{ config('cart.currency') }}",
                    amount: amount * 100,
                    email: "{{ $customer->email }}"
                });
                e.preventDefault();
            });

            // Close Checkout on page navigation:
            window.addEventListener('popstate', function() {
                handler.close();
            });
        });
    </script>
@endsection