@component('mail::message')
<section class="row">
    <div class="pull-left">
        Hi Admin,<br /><br />
    </div>
</section>
<section class="row">
    <div class="col-md-12">
		New enquiry is generated on Fruit and veg site.
		<br/>
		<br/>
		<strong>Details</strong><br />
		Name : {{ $mail_content['name'] }}<br/>
		Email : {{ $mail_content['email'] }}<br/>
		Phone : {{ $mail_content['tel_num'] }}<br/>
		Message : {{ $mail_content['enquiry'] }}
    </div>
</section>

Thanks,<br>
{{ config('app.name') }} Team
@endcomponent
