@extends('emails.layout')
@section('message')
<section class="row">
    <div class="pull-left">
        Hi {{$adminuser_details['first_name']}} {{$adminuser_details['last_name']}}! <br /><br />
        Your account on Fruit and veg has been created!<br />
    </div>
</section>
<section class="row">
    <div class="col-md-12">
	
        <h2>Here are the details</h2>
		
        <table class="table table-striped" width="100%" border="0" cellspacing="0" cellpadding="0">
            <tbody>
                <tr>
                    <td>Email</td><td>{{$adminuser_details['email']}}</td>
                </tr>
                <tr>
                    <td>Password</td><td>{{$adminuser_details['password_confirmation']}}</td>
                </tr>
            </tbody>

        </table>
    </div>
</section>
@endsection