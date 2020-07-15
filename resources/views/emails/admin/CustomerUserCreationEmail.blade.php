@extends('emails.layout')
@section('message')
<section class="row">
    <div class="pull-left">
        Hi {{$customeruser_details['first_name']}} {{$customeruser_details['last_name']}}! <br /><br />
        
		Your account on Fruit and veg has been created!<br /><br />
	</div>
</section>
<section class="row">
    <div class="col-md-12">
	
     <strong>Here are the details</strong>
		
        <table class="table table-striped" width="100%" border="0" cellspacing="0" cellpadding="0">
            <tbody>
                <tr>
                    <td>Email</td><td>{{$customeruser_details['email']}}</td>
                </tr>
                <tr>
                    <td>Password</td><td>{{$customeruser_details['password']}}</td>
                </tr>
                <tr>
                    <td colspan="2"> <br/>Please click on the below link to verify your account so you can login into the system using above details.
                        <br/>
                        <br/>
                        <a href="{{url('user/verify', $customeruser_details['token'])}}">Verify Account</a>
                    </td>
                </tr>
            </tbody>

        </table>
    </div>
</section>
@endsection