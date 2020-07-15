<div class="list-group-outer">
                
	<div class="list-group">
		<a href="{{ route('accounts').'/' }}" class="list-group-item list-group-item-action {{ (request()->segment(1) == 'accounts' && request()->segment(2) == null) ? 'active' : ''}}">Your account</a>
		
		<a href="{{ route('accounts.accountdetails', \Auth::user()->id).'/' }}" class="list-group-item list-group-item-action {{ (request()->segment(1) == 'accounts' && request()->segment(2) == 'accountdetails') ? 'active' : ''}}">Account Details</a>
		
		<a href="{{ route('accounts.addressbook', \Auth::user()->id).'/' }}" class="list-group-item list-group-item-action {{ (request()->segment(1) == 'accounts' && request()->segment(2) == 'addressbook') ? 'active' : ''}}">address book</a>
		
		<a href="{{ route('accounts.orders').'/' }}" class="list-group-item list-group-item-action {{ (request()->segment(1) == 'accounts' && request()->segment(2) == 'orders') ? 'active' : ''}}">your orders</a>
		
		<a href="{{ route('accounts.accountstatements', \Auth::user()->id).'/' }}" class="list-group-item list-group-item-action {{ (request()->segment(1) == 'accounts' && request()->segment(2) == 'accountstatements') ? 'active' : ''}}">statements</a>
		
		<a href="{{ route('accounts.accountpayments', \Auth::user()->id).'/' }}" class="list-group-item list-group-item-action {{ (request()->segment(1) == 'accounts' && request()->segment(2) == 'accountpayments') ? 'active' : ''}}">Payment</a>
		
		<a href="{{ route('logout').'/' }}" class="list-group-item list-group-item-action ">Logout</a>
	</div>
                
	<nav class="mobile-navbar navbar navbar-expand-lg">
		<!-- Brand -->
		<a class="navbar-brand {{ (request()->segment(1) == 'accounts' && request()->segment(2) == null) ? 'active' : ''}}" href="{{ route('accounts').'/' }}">Your account</a>

		<!-- Toggler/collapsibe Button -->
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
			<i class="fas fa-chevron-down"></i>
		</button>

		<!-- Navbar links -->
		<div class="collapse navbar-collapse" id="collapsibleNavbar">
			<ul class="navbar-nav">
				<li class="nav-item {{ (request()->segment(1) == 'accounts' && request()->segment(2) == 'accountdetails') ? 'active' : ''}}">
					<a class="nav-link" href="{{ route('accounts.accountdetails', \Auth::user()->id).'/' }}">Account Details</a>
				</li>
				<li class="nav-item {{ (request()->segment(1) == 'accounts' && request()->segment(2) == 'addressbook') ? 'active' : ''}}">
					<a class="nav-link " href="{{ route('accounts.addressbook', \Auth::user()->id).'/' }}">address book</a>
				</li>
				<li class="nav-item {{ (request()->segment(1) == 'accounts' && request()->segment(2) == 'orders') ? 'active' : ''}}">
					<a class="nav-link" href="{{ route('accounts.orders').'/' }}">your orders</a>
				</li>
				<li class="nav-item {{ (request()->segment(1) == 'accounts' && request()->segment(2) == 'accountstatements') ? 'active' : ''}}">
					<a class="nav-link" href="{{ route('accounts.accountstatements', \Auth::user()->id).'/' }}">statements</a>
				</li>
				<li class="nav-item {{ (request()->segment(1) == 'accounts' && request()->segment(2) == 'accountpayments') ? 'active' : ''}}">
					<a class="nav-link" href="{{ route('accounts.accountpayments', \Auth::user()->id) .'/' }}">Payment</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="{{ route('logout').'/' }}">Logout</a>
				</li>
			</ul>
		</div>
	</nav> 
	
</div>