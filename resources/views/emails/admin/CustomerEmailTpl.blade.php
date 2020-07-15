@extends('emails.layout')
@section('message')
<section class="row">
    <div class="pull-left">
        Hi {{$mail_content['customer_name']}},<br /><br />
       
    </div>
</section>
<section class="row">
    <div class="col-md-12">
		{!!$mail_content['message']!!}
    </div>
</section>
@endsection