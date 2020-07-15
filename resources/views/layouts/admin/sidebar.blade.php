
<!-- =============================================== -->

<!-- Left side column. contains the sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <!--
        <div class="user-panel">
            <div class="pull-left image">
                <img src="{{ asset('img/user2-160x160.jpg') }}" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p>{{ $user->name }}</p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>
        -->
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu {{ ($user->hasRole('packer')) ? 'packer-menu' : ''}} ">
            <li class="header"><b>MAIN NAVIGATION</b></li>
			@if($user->hasRole('packer'))
			<li class="@if(request()->segment(3) == '')active @endif"><a href="{{ route('admin.packer.index') }}"> <i class="fa fa-home"></i> <span>Dashboard</span></a></li>
		    @else
			<li class="@if(request()->segment(2) == '')active @endif"><a href="{{ route('admin.dashboard') }}"> <i class="fa fa-home"></i> <span>Dashboard</span></a></li>	
			@endif
          
           @if($user->hasRole('superadmin') || $user->hasPermission('create-admin') || $user->hasPermission('view-admin'))
            <li class="treeview @if(request()->segment(2) == 'employees' || request()->segment(2) == 'roles' || request()->segment(3) == 'permissions') active @endif">
                <a href="#">
                    <i class="fa fa-star"></i> <span>Administrator</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    @if($user->hasRole('superadmin') || $user->hasPermission('view-admin'))
                    <li class="@if(request()->segment(2) == 'employees' && request()->segment(3) != 'create')active @endif"><a href="{{ route('admin.employees.index') }}"><i class="fa fa-circle-o"></i> List admin users</a></li>
                    @endif
                    @if($user->hasRole('superadmin') || $user->hasPermission('create-admin'))
                    <li class="@if(request()->segment(2) == 'employees' && request()->segment(3) == 'create')active @endif"><a href="{{ route('admin.employees.create') }}"><i class="fa fa-plus"></i> Create admin user</a></li>
                    @endif
