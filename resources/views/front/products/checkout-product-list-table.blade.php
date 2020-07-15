@if(!$products->isEmpty())
    <table class="table table-striped">
        <thead>
        <th class="col-md-2 col-lg-2">Product(s) <a href="{{route('cart.index')}}">Edit</a></th>
                <th class="col-md-2 col-lg-2">Code</th>
                <th class="col-md-2 col-lg-2">Estimated Total</th>
        </thead>
        <tfoot>
        <tr>
            <td class="bg-warning">SubTotal</td>
            <td class="bg-warning"></td>
            <td class="bg-warning">{{config('cart.currency_symbol')}} {{ $subtotal }}</td>
        </tr>
       <!-- <tr>
            <td class="bg-warning">Shipping</td>
            <td class="bg-warning"></td>
            <td class="bg-warning"></td>
            <td class="bg-warning"></td>
            <td class="bg-warning">{{config('cart.currency_symbol')}} <span id="shippingFee">{{ number_format(0, 2) }}</span></td>
        </tr>
        <tr>
            <td class="bg-warning">Tax</td>
            <td class="bg-warning"></td>
            <td class="bg-warning"></td>
            <td class="bg-warning"></td>
            <td class="bg-warning">{{config('cart.currency_symbol')}} {{ number_format($tax, 2) }}</td>
        </tr>
        <tr>
            <td class="bg-success">Total</td>
            <td class="bg-success"></td>
            <td class="bg-success"></td>
            <td class="bg-success"></td>
            <td class="bg-success">{{config('cart.currency_symbol')}} <span id="grandTotal" data-total="{{ $total }}">{{ $total }}</span></td>
        </tr> -->
        
        @if($discount_coupon_amount > 0)
            <tr>
                <td class="bg-warning">Coupon Discount</td>
                <td class="bg-warning"></td>
                <td class="bg-warning">{{config('cart.currency_symbol')}} {{ $discount_coupon_amount }}</td>
            </tr>
        @endif
        
        <tr>
            <td class="bg-warning">Total</td>
            <td class="bg-warning"></td>
            <td class="bg-warning">{{config('cart.currency_symbol')}} {{ $total }}</td>
        </tr>
        
        </tfoot>
        <tbody>
        @foreach($cartItems as $cartItem)
                                <tr>
                                    <td>
                                        <h3>{{ $cartItem->qty }} X {{ $cartItem->name }} ({{ $cartItem->type }})</h3>
                                      <!--  @if(isset($cartItem->options))
                                            @foreach($cartItem->options as $key => $option)
                                                <span class="label label-primary">{{ $key }} : {{ $option }}</span>
                                            @endforeach
                                        @endif
                                        <div class="product-description">
                                            {!! $cartItem->product->description !!}
                                        </div> -->
                                    </td>
                                    <td>
                                       <!-- <a href="{{ route('front.get.product', [$cartItem->product->slug]) }}" class="hover-border"> -->
                                           {{ $cartItem->product_code }}
                                       <!-- </a> -->
                                    </td>
                                    
                                    <td>{{config('cart.currency_symbol')}} {{ number_format($cartItem->price*$cartItem->qty, 2) }}</td>
                                </tr>
                            @endforeach
        </tbody>
    </table>
@endif
<script type="text/javascript">
    $(document).ready(function () {
        let courierRadioBtn = $('input[name="rate"]');
        courierRadioBtn.click(function () {
            $('#shippingFee').text($(this).data('fee'));
            let totalElement = $('span#grandTotal');
            let shippingFee = $(this).data('fee');
            let total = totalElement.data('total');
            let grandTotal = parseFloat(shippingFee) + parseFloat(total);
            totalElement.html(grandTotal.toFixed(2));
        });
    });
</script>