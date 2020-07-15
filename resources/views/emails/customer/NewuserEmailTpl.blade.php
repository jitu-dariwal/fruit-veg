@extends('emails.layout')
@section('message')
<section class="row">
    <div class="pull-left">
        Hi {{$mail_content['first_name']}} {{$mail_content['last_name']}},<br /><br />
       
    </div>
</section>
<section class="row">
    <div class="col-md-12">
		Your registered email-id is {{$mail_content['email']}} , Please click on the below link to verify your email account
		<br/>
		<br/>
		<a href="{{url('user/verify', $mail_content['token'])}}">Verify Email</a>
    </div>
</section>
@endsection