<!--  <li><a href="{{ route('admin.roles.index') }}"><i class="fa fa-circle-o"></i> Roles</a></li> -->
                    @if($user->hasRole('superadmin'))
                    <li class="@if(request()->segment(3) == 'permissions')active @endif"><a href="{{ route('admin.roles.permissions') }}"><i class="fa fa-circle-o"></i> Permissions</a></li>                    
                    @endif
                </ul>
            </li>
            @endif
            <!--<li class="header">SELL</li>-->
            @if(!$user->hasRole('packer'))
            
            @if($user->hasPermission('view-product') || $user->hasPermission('create-product') || $user->hasPermission('view-category') || $user->hasPermission('create-category') || $user->hasPermission('view-manufacturer') || $user->hasPermission('create-manufacturer') || $user->hasPermission('create-coupon') || $user->hasPermission('view-coupon') || $user->hasRole('superadmin'))
            <li class="treeview @if(request()->segment(2) == 'products' || request()->segment(2) == 'categories'  || request()->segment(2) == 'product' || request()->segment(2) == 'manufacturers' || request()->segment(2) == 'coupons') active @endif">
                <a href="#">
                    <i class="fa fa-gift"></i> <span>Catalog</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    @if($user->hasPermission('view-product') || $user->hasPermission('create-product') || $user->hasRole('superadmin'))
                    <li class="@if(request()->segment(2) == 'products' || request()->segment(2) == 'product') active @endif">
                        <a href="#">
                            <i class="fa fa-folder"></i> <span>Products</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            @if($user->hasPermission('view-product') || $user->hasRole('superadmin'))<li class="@if(request()->segment(2) == 'products' && request()->segment(3) != 'create')active @endif"><a href="{{ route('admin.products.index') }}"><i class="fa fa-circle-o"></i> List products</a></li>@endif
                            @if($user->hasPermission('create-product') || $user->hasRole('superadmin'))<li class="@if(request()->segment(2) == 'products' && request()->segment(3) == 'create')active @endif"><a href="{{ route('admin.products.create') }}"><i class="fa fa-plus"></i> Create product</a></li>@endif
                            @if($user->hasPermission('product-pdf') || $user->hasRole('superadmin'))<li class="@if(request()->segment(3) == 'productspdf')active @endif"><a href="{{ route('admin.products.productspdf') }}"><i class="fa fa-circle-o"></i> Products List PDF</a></li>@endif
                            @if($user->hasPermission('product-bulk-update') || $user->hasRole('superadmin'))<li class="@if(request()->segment(3) == 'productsbulkupdate')active @endif"><a href="{{ route('admin.products.productsbulkupdate') }}"><i class="fa fa-plus"></i> Product Update (BULK)</a></li>@endif
                        </ul>
                    </li>
                    @endif

                  @if($user->hasPermission('view-category') || $user->hasPermission('create-category') || $user->hasRole('superadmin'))
                    <li class="@if(request()->segment(2) == 'categories') active @endif">
                        <a href="#">
                            <i class="fa fa-folder"></i> <span>Categories</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            @if($user->hasPermission('view-category') || $user->hasRole('superadmin'))<li class="@if(request()->segment(2) == 'categories' && request()->segment(3) != 'create')active @endif"><a href="{{ route('admin.categories.index') }}"><i class="fa fa-circle-o"></i> List categories</a></li>@endif
                            @if($user->hasPermission('create-category') || $user->hasRole('superadmin'))<li class="@if(request()->segment(2) == 'categories' && request()->segment(3) == 'create')active @endif"><a href="{{ route('admin.categories.create') }}"><i class="fa fa-plus"></i> Create category</a></li>@endif
                        </ul>
                    </li>
                   @endif

                   @if($user->hasPermission('view-manufacturer') || $user->hasPermission('create-manufacturer') || $user->hasPermission('update-manufacturer') || $user->hasPermission('delete-manufacturer') || $user->hasRole('superadmin'))
                    <li class="@if(request()->segment(2) == 'manufacturers') active @endif">
                        <a href="#">
                            <i class="fa fa-folder"></i> <span>Manufacturers</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            @if($user->hasPermission('create-manufacturer') || $user->hasRole('superadmin'))<li class="@if(request()->segment(2) == 'manufacturers' && request()->segment(3) != 'create')active @endif"><a href="{{ route('admin.manufacturers.index') }}"><i class="fa fa-circle-o"></i> Manufacturers</a></li>@endif
                            @if($user->hasPermission('create-manufacturer') || $user->hasRole('superadmin'))<li class="@if(request()->segment(2) == 'manufacturers' && request()->segment(3) == 'create')active @endif"><a href="{{ route('admin.manufacturers.create') }}"><i class="fa fa-plus"></i> Create Manufacturer</a></li>@endif
                        </ul>
                    </li>
                    @endif
                  
                    @if($user->hasPermission('create-coupon') || $user->hasPermission('view-coupon') || $user->hasPermission('update-coupon') || $user->hasPermission('delete-coupon') || $user->hasRole('superadmin'))
                     <li class="@if(request()->segment(2) == 'coupons') active @endif">
                        <a href="#">
                            <i class="fa fa-folder"></i> <span>Vouchers/Coupons</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            @if($user->hasPermission('view-coupon') || $user->hasRole('superadmin'))<li class="@if(request()->segment(2) == 'coupons' && request()->segment(3) != 'create' && request()->segment(3) != 'sentcoupon')active @endif"><a href="{{ route('admin.coupons.index') }}"><i class="fa fa-circle-o"></i> Coupons</a></li>@endif
                            @if($user->hasPermission('create-coupon') || $user->hasRole('superadmin'))<li class="@if(request()->segment(2) == 'coupons' && request()->segment(3) == 'create')active @endif"><a href="{{ route('admin.coupons.create') }}"><i class="fa fa-plus"></i> Create Coupon</a></li>@endif
                            @if($user->hasPermission('send-coupon') || $user->hasRole('superadmin'))<li class="@if(request()->segment(2) == 'coupons' && request()->segment(3) == 'sentcoupon')active @endif"><a href="{{ route('admin.coupons.sentcoupon') }}"><i class="fa fa-plus"></i>Vouchers/Coupons Sent</a></li>@endif
                        </ul>
                    </li>
                    @endif
                    <!--   <li class="@if(request()->segment(2) == 'attributes') active @endif">
                            <a href="#">
                                <i class="fa fa-gear"></i> <span>Attributes</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="{{ route('admin.attributes.index') }}"><i class="fa fa-circle-o"></i> List attributes</a></li>
                                <li><a href="{{ route('admin.attributes.create') }}"><i class="fa fa-plus"></i> Create attribute</a></li>
                            </ul>
                        </li>
                        <li class="@if(request()->segment(2) == 'brands') active @endif">
                            <a href="#">
                                <i class="fa fa-tag"></i> <span>Brands</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="{{ route('admin.brands.index') }}"><i class="fa fa-circle-o"></i> List brands</a></li>
                                <li><a href="{{ route('admin.brands.create') }}"><i class="fa fa-plus"></i> Create brand</a></li>
                            </ul>
                        </li> -->
                </ul>
            </li>
            @endif
            
            @if($user->hasPermission('view-customer') || $user->hasPermission('create-customer') || $user->hasPermission('delete-customer') || $user->hasPermission('update-customer') || $user->hasPermission('view-customergroup') || $user->hasPermission('view-customergroup') || $user->hasPermission('create-customergroup') || $user->hasRole('superadmin'))
                <li class="treeview @if(request()->segment(2) == 'customers' || request()->segment(2) == 'customersgroup') active @endif">
                    <a href="#">
                        <i class="fa fa-user"></i> <span>Customers</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                       @if($user->hasPermission('view-customer') || $user->hasRole('superadmin')) 
                       <li class="@if(request()->segment(2) == 'customers' && request()->segment(3) != 'create')active @endif"><a href="{{ route('admin.customers.index') }}"><i class="fa fa-circle-o"></i> List customers</a></li>
                       @endif
                       @if($user->hasPermission('create-customer') || $user->hasRole('superadmin')) 
                       <li class="@if(request()->segment(2) == 'customers' && request()->segment(3) == 'create')active @endif"><a href="{{ route('admin.customers.create') }}"><i class="fa fa-plus"></i> Create customer</a></li>
                       @endif 
                       @if($user->hasPermission('create-customergroup') || $user->hasPermission('update-customergroup') || $user->hasRole('superadmin'))
                        <li class="treeview @if(request()->segment(2) == 'customersgroup') active @endif">
                            <a href="#">
                                <i class="fa fa-group"></i> <span>Customers Group</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                @if($user->hasPermission('view-customergroup') || $user->hasRole('superadmin'))
                                    <li class="@if(request()->segment(2) == 'customersgroup' && request()->segment(3) != 'create')active @endif"><a href="{{ route('admin.customersgroup.index') }}"><i class="fa fa-circle-o"></i> List customers groups</a></li>
                                @endif
                                @if($user->hasPermission('create-customergroup') || $user->hasRole('superadmin'))
                                    <li class="@if(request()->segment(2) == 'customersgroup' && request()->segment(3) == 'create')active @endif"><a href="{{ route('admin.customersgroup.create') }}"><i class="fa fa-plus"></i> Create customer group</a></li>
                                @endif
                            </ul>
                        </li>
                        @endif
                    </ul>
                </li>
            @endif
            
            @if($user->hasPermission('create-appuser') || $user->hasPermission('update-appuser') || $user->hasPermission('view-apporders') || $user->hasRole('superadmin'))
            <li class="treeview @if(request()->segment(2) == 'appusers') active @endif">
                <a href="#">
                    <i class="fa fa-star"></i> <span>App Sections</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    @if($user->hasPermission('view-appuser') || $user->hasRole('superadmin'))
                        <li class="@if(request()->segment(2) == 'appusers' && request()->segment(3) != 'create' && request()->segment(3) != 'apporders' && request()->segment(3) != 'editapporder')active @endif"><a href="{{ route('admin.appusers.index') }}"><i class="fa fa-circle-o"></i> List app users</a></li>
                    @endif
                    @if($user->hasPermission('create-appuser') || $user->hasRole('superadmin'))
                        <li class="@if(request()->segment(2) == 'appusers' && request()->segment(3) == 'create')active @endif"><a href="{{ route('admin.appusers.create') }}"><i class="fa fa-plus"></i> Create app user</a></li>
                    @endif
                    @if($user->hasPermission('view-apporders') || $user->hasRole('superadmin'))
                        <li class="@if(request()->segment(2) == 'appusers' && (request()->segment(3) == 'apporders' || request()->segment(3) == 'editapporder'))active @endif"><a href="{{ route('admin.appusers.apporders') }}"><i class="fa fa-circle-o"></i> App orders list</a></li>
                    @endif
                </ul>
            </li>
            @endif
            
            @if($user->hasPermission('create-page') || $user->hasPermission('delete-page') || $user->hasPermission('update-page') || $user->hasPermission('view-pages') || $user->hasRole('superadmin'))
            <li class="treeview @if(request()->segment(2) == 'pages') active @endif">
                <a href="#">
                    <i class="fa fa-star"></i> <span>Pages</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    @if($user->hasPermission('view-pages') || $user->hasRole('superadmin'))
                    <li class="@if(request()->segment(2) == 'pages' && (request()->segment(3) != 'create' && request()->segment(3) != 'dashboard'))active @endif"><a href="{{ route('admin.pages.index') }}"><i class="fa fa-circle-o"></i> List pages</a></li>
                    @endif
                    @if($user->hasPermission('create-page') || $user->hasRole('superadmin'))
                    <li class="@if(request()->segment(2) == 'pages' && request()->segment(3) == 'create')active @endif"><a href="{{ route('admin.pages.create') }}"><i class="fa fa-plus"></i> Create page</a></li>
                    @endif
					@if($user->hasPermission('view-pages') || $user->hasRole('superadmin'))
                    <li class="@if(request()->segment(2) == 'pages' && request()->segment(3) == 'dashboard')active @endif"><a href="{{ route('admin.dashboardSections') }}"><i class="fa fa-circle-o"></i> Dashboard Sections</a></li>
                    @endif
                </ul>
            </li>
            
             @endif
	
        @if($user->hasPermission('view-logs') || $user->hasRole('superadmin'))
		<li class="treeview @if(request()->segment(2) == 'issue-log' or request()->segment(2) == 'amendment-log-report' or request()->segment(2) == 'communication-log') active @endif">
                <a href="#">
                   <i class="fa fa-history" aria-hidden="true"></i> <span>Logs</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class="@if(request()->segment(2) == 'issue-log' && request()->segment(3) != 'create')active @endif"><a href="{{ route('admin.issuelog.index') }}"><i class="fa fa-circle-o"></i> Issue Log</a></li>
					
					<li class="@if(request()->segment(2) == 'issue-log' && request()->segment(3) == 'create')active @endif"><a href="{{ route('admin.issuelog.create') }}"><i class="fa fa-plus"></i> Create Issue Log</a></li>
					
					<li class="@if(request()->segment(2) == 'amendment-log-report' && request()->segment(3) != 'create')active @endif"><a href="{{ route('admin.amendmentlogreport.index') }}"><i class="fa fa-circle-o"></i> Amendment Log</a></li>
					
					<li class="@if(request()->segment(2) == 'amendment-log-report' && request()->segment(3) == 'create')active @endif"><a href="{{ route('admin.amendmentlogreport.create') }}"><i class="fa fa-plus"></i> Create Amendment Log</a></li>
					
					<li class="@if(request()->segment(2) == 'communication-log' && request()->segment(3) != 'create')active @endif"><a href="{{ route('admin.communicationlog.index') }}"><i class="fa fa-circle-o"></i> Communication Log</a></li>
					
					<li class="@if(request()->segment(2) == 'communication-log' && request()->segment(3) == 'create')active @endif"><a href="{{ route('admin.communicationlog.create') }}"><i class="fa fa-plus"></i> Create Communication Log</a></li>
					
                </ul>
            </li>
	@endif
      
	@if($user->hasPermission('create-lead') || $user->hasPermission('view-leads') || $user->hasRole('superadmin'))		
		<li class="treeview @if(request()->segment(2) == 'lead') active @endif">
			<a href="#">
			   <i class="fa fa-object-group" aria-hidden="true"></i> <span>Lead</span>
				<span class="pull-right-container">
					<i class="fa fa-angle-left pull-right"></i>
				</span>
			</a>
			<ul class="treeview-menu">
			   
				@if($user->hasPermission('view-leads') || $user->hasRole('superadmin'))
				 <li class="@if(request()->segment(2) == 'lead' && request()->segment(3) != 'create')active @endif"><a href="{{ route('admin.lead.index') }}"><i class="fa fa-circle-o"></i> List lead</a></li>
				@endif
				
				@if($user->hasPermission('create-lead') || $user->hasRole('superadmin'))
					<li class="@if(request()->segment(2) == 'lead' && request()->segment(3) == 'create')active @endif"><a href="{{ route('admin.lead.create') }}"><i class="fa fa-plus"></i> Create lead</a></li>
				@endif
			</ul>
		</li>
	@endif    
       
	@if($user->hasPermission('create-faq') || $user->hasPermission('view-faq') || $user->hasRole('superadmin'))		
		<li class="treeview @if(request()->segment(2) == 'faq') active @endif">
			<a href="#">
				<i class="fa fa-question-circle" aria-hidden="true"></i> <span>FAQ</span>
				<span class="pull-right-container">
					<i class="fa fa-angle-left pull-right"></i>
				</span>
			</a>
			<ul class="treeview-menu">
				@if($user->hasPermission('view-faq') || $user->hasRole('superadmin'))
					<li class="@if(request()->segment(2) == 'faq' && request()->segment(3) != 'create')active @endif"><a href="{{ route('admin.faq.index') }}"><i class="fa fa-circle-o"></i> List faq</a></li>
				@endif
				
				@if($user->hasPermission('create-faq') || $user->hasRole('superadmin'))
					<li class="@if(request()->segment(2) == 'faq' && request()->segment(3) == 'create')active @endif"><a href="{{ route('admin.faq.create') }}"><i class="fa fa-plus"></i> Create faq</a></li>
				@endif
			</ul>
		</li>
	@endif    
       
	@if($user->hasPermission('create-postcode') || $user->hasPermission('view-postcode') || $user->hasRole('superadmin'))		
		<li class="treeview @if(request()->segment(2) == 'post-code') active @endif">
			<a href="#">
				<i class="fa fa-map-marker" aria-hidden="true"></i> <span>Post Codes</span>
				<span class="pull-right-container">
					<i class="fa fa-angle-left pull-right"></i>
				</span>
			</a>
			<ul class="treeview-menu">
				@if($user->hasPermission('view-postcode') || $user->hasRole('superadmin'))
					<li class="@if(request()->segment(2) == 'post-code' && request()->segment(3) != 'create')active @endif"><a href="{{ route('admin.post-code.index') }}"><i class="fa fa-circle-o"></i> List post code</a></li>
				@endif
				
				@if($user->hasPermission('create-postcode') || $user->hasRole('superadmin'))
					<li class="@if(request()->segment(2) == 'post-code' && request()->segment(3) == 'create')active @endif"><a href="{{ route('admin.post-code.create') }}"><i class="fa fa-plus"></i> Create post code</a></li>
				@endif
			</ul>
		</li>
	@endif    
            
	@if($user->hasPermission('view-reports') || $user->hasRole('superadmin'))		
            <li class="@if(request()->segment(2) == 'update-invoice-status' && request()->segment(3) != 'create')active @endif"><a href="{{ route('admin.update_invoice_status.index') }}"><i class="fa fa-refresh"></i> <span>Update Invoice Status</span></a></li>
	@endif
        
        @if($user->hasPermission('view-reports') || $user->hasRole('superadmin'))
            <li class="@if(request()->segment(2) == 'prep-produce-report' && request()->segment(3) != 'create')active @endif"><a href="{{ route('admin.prep_produce_report.index') }}"><i class="fa fa-star"></i> <span>Prep Produce Report</span></a></li>
	@endif
        
        @if($user->hasPermission('view-reports') || $user->hasRole('superadmin'))
            <li class="@if(request()->segment(2) == 'customers-postcode-list-report' && request()->segment(3) != 'create')active @endif"><a href="{{ route('admin.customers_postcode_list_report.index') }}"><i class="fa fa-star"></i> <span>Customers Postcode report</span> </a></li>
	@endif		   
           
            
           @if($user->hasPermission('view-order') || $user->hasPermission('create-order') || $user->hasPermission('update-order') || $user->hasPermission('delete-order') || $user->hasRole('superadmin'))

            <li class="header">ORDERS</li>
            <li class="treeview @if(request()->segment(2) == 'orders' || request()->segment(2) =='order') active @endif">
                <a href="#">
                    <i class="fa fa-money"></i> <span>Orders</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    @if($user->hasPermission('view-order') || $user->hasRole('superadmin'))
                    <li class="treeview @if(request()->segment(2) == 'orders' && request()->segment(3) != 'create') active @endif"><a href="{{ route('admin.orders.index') }}"><i class="fa fa-circle-o"></i> List orders</a></li>
                    @endif
                    
                    @if($user->hasPermission('create-order') || $user->hasRole('superadmin'))
                    <li class="treeview @if(request()->segment(2) == 'orders' && request()->segment(3) =='create') active @endif"><a href="{{ route('admin.orders.create') }}"><i class="fa fa-plus"></i> Create order</a></li>
                    @endif
                </ul>
            </li>
            @endif
            
            @if($user->hasPermission('view-order-status') || $user->hasPermission('create-order-status') || $user->hasRole('superadmin'))
            <li class="treeview @if(request()->segment(2) == 'order-statuses') active @endif">
                <a href="#">
                    <i class="fa fa-anchor"></i> <span>Order Statuses</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    @if($user->hasPermission('view-order-status') || $user->hasRole('superadmin'))
                        <li class="treeview @if(request()->segment(2) == 'order-statuses' && request()->segment(3) != 'create') active @endif"><a href="{{ route('admin.order-statuses.index') }}"><i class="fa fa-circle-o"></i> List order statuses</a></li>
                    @endif
                    @if($user->hasPermission('create-order-status') || $user->hasRole('superadmin'))
                        <li class="treeview @if(request()->segment(2) == 'order-statuses' && request()->segment(3) == 'create') active @endif"><a href="{{ route('admin.order-statuses.create') }}"><i class="fa fa-plus"></i> Create order status</a></li>
                    @endif
                </ul>
            </li>
            @endif
			<!----------Reports----------->
           @if($user->hasPermission('view-reports') || $user->hasRole('superadmin'))
            <li class="treeview @if(request()->segment(2) == 'reports' || request()->segment(2) =='reports' || request()->segment(2) =='bankholidays') active @endif">
                <a href="#">
                    <i class="fa fa-address-card-o" aria-hidden="true"></i> <span>Reports</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class="@if(request()->segment(2) == 'reports' && request()->segment(3) =='products-purchased') active @endif"><a href="{{route('admin.reports.productspurchased')}}"><i class="fa fa-circle-o" aria-hidden="true"></i> Products Purchased</a></li>
                    <li class="@if(request()->segment(2) == 'reports' && request()->segment(3) =='customers-order-total') active @endif"><a href="{{route('admin.reports.customers-order-total')}}"><i class="fa fa-circle-o" aria-hidden="true"></i> Customer Orders-Total</a></li>
                    <li class="@if(request()->segment(2) == 'reports' && request()->segment(3) =='not-valid-customers') active @endif"><a href="{{route('admin.reports.not-valid-customers')}}"><i class="fa fa-circle-o" aria-hidden="true"></i> Customers Not Validated</a></li>
					<li class="@if(request()->segment(2) == 'reports' && request()->segment(3) =='inactive-client-report') active @endif"><a href="{{route('admin.reports.inactive-client-report')}}"><i class="fa fa-circle-o" aria-hidden="true"></i> Inactive Client Report</a></li>
					<li class="@if(request()->segment(2) == 'reports' && request()->segment(3) =='active-client-report') active @endif"><a href="{{route('admin.reports.active-client-report')}}"><i class="fa fa-circle-o" aria-hidden="true"></i> Active Client Report</a></li>
					<li class="@if(request()->segment(2) == 'reports' && request()->segment(3) =='never-ordered-client-report') active @endif"><a href="{{route('admin.reports.never-ordered-client-report')}}"><i class="fa fa-circle-o" aria-hidden="true"></i> Never Ordered Client Report</a></li>
					<li class="@if(request()->segment(2) == 'reports' && request()->segment(3) =='customer-discount-report') active @endif"><a href="{{route('admin.reports.customer-discount-report')}}"><i class="fa fa-circle-o" aria-hidden="true"></i> Customer Discounts Report</a></li>
					<li class="@if(request()->segment(2) == 'reports' && request()->segment(3) =='discount-detials') active @endif"><a href="{{route('admin.reports.discount-detials')}}"><i class="fa fa-circle-o" aria-hidden="true"></i> Discount Detailed Report</a></li>
					<li class="@if(request()->segment(2) == 'reports' && request()->segment(3) =='order-difference') active @endif"><a href="{{route('admin.reports.order-difference')}}"><i class="fa fa-circle-o" aria-hidden="true"></i> Order differences</a></li>
                    <li class="@if(request()->segment(2) == 'reports' && request()->segment(3) =='sales-report') active @endif"><a href="{{route('admin.reports.sales-report')}}"><i class="fa fa-circle-o" aria-hidden="true"></i> Sales Report</a></li>
					<li class="@if(request()->segment(2) == 'reports' && request()->segment(3) =='sales-report-per-category') active @endif"><a href="{{route('admin.reports.sales-report-per-category')}}"><i class="fa fa-circle-o" aria-hidden="true"></i> Sales Report as per category</a></li>
					<li class="@if(request()->segment(2) == 'reports' && request()->segment(3) =='daily-product-sales-report') active @endif"><a href="{{route('admin.reports.daily-product-sales-report')}}"><i class="fa fa-circle-o" aria-hidden="true"></i> Daily Products Report</a></li>
					<li class="@if(request()->segment(2) == 'reports' && request()->segment(3) =='daily-milk-product-sales-report') active @endif"><a href="{{route('admin.reports.daily-milk-product-sales-report')}}"><i class="fa fa-circle-o" aria-hidden="true"></i> Milk Products Report</a></li>
					<li class="@if(request()->segment(2) == 'reports' && request()->segment(3) =='customer-statics') active @endif"><a href="{{route('admin.reports.customer-statics')}}"><i class="fa fa-circle-o" aria-hidden="true"></i> Customer Statistics</a></li>
					<li class="@if(request()->segment(2) == 'reports' && request()->segment(3) =='total-driver-rounds') active @endif"><a href="{{route('admin.reports.total-driver-rounds')}}"><i class="fa fa-circle-o" aria-hidden="true"></i> Total Driver Rounds</a></li>
					<li class="@if(request()->segment(2) == 'reports' && request()->segment(3) =='discount-issue-log-report' || request()->segment(3) =='discount-not-appear-on-issue-log') active @endif"><a href="{{route('admin.reports.discount-issue-log-report')}}"><i class="fa fa-circle-o" aria-hidden="true"></i> Customers discount / Issue<br> log matching report</a></li>
					
					<li class="header" style="font-weight: 400;color: #ffffff;"> Start Weekly Reports</li>
                    <li  class="@if(request()->segment(2) == 'reports' && request()->segment(3) =='paid-unpaid-weekly-total') active @endif"><a href="{{route('admin.reports.paid-unpaid-weekly-total')}}"><i class="fa fa-circle-o" aria-hidden="true"></i> Paid Unpaid Totals Weekly</a></li>
                    
					<li  class="@if(request()->segment(2) == 'reports' && request()->segment(3) =='paid-unpaid-customer-weekly') active @endif"><a href="{{route('admin.reports.paid-unpaid-customer-weekly')}}"><i class="fa fa-circle-o" aria-hidden="true"></i> Paid Unpaid Yearly Weekly</a></li>
					
					<li  class="@if(request()->segment(2) == 'reports' && request()->segment(3) =='customer-weekly-statement') active @endif"><a href="{{route('admin.reports.customer-weekly-statement')}}"><i class="fa fa-circle-o" aria-hidden="true"></i> Customer Weekly Statement</a></li>
					
					<li class="header" style="font-weight: 400;color: #ffffff;"> Start Monthly Reports</li>
                    <li  class="@if(request()->segment(2) == 'reports' && request()->segment(3) =='paid-unpaid-monthly-total') active @endif"><a href="{{route('admin.reports.paid-unpaid-monthly-total')}}"><i class="fa fa-circle-o" aria-hidden="true"></i> Paid Unpaid Totals Monthly</a></li>
					<li  class="@if(request()->segment(2) == 'reports' && request()->segment(3) =='customer-monthly-statement') active @endif"><a href="{{route('admin.reports.customer-monthly-statement')}}"><i class="fa fa-circle-o" aria-hidden="true"></i> Customer Monthly Statement</a></li>
					<li  class="@if(request()->segment(2) == 'reports' && request()->segment(3) =='monthly-sales-tax') active @endif"><a href="{{route('admin.reports.monthly-sales-tax')}}"><i class="fa fa-circle-o" aria-hidden="true"></i> Monthly Sales/Tax</a></li>
					<li  class="@if(request()->segment(2) == 'reports' && request()->segment(3) =='delivery-report') active @endif"><a href="{{route('admin.reports.delivery-report')}}"><i class="fa fa-circle-o" aria-hidden="true"></i> Delivery Report New</a></li>
                    <li  class="@if(request()->segment(2) == 'reports' && request()->segment(3) =='delivery-report-summary') active @endif"><a href="{{route('admin.reports.delivery-report-summary')}}"><i class="fa fa-circle-o" aria-hidden="true"></i> Delivery Report Summary</a></li>
					<li  class="@if(request()->segment(2) =='reports' && (request()->segment(3) =='customer-invoice-notes')) active @endif"><a href="{{route('admin.reports.customer-invoice-notes')}}"><i class="fa fa-circle-o" aria-hidden="true"></i> Invoice Notes</a></li>
					
				    <li  class="@if(request()->segment(2) =='bankholidays' && (!request()->segment(3) =='create' || request()->segment(4) =='edit')) active @endif"><a href="{{route('admin.bankholidays.index')}}"><i class="fa fa-circle-o" aria-hidden="true"></i> Bank Holidays</a></li>
					
				    <li  class="@if(request()->segment(2) =='bankholidays' && request()->segment(3) =='create') active @endif"><a href="{{route('admin.bankholidays.create')}}"><i class="fa fa-circle-o" aria-hidden="true"></i> Create Bank Holiday</a></li>
					
					<li  class="@if(request()->segment(3) =='anomolies' && (!request()->segment(4) !='create' || request()->segment(5) !='edit')) active @endif"><a href="{{route('admin.anomolies.index')}}"><i class="fa fa-circle-o" aria-hidden="true"></i> Anomolies </a></li>

                    <!--li><a href="#"><i class="fa fa-circle-o" aria-hidden="true"></i> Sales Report as per category</a></li>
                    
                    <li><a href="#"><i class="fa fa-circle-o" aria-hidden="true"></i> Milk Products Report</a></li>
                    
					<li class="header" style="font-weight: 400;color: #ffffff;">Start Weekly Reports</li>
                    <li><a href="#"><i class="fa fa-circle-o" aria-hidden="true"></i> Paid and Unpaid Totals Weekly</a></li>
                    <li><a href="#"><i class="fa fa-circle-o" aria-hidden="true"></i> Paid and Unpaid Totals Weekly</a></li>
                    <li><a href="#"><i class="fa fa-circle-o" aria-hidden="true"></i> Paid and Unpaid Totals Weekly</a></li>
					<li><a href="#"><i class="fa fa-circle-o" aria-hidden="true"></i>Paid and Unpaid Statements Weekly
					<li><a href="#"><i class="fa fa-circle-o" aria-hidden="true"></i>Paid and Unpaid Yearly Weekly</a></li>
					<li><a href="#"><i class="fa fa-circle-o" aria-hidden="true"></i>Credit Control System Weekly</a></li>
					<li><a href="#"><i class="fa fa-circle-o" aria-hidden="true"></i>Discount Detailed Report</a></li>
					<li><a href="#"><i class="fa fa-circle-o" aria-hidden="true"></i>Total Driver Rounds</a></li>
					<li><a href="#"><i class="fa fa-circle-o" aria-hidden="true"></i>Bank Holidays</a></li>
					<li><a href="#"><i class="fa fa-circle-o" aria-hidden="true"></i>End Weekly Reports</a></li>
					<li><a href="#"><i class="fa fa-circle-o" aria-hidden="true"></i>Start Monthly Reports</a></li>
					<li><a href="#"><i class="fa fa-circle-o" aria-hidden="true"></i>Paid and Unpaid Totals Monthly</a></li>
					<li><a href="#"><i class="fa fa-circle-o" aria-hidden="true"></i>End Monthly Reports</a></li>
					<li><a href="#"><i class="fa fa-circle-o" aria-hidden="true"></i>Paid and Unpaid Totals</a></li>
					<li><a href="#"><i class="fa fa-circle-o" aria-hidden="true"></i>Paid and Unpaid Statements</a></li>
					<li><a href="#"><i class="fa fa-circle-o" aria-hidden="true"></i>Credit Control System</a></li>
					<li><a href="#"><i class="fa fa-circle-o" aria-hidden="true"></i>Paid and Unpaid Yearly</a></li>
					<li><a href="#"><i class="fa fa-circle-o" aria-hidden="true"></i>Delivery Report New</a></li>
					<li><a href="#"><i class="fa fa-circle-o" aria-hidden="true"></i>Delivery Report Summary</a></li>
					<li><a href="#"><i class="fa fa-circle-o" aria-hidden="true"></i>Hear About Us Report</a></li>
					<li><a href="#"><i class="fa fa-circle-o" aria-hidden="true"></i>Statement for Year</a></li>
					<li><a href="#"><i class="fa fa-circle-o" aria-hidden="true"></i>Anomolies</a></li>
					<li><a href="#"><i class="fa fa-circle-o" aria-hidden="true"></i>Monthly Sales/Tax</a></li>
					<li><a href="#"><i class="fa fa-circle-o" aria-hidden="true"></i>Customers Postcode report</a></li>
					<li><a href="#"><i class="fa fa-circle-o" aria-hidden="true"></i>PREP PRODUCE report</a></li>
					<li><a href="#"><i class="fa fa-circle-o" aria-hidden="true"></i>Customers discount / issue log </a></li>
					<li><a href="#"><i class="fa fa-circle-o" aria-hidden="true"></i>matching report</a></li>
					<li><a href="#"><i class="fa fa-circle-o" aria-hidden="true"></i>Invoice Notes</a></li-->

                </ul>
                
            </li>
            @endif 
            
            @if($user->hasPermission('site-setting') || $user->hasRole('superadmin'))		
                <li class="treeview @if(request()->segment(2) == 'settings') active @endif">
                    <a href="#">
                        <i class="fa fa-gear"></i> <span>Site Settings</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li class="treeview @if(request()->segment(2) == 'settings' && request()->segment(3) == 'create') active @endif"><a href="{{ route('admin.settings.create') }}"><i class="fa fa-plus"></i> Save site settings</a></li>
                    </ul>
                </li>	
            @endif
            
            
            
			@endif
			<!-------------Packer Actions------------->
			@if($user->hasRole('packer'))
                            @for ($d = 0; $d < 7; $d++)
                                    @php
                                            $dt = new DateTime();
                                            $m = $dt->modify("+$d days");
                                            $date_is=$m->format("Y-m-d");
                                            $f = $m->format("l");
                                @endphp
                                <li class="treeview @if(request()->segment(4) == $date_is) active @endif">
                                    <a href="{{ (Finder::getOrderCountByDate($date_is)>0) ? route('admin.packer.orders',$date_is) : 'javascript:void(0);'}}">
                                        <i class="fa fa-calendar-check-o" aria-hidden="true"></i> <span>{{$f}} {{(Finder::getOrderCountByDate($date_is)>0) ? '('.Finder::getOrderCountByDate($date_is).')' : '' }}</span>
                                        <span class="pull-right-container">
                                            <i class="fa fa-angle-left pull-right"></i>
                                        </span>
                                    </a>
                                </li>
                            @endfor
			
			@endif
            <!--<li class="header">DELIVERY</li>-->

            <!--
            <li class="header">CONFIG</li>                        
            <li class="treeview @if(request()->segment(2) == 'countries' || request()->segment(2) == 'provinces') active @endif">
                <a href="#">
                    <i class="fa fa-flag"></i> <span>Countries</span>
                    <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ route('admin.countries.index') }}"><i class="fa fa-circle-o"></i> List</a></li>
                </ul>
            </li>-->
			
			
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>

<!-- =============================================== -->