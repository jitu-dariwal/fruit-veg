@extends('emails.layout')
@section('message')
<section class="row">
    <div class="pull-left">
        Hi {{$adminuser_details['first_name']}} {{$adminuser_details['last_name']}}! <br /><br />
        Your password on Fruit and veg has been reset!
    </div>
</section>
<section class="row">
    <div class="col-md-12">
	
        <h2>Here are the details</h2>
		
        <table class="table table-striped" width="100%" border="0" cellspacing="0" cellpadding="0">
            <tbody>
                <tr>
                    <td>Username</td><td>{{$adminuser_details['email']}}</td>
                </tr>
                <tr>
                    <td>Password</td><td>{{$adminuser_details['new_password']}}</td>
                </tr>
            </tbody>

        </table>
    </div>
</section>
@endsection