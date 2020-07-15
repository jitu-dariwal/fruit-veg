@if(isset($payment['name']))
    @if($payment['name'] == config('weekly-invoice.name') || $payment['name'] == 'weekly-invoice')
        @include('front.payments.weekly-invoice')
    @elseif($payment['name'] == config('monthly-invoice.name') || $payment['name'] == 'monthly-invoice')
        @include('front.payments.monthly-invoice')
    @elseif($payment['name'] == config('credit-card-SECPay.name') || $payment['name'] == 'secpay')
        @include('front.payments.credit-card-SECPay')
    @elseif($payment['name'] == config('stripe.name') || $payment['name'] == 'stripe')
        @include('front.payments.stripe')
    @elseif($payment['name'] == config('paypal.name') || $payment['name'] == 'paypal')
        @include('front.payments.paypal')
    @elseif($payment['name'] == config('bank-transfer.name') || $payment['name'] == 'bank-transfer')
        @include('front.payments.bank-transfer')
    @endif
@endif