<img src="{{ asset("img/logo.jpg") }}" alt="{{ config('app.name') }}" /><br /><br />  
			  <b>Order Number:</b> {{$order->id}}<br />
			  <b>Invoice URL:</b>	<a href="{{route('accounts.orderdetail', $order->id)}}">Click To View</a><br />
			  <b>Date Ordered:</b> {{$order->created_at->format('d-m-Y')}}<br /><br />
			  Your order has been updated to the following status<br /><br />
			  <b>New status:</b> {{$order->orderStatus->name}}<br /><br />
			  Please reply to this email if you have any questions.
			 <hr>
Thanks,<br>
{{ config('app.name') }}