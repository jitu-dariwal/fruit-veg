<html xmlns="http://www.w3.org/1999/xhtml">
            <head>
                <script type="text/javascript">
                    function closethisasap() {
                        document.forms["redirectpost"].submit();
                    }
                </script>
            </head>
            <body onload="closethisasap();">
            <form name="redirectpost" method="post" action="https://www.secpay.com/java-bin/ValCard">
                <input type="hidden" name="merchant" value="fruitf01-jredir">
                <input type="hidden" name="trans_id" value="FruitAndVeg<?php echo date('Ymd').time(); ?>">
                <input type="hidden" name="amount" value="{{$dueAmt}}">
                <input type="hidden" name="bill_name" value="{{ucfirst($customer->first_name.' '.$customer->last_name)}}">
                <input type="hidden" name="bill_addr_1" value="{{$customer->defaultaddress->street_address}}">
                <input type="hidden" name="bill_addr_2" value="{{$customer->defaultaddress->address_line_2}}">
                <input type="hidden" name="bill_city" value="{{$customer->defaultaddress->city}}">
                <input type="hidden" name="bill_state" value="{{$customer->defaultaddress->county_state}}">
                <input type="hidden" name="bill_post_code" value="{{$customer->defaultaddress->post_code}}">
                <input type="hidden" name="bill_country" value="{{$customer->defaultaddress->country->name}}">
                <input type="hidden" name="bill_tel" value="{{$customer->defaultaddress->tel_num}}">
                <input type="hidden" name="bill_email" value="{{$customer->defaultaddress->email}}">
                <input type="hidden" name="ship_name" value="{{$customer->defaultaddress->company_name}}">
                <input type="hidden" name="ship_addr_1" value="{{$customer->defaultaddress->street_address}}">
                <input type="hidden" name="ship_addr_2" value="{{$customer->defaultaddress->address_line_2}}">
                <input type="hidden" name="ship_city" value="{{$customer->defaultaddress->city}}">
                <input type="hidden" name="ship_state" value="{{$customer->defaultaddress->county_state}}">
                <input type="hidden" name="ship_post_code" value="{{$customer->defaultaddress->post_code}}">
                <input type="hidden" name="ship_country" value="United Kingdom">
                <input type="hidden" name="currency" value="GBP">
                <input type="hidden" name="template" value="http://www.secpay.com/users/fruitf01/template_fnv.html">
                <input type="hidden" name="callback" value="{{route('payment-with-cc.statusupdate', $invoiceid)}};{{route('payment-with-cc.statusupdate', $invoiceid)}}">
                <input type="hidden" name="osCsid" value="ef0502b22ff1170c297c65a8913ff7eb">
                <input type="hidden" name="options" value="test_status=true,dups=false,cb_post=true,cb_flds=osCsid">
            </form>
            </body> 
            </html>