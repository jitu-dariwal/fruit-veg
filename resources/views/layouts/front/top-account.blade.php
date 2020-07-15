<h1>Your account </h1>
<p>{{ __('content.account.welcome_msg',['user_name' => \Auth::user()->first_name.' '.\Auth::user()->last_name]) }}</p>