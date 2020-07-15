@extends('emails.layout')
@section('message')
<section class="row">
    <div class="pull-left">
        Hi Admin,<br /><br />
       
    </div>
</section>
<section class="row">
    <div class="col-md-12">
		New User registered on Fruit and veg site.
		<br/>
		<br/>
                <strong>Details</strong><br />
                Name - {{$mail_content['first_name']}} {{$mail_content['last_name']}}<br />
                Email - {{$mail_content['email']}}<br />
                Phone - {{$mail_content['tel_num']}}
    </div>
</section>
@endsection