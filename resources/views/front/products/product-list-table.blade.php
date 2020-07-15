@if(!$products->isEmpty())
    <table class="table table-striped">
        <thead>
                <th class="col-md-2 col-lg-2">Code</th>
                <th class="col-md-2 col-lg-2">Product(s)</th>
                <th class="col-md-2 col-lg-1">Type</th>
                <th class="col-md-2 col-lg-2">Weight</th>
                <th class="col-md-2 col-lg-2">Qty</th>
                <th class="col-md-2 col-lg-1"></th>
                <th class="col-md-2 col-lg-2">Estimated Total</th>
        </thead>
        <tfoot>
        <tr>
            <td class="bg-warning">Total</td>
            <td class="bg-warning"></td>
            <td class="bg-warning"></td>
            <td class="bg-warning"></td>
            <td class="bg-warning"></td>
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
        </tfoot>
        <tbody>
        @foreach($cartItems as $cartItem)
                                <tr>
                                    <td>
                                       <!-- <a href="{{ route('front.get.product', [$cartItem->product->slug]) }}" class="hover-border"> -->
                                           {{ $cartItem->product_code }}
                                       <!-- </a> -->
                                    </td>
                                    <td>
                                        <h3>{{ $cartItem->name }}</h3>
                                      <!--  @if(isset($cartItem->options))
                                            @foreach($cartItem->options as $key => $option)
                                                <span class="label label-primary">{{ $key }} : {{ $option }}</span>
                                            @endforeach
                                        @endif
                                        <div class="product-description">
                                            {!! $cartItem->product->description !!}
                                        </div> -->
                                    </td>
                                    <td>{{ $cartItem->type }}</td>
                                    <td>{{ $cartItem->qty }} X {{ $cartItem->packet_size }}</td>
                                    <td>
                                        <form action="{{ route('cart.update', $cartItem->rowId) }}" class="form-inline" method="post">
                                            {{ csrf_field() }}
                                            <input type="hidden" name="_method" value="put">
                                            <div class="input-group">
                                                <input type="text" name="quantity" value="{{ $cartItem->qty }}" class="form-control" />
                                                <span class="input-group-btn"><button class="btn btn-default">Update</button></span>
                                            </div>
                                        </form>
                                    </td>
                                    <td>
                                        <form action="{{ route('cart.destroy', $cartItem->rowId) }}" method="post">
                                            {{ csrf_field() }}
                                            <input type="hidden" name="_method" value="delete">
                                            <button onclick="return confirm('You are about to delete this product?')" class="btn btn-danger"><i class="fa fa-times"></i></button>
                                        </form>
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