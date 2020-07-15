<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/**
 * Admin routes
 */
Route::namespace ('Admin')->group(function () {
    Route::get('admin/login', 'LoginController@showLoginForm')->name('admin.login');
    Route::get('admin/resetpassword', 'LoginController@showResetForm')->name('admin.resetpassword');
    Route::post('admin/login', 'LoginController@login')->name('admin.login');
    Route::post('admin/password/email', 'LoginController@resetPassword')->name('admin.password.email');
    Route::get('admin/logout', 'LoginController@logout')->name('admin.logout');
});
Route::group(['prefix' => 'admin', 'middleware' => ['employee'], 'as' => 'admin.'], function ($api) {
    Route::namespace ('Admin')->group(function ($api) {
        Route::group([], function ($api) {
            //Route::group(['middleware' => ['role:admin|superadmin|clerk, guard:employee']], function () {
            Route::get('/', 'DashboardController@index')->name('dashboard');
            Route::namespace ('Products')->group(function ($api) {
                Route::resource('products', 'ProductController');
                Route::get('remove-image-product', 'ProductController@removeImage')->name('product.remove.image');
                Route::get('remove-image-thumb', 'ProductController@removeThumbnail')->name('product.remove.thumb');
                Route::get('product/productspdf', 'ProductController@productspdf')->name('products.productspdf');
                Route::get('product/productsbulkupdate', 'ProductController@productsbulkupdate')->name('products.productsbulkupdate');
                Route::post('product/filterbulkproducts', 'ProductController@filterbulkproducts')->name('products.filterbulkproducts');
                Route::get('product/bulkprdpriceupdate', 'ProductController@bulkprdpriceupdate')->name('products.bulkprdpriceupdate');
                Route::get('product/bulkprdstockupdate', 'ProductController@bulkprdstockupdate')->name('products.bulkprdstockupdate');
                Route::get('product/subcategories/{id}', 'ProductController@subcategories')->name('products.subcategories');
                Route::post('product/generateprdpdf', 'ProductController@generateprdpdf')->name('products.generateprdpdf');
                Route::post('product/sendpdfemail', 'ProductController@sendpdfemail')->name('products.sendpdfemail');
                Route::post('product/filterproducts', 'ProductController@filterproducts')->name('products.filterproducts');
                Route::post('product/sortpdfproducts', 'ProductController@sortpdfproducts')->name('products.sortpdfproducts');
            });
            Route::namespace ('Customers')->group(function () {
                Route::resource('customers', 'CustomerController');
                Route::resource('customersgroup', 'CustomerGroupController');
                Route::resource('customers.addresses', 'CustomerAddressController');
                Route::get('customers/email/{id}', 'CustomerController@email')->name('customers.email');
                Route::post('customers/sendmail', 'CustomerController@sendmail')->name('customers.sendmail');
                Route::post('customers/updatechase', 'CustomerController@updatechase')->name('customers.updatechase');
                //Route::get('customers/groups/list', 'CustomerController@groups')->name('customers.groups');
                //Route::get('customers/group/create', 'CustomerController@creategroup')->name('customers.creategroup');
                //Route::post('customers/storegroup', 'CustomerController@storegroup')->name('customers.storegroup');
                //Route::get('customers/group/editgroup/{id}', 'CustomerController@editgroup')->name('customers.editgroup');
                //Route::post('customers/groupupdate', 'CustomerController@customer_groupupdate')->name('customers.groupup_date');
            });

            Route::namespace ('Appusers')->group(function () {
                Route::resource('appusers', 'AppuserController');
                Route::get('appusers/apporders/list', 'AppuserController@apporders')->name('appusers.apporders');
                Route::post('appusers/apporders/list', 'AppuserController@apporders')->name('appusers.apporders');
                Route::post('appusers/exportapporders/list', 'AppuserController@exportapporders')->name('appusers.exportapporders');
                Route::get('appusers/editapporder/{id}', 'AppuserController@editapporder')->name('appusers.editapporder');
                Route::post('appusers/updateorder', 'AppuserController@updateorder')->name('appusers.updateorder');
            });

            Route::namespace ('Pages')->group(function () {
                Route::resource('pages', 'PagesController');
				Route::get('pages/dashboard/sections', 'PagesController@dashboardSections')->name('dashboardSections');
				
				Route::get('pages/dashboard/sections/add', 'PagesController@dashboardSectionCreate')->name('dashboardSectionsAdd');
				
				Route::post('pages/dashboard/sections/add', 'PagesController@dashboardSectionSave')->name('dashboardSectionsSave');
				
				Route::get('pages/dashboard/sections/edit/{id}', 'PagesController@dashboardSectionEdit')->name('dashboardSectionsEdit');
				
				Route::post('pages/dashboard/sections/edit/{id}', 'PagesController@dashboardSectionUpdate')->name('dashboardSectionsUpdate');
            });

            Route::namespace ('Manufacturers')->group(function () {

                Route::resource('manufacturers', 'ManufacturerController');
            });

            Route::namespace ('Webservices')->group(function () {
                Route::resource('webservices', 'WebserviceController');
                //Route::get('webservices', 'WebserviceController@index')->name('webservices.index');
            });

            Route::namespace ('Coupons')->group(function () {

                Route::resource('coupons', 'CouponController');
                Route::post('coupons/selectproduct', 'CouponController@selectproduct')->name('coupons.selectproduct');
                Route::post('coupons/selectcategory', 'CouponController@selectcategory')->name('coupons.selectcategory');
                Route::get('coupons/email/{id}', 'CouponController@email')->name('coupons.email');
                Route::get('coupons/reports/{id}', 'CouponController@reports')->name('coupons.reports');
                Route::post('coupons/sendmail', 'CouponController@sendmail')->name('coupons.sendmail');
                Route::get('coupons/sentcoupon/user', 'CouponController@sentcoupon')->name('coupons.sentcoupon');

            });

            Route::namespace ('Bankholidays')->group(function () {

                Route::resource('bankholidays', 'BankholidayController');
            });

            Route::namespace ('Categories')->group(function () {
                Route::resource('categories', 'CategoryController');
                Route::get('remove-image-category', 'CategoryController@removeImage')->name('category.remove.image');
            });
            Route::namespace ('Orders')->group(function () {
                Route::resource('orders', 'OrderController');
                Route::post('multi-orders-status-update', 'OrderController@updateMultiOrderStatus')->name('orders.multi_order_status_update');
                Route::get('orders-list/{customer}', 'OrderController@customerOrdersList')->name('orders.customer_order_list');
                Route::get('orders/category-products/{type}/{cat}', 'OrderController@getCategoryProducts');
                Route::get('order/{id}/add-products', 'OrderController@addProductstoOrder')->name('orders.addproducts');
                Route::post('order/{id}/add-products', 'OrderController@addOrderProduct')->name('orders.storeproducts');
                Route::get('order/payment/{id}/pay-with-cc', 'OrderController@payWithCC')->name('orders.paywith-cc');
                Route::get('order/order-payment/{id}/updatestatus', 'OrderController@orderPaymentStatus')->name('orders.order-payment-status');
                Route::get('orders/product/remove/{product_id}/{order_id}', 'OrderController@destroyOrderProduct');
                Route::post('order/update-products-info/update', 'OrderController@updateOrderProduct')->name('orders.update-order-product-details');
                Route::post('order/delivery-info/update', 'OrderController@updateOrderDeliveryinfo')->name('orders.update-order-delivery-details');
                //Temp Basket Process Urls
                Route::get('order-product/{id}/add-multi-products/{catid?}', 'TempBasketController@index')->name('orders.tailor_made');
                Route::get('order-product/update-product-to-cart', 'TempBasketController@updateshoppinglist')->name('orders.tailor_made.updateproduct');
                Route::get('order-product/remove-basket-product/{product_id}', 'TempBasketController@deletebasketproduct')->name('orders.tailor_made.removeproducts');
                Route::post('order-product/add-order-product/{order_id}', 'TempBasketController@add_order_product')->name('orders.tailor_made.addproducts');

                Route::resource('order-statuses', 'OrderStatusController');
                Route::get('orders/{id}/invoice', 'OrderController@generateInvoice')->name('orders.invoice.generate');
                Route::get('orders/{id}/packing_slip', 'OrderController@generatePackingslip')->name('orders.packing_slip.generate');
                Route::get('orders/customer/{id}', 'OrderController@getCustomerDetailForm')->name('orders.customer.select');
            });
            //Packers
            Route::namespace ('Packers')->group(function () {
                Route::group(['prefix' => 'packer', 'as' => 'packer.'], function () {
                    Route::get('/', 'PackerController@index')->name('index');
                    Route::get('/order/{date}', 'PackerController@ordersbydate')->name('orders');
                    Route::get('/order/{orderid}/view', 'PackerController@orderdetail')->name('order.show');
                    Route::post('/update-products-status', 'PackerController@updateOrderProductstatus')->name('update_product_status');
                    Route::post('/update-order-status', 'PackerController@updateOrderstatus')->name('update_order_status');
                    Route::get('/add-product-actual-weight/{order_id}/{product_id}', 'PackerController@viewCal')->name('view_cal');
                    Route::post('/add-product-actual-weight/{order_id}/{product_id}', 'PackerController@calculateActualWeight')->name('calculated_wieght');
                    //Route::post('admin/packer/add-product-actual-weight/{order_id}/{product_id}', 'Admin\Packers\PackerController@calculateActualWeight');
                });
            });

            /*******************************************/
            /*****************Reports*******************/
            /*******************************************/
            Route::group(['middleware' => ['reportsview']], function () {
                Route::namespace ('Reports')->group(function () {
                    Route::group(['prefix' => 'reports', 'as' => 'reports.'], function () {
                        Route::get('/products-purchased', 'ProductReportController@productsPurchased')->name('productspurchased');
                        Route::get('/customers-order-total', 'CustomerReportController@customerOrderTotal')->name('customers-order-total');
                        Route::get('/not-valid-customers', 'CustomerReportController@notValidCustomers')->name('not-valid-customers');
                        Route::get('/send-verification-mail/{id}', 'CustomerReportController@sendValidationEmail')->name('send-verification-mail');
                        Route::get('/export-not-valid-customers', 'CustomerReportController@exportNotValidCustomers')->name('export-not-valid-customers');
                        Route::post('/remove-not-valid-customers', 'CustomerReportController@removeNotValidCustomers')->name('remove-not-valid-customers');
                        Route::get('/sales-report', 'OrderReportController@salesReport')->name('sales-report');
                        Route::get('/sales-report-per-category', 'OrderReportController@salesAsPerCatReport')->name('sales-report-per-category');
                        Route::get('/daily-milk-product-sales-report', 'OrderReportController@dailyMilkProductsalesReport')->name('daily-milk-product-sales-report');
                        Route::get('/daily-product-sales-report', 'OrderReportController@dailyProductsalesReport')->name('daily-product-sales-report');
                        Route::get('/print-daily-product-sales-report', 'OrderReportController@printDailyProductsalesReport')->name('print-daily-product-sales-report');
                        Route::get('/print-daily-milkproduct-sales-report', 'OrderReportController@printMilkDailyProductsalesReport')->name('print-daily-milkproduct-sales-report');
                        Route::get('/export-daily-product-sales-report', 'OrderReportController@exportDailyProductsales')->name('export-daily-product-sales-report');
                        Route::get('/export-daily-milkproduct-sales-report', 'OrderReportController@exportDailyMilkProductsales')->name('export-daily-milkproduct-sales-report');
                        Route::get('/customer-statics', 'CustomerReportController@customerStatics')->name('customer-statics');
                        Route::get('/inactive-client-report', 'CustomerReportController@inactiveClients')->name('inactive-client-report');
                        Route::get('/export-inactive-client-report', 'CustomerReportController@exportInactiveClients')->name('export-inactive-client-report');
                        Route::get('/active-client-report', 'CustomerReportController@activeClients')->name('active-client-report');
                        Route::get('/export-active-client-report', 'CustomerReportController@exportActiveClients')->name('export-active-client-report');
                        Route::get('/never-ordered-client-report', 'CustomerReportController@neverOrderedClients')->name('never-ordered-client-report');
                        Route::get('/export-never-ordered-client-report', 'CustomerReportController@exportNeverOrderedClients')->name('export-never-ordered-client-report');
                        Route::get('/customer-discount-report/{export?}', 'CustomerReportController@customerDiscounts')->name('customer-discount-report');
                        Route::get('/export-customer-discount-report', 'CustomerReportController@exportCustomerDiscounts')->name('export-customer-discount-report');
                        Route::get('/order-difference', 'OrderReportController@orderDifference')->name('order-difference');
                        Route::get('/export-order-difference', 'OrderReportController@exportOrderDifference')->name('export-order-difference');

                        Route::get('/paid-unpaid-weekly-total', 'WeeklyReportController@paidunpaidWeeklyTotal')->name('paid-unpaid-weekly-total');
                        Route::get('/paid-unpaid-weekly-total-export', 'WeeklyReportController@paidunpaidWeeklyTotalExport')->name('paid-unpaid-weekly-total-export');
                        Route::get('/weekly-invoice', 'WeeklyReportController@weeklyInovice')->name('weekly-invoice');
                        Route::post('/mail-weekly-invoice', 'WeeklyReportController@emailWeeklyInovice')->name('mail-weekly-invoice');
                        Route::get('/customer-weekly-statement', 'WeeklyReportController@customerWeeklyStatement')->name('customer-weekly-statement');
                        Route::get('/paid-unpaid-customer-weekly', 'WeeklyReportController@paidunpaidCustomerWeekly')->name('paid-unpaid-customer-weekly');

                        Route::get('/paid-unpaid-monthly-total', 'MonthlyReportController@paidunpaidMonthlyTotal')->name('paid-unpaid-monthly-total');
                        Route::get('/monthly-invoice', 'MonthlyReportController@monthlyInovice')->name('monthly-invoice');
                        Route::post('/mail-monthly-invoice', 'MonthlyReportController@emailMonthlyInovice')->name('mail-monthly-invoice');
                        Route::get('/customer-monthly-statement', 'MonthlyReportController@customerMonthlyStatement')->name('customer-monthly-statement');
                        Route::get('/monthly-sales-tax', 'MonthlyReportController@monthlySalesTax')->name('monthly-sales-tax');
                        Route::get('/export-monthly-sales-tax', 'MonthlyReportController@exportMonthlySalesTax')->name('export-monthly-sales-tax');
                        Route::get('/delivery-report', 'DeliveryReportController@newDeliveryReport')->name('delivery-report');
                        Route::get('/export-delivery-report', 'DeliveryReportController@exportNewDeliveryReport')->name('export-delivery-report');
                        Route::get('/delivery-report-summary', 'DeliveryReportController@deliveryReportSummary')->name('delivery-report-summary');
                        Route::post('/delivery-report-summary', 'DeliveryReportController@assignDriverToOrder')->name('delivery-report-summary');
                        Route::get('/delivery-report-summary-export/{driver?}', 'DeliveryReportController@deliveryReportSummaryExport')->name('delivery-report-summary-export');
                        Route::get('/delivery-summary-fleetmatics-export/{driver?}', 'DeliveryReportController@deliveryPOIAddressExport')->name('delivery-summary-fleetmatics-export');
                        Route::get('/set-driver', 'DeliveryReportController@getsetDriver')->name('set-driver');
                        Route::post('/set-driver', 'DeliveryReportController@setDriverName')->name('update-driver-name');
                        Route::get('/free-delivery-invoice/{driver?}', 'DeliveryReportController@freeDeliveryInvoice')->name('free-delivery-invoice');
                        Route::get('/free-delivery-packing-slip/{driver?}', 'DeliveryReportController@freeDeliveryPackingslip')->name('free-delivery-packing-slip');
                        Route::get('/rounds-report/{driver}', 'DeliveryReportController@driverRoundReport')->name('rounds-report');
                        Route::get('/total-driver-rounds', 'DeliveryReportController@totaldriverRoundsReport')->name('total-driver-rounds');
                        Route::get('/export-total-driver-rounds', 'DeliveryReportController@exporttotaldriverRoundsReport')->name('export-total-driver-rounds');
                        Route::get('/customer-invoice-notes', 'CustomerReportController@customerInvoiceNotes')->name('customer-invoice-notes');
                        Route::get('/export-customer-invoice-notes', 'CustomerReportController@exportCustomerInvoiceNotes')->name('export-customer-invoice-notes');
                        Route::get('/discount-detials', 'CustomerReportController@detailDiscounts')->name('discount-detials');
                        Route::get('/discount-detials-report-export', 'CustomerReportController@exportDetailDiscounts')->name('export-discount-detailed-report');
                        Route::get('/discount-issue-log-report', 'IssuesReportController@index')->name('discount-issue-log-report');
                        Route::get('/discount-not-appear-on-issue-log', 'IssuesReportController@discountNotApperar')->name('discount-not-appear-on-issue-log');
                        Route::get('/discounts-only-in-issue-logs', 'IssuesReportController@discountOnlyInIssueslogs')->name('discounts-only-in-issue-logs');
                        Route::get('/financial-implication-report', 'IssuesReportController@discountmisMatchInIssueslogs')->name('financial-implication-report');

                    });
                });
            });
            //Invoice Routes
            Route::get('/get-week/{year}', 'Invoice\InvoiceController@getweek')->name('get_week');
            Route::get('/add-invoices', 'Invoice\InvoiceController@create')->name('invoice.add');
            Route::get('/invoices', 'Invoice\InvoiceController@index')->name('invoice.index');
            Route::get('/lock-invoice/{id}', 'Invoice\InvoiceController@lockInvoice')->name('invoice.lock');
            Route::post('/update-invoice/payment-method', 'Invoice\InvoiceController@updatePaymentMethod')->name('invoice.update-payment-method');
            Route::post('/multi-invoice-status/update', 'Invoice\InvoiceController@createMultiInvoice')->name('invoice.update-multi-invoice-status');
            Route::post('/update-invoice/remittance', 'Invoice\InvoiceController@updateRemittance')->name('invoice.update-remittance');
            Route::post('/update-invoice/paid-date', 'Invoice\InvoiceController@updatePaidDate')->name('invoice.update-paid-date');
            Route::post('/update-invoice-po-number', 'Invoice\InvoiceController@updateInvoicePONumber')->name('invoice.update-invoice-po-number');
            Route::get('/lock-po-number/{id}', 'Invoice\InvoiceController@lockPONumber')->name('invoice.po_number.lock');
            //Invoice Notes
            Route::namespace ('InvoiceNotes')->group(function () {
                Route::group(['prefix' => 'invoice-notes', 'as' => 'invoice-notes.'], function () {
                    Route::get('/view/{customer_id}/{invoiceid}', 'InvoiceNoteController@index')->name('list');
                    Route::get('/create/{customer_id}/{invoiceid}', 'InvoiceNoteController@create')->name('create');
                    Route::post('/store', 'InvoiceNoteController@store')->name('store');
                    Route::get('/edit/{id}', 'InvoiceNoteController@edit')->name('edit');
                    Route::post('/update/{id}', 'InvoiceNoteController@update')->name('update');
                    Route::delete('/destroy/{id}', 'InvoiceNoteController@destroy')->name('destroy');
                });
            });
            //Invoice Part Payments
            Route::namespace ('InvoicePartPayments')->group(function () {
                Route::group(['prefix' => 'invoice-part-payments', 'as' => 'invoice-part-payments.'], function () {
                    Route::get('/view/{customer_id}/{invoiceid}', 'InvoicePartPaymentController@index')->name('list');
                    Route::get('/create/{customer_id}/{invoiceid}', 'InvoicePartPaymentController@create')->name('create');
                    Route::post('/store', 'InvoicePartPaymentController@store')->name('store');
                });
            });
            Route::resource('addresses', 'Addresses\AddressController');
            Route::resource('countries', 'Countries\CountryController');
            Route::resource('countries.provinces', 'Provinces\ProvinceController');
            Route::resource('countries.provinces.cities', 'Cities\CityController');
            Route::resource('couriers', 'Couriers\CourierController');
            Route::resource('attributes', 'Attributes\AttributeController');
            Route::resource('attributes.values', 'Attributes\AttributeValueController');
            Route::resource('brands', 'Brands\BrandController');
        });
        // Route::group(['middleware' => ['role:admin|superadmin, guard:employee']], function () {

        Route::group([], function () {
            Route::resource('settings', 'SettingController');
            Route::get('setting', 'SettingController@index')->name('setting.index');
            Route::put('setting/{id}', 'SettingController@update')->name('setting.update');

            Route::get('cron/invoices_weekly', 'CronController@invoices_weekly')->name('cron.invoices_weekly');
            Route::get('cron/invoices_weekly_per_customer', 'CronController@invoices_weekly_per_customer')->name('cron.invoices_weekly_per_customer');
            Route::get('cron/invoices_weekly_paid_status', 'CronController@invoices_weekly_paid_status')->name('cron.invoices_weekly_paid_status');
            Route::get('cron/invoices_weekly_per_customer_paid_status', 'CronController@invoices_weekly_per_customer_paid_status')->name('cron.invoices_weekly_per_customer_paid_status');
            Route::get('cron/invoices_monthly', 'CronController@invoices_monthly')->name('cron.invoices_monthly');
            Route::get('cron/invoices_monthly_per_customer', 'CronController@invoices_monthly_per_customer')->name('cron.invoices_monthly_per_customer');

            // Route::group(['middleware' => ['role:admin|superadmin, guard:employee']], function () {
            Route::resource('employees', 'EmployeeController');
            // });

            Route::get('employees/{id}/profile', 'EmployeeController@getProfile')->name('employee.profile');
            Route::put('employees/{id}/profile', 'EmployeeController@updateProfile')->name('employee.profile.update');
            Route::resource('roles', 'Roles\RoleController');
            Route::resource('permissions', 'Permissions\PermissionController');
            Route::get('permissions/permission/denied', 'Permissions\PermissionController@permission_denied')->name('permissions.permission_denied');
            Route::get('role/permissions', 'Roles\RoleController@managePermissions')->name('roles.permissions');
            Route::post('role/permissions/save', 'Roles\RoleController@savePermissions')->name('roles.permissions.save');
        });

        $api->group([
            'namespace' => 'Logs',
            'prefix'    => 'issue-log',
            'as'        => 'issuelog.',

        ], function ($api) {

            $api->get('/', 'IssuelogController@index')->name('index');
            $api->get('/create', 'IssuelogController@create')->name('create');
            $api->post('/', 'IssuelogController@store')->name('store');
            $api->get('/{id}/edit', 'IssuelogController@edit')->name('edit');
            $api->post('/{id}/update', 'IssuelogController@update')->name('update');
            $api->get('/{id}/delete', 'IssuelogController@destroy')->name('delete');
            $api->get('/{id}', 'IssuelogController@show')->name('show');
            $api->get('/print/log', 'IssuelogController@printLog')->name('print');

        });

        $api->group([
            'namespace' => 'Logs',
            'prefix'    => 'amendment-log-report',
            'as'        => 'amendmentlogreport.',

        ], function ($api) {

            $api->get('/', 'AmendmentLogReportController@index')->name('index');
            $api->get('/create', 'AmendmentLogReportController@create')->name('create');
            $api->post('/', 'AmendmentLogReportController@store')->name('store');
            $api->get('/{id}/edit', 'AmendmentLogReportController@edit')->name('edit');
            $api->post('/{id}/update', 'AmendmentLogReportController@update')->name('update');
            $api->get('/{id}/delete', 'AmendmentLogReportController@destroy')->name('delete');
            $api->get('/{id}', 'AmendmentLogReportController@show')->name('show');
            $api->get('/print/log', 'AmendmentLogReportController@printLog')->name('print');

        });

        $api->group([
            'namespace' => 'Logs',
            'prefix'    => 'communication-log',
            'as'        => 'communicationlog.',

        ], function ($api) {

            $api->get('/', 'CommunicationLogController@index')->name('index');
            $api->get('/create', 'CommunicationLogController@create')->name('create');
            $api->post('/', 'CommunicationLogController@store')->name('store');
            $api->get('/{id}/edit', 'CommunicationLogController@edit')->name('edit');
            $api->post('/{id}/update', 'CommunicationLogController@update')->name('update');
            $api->get('/{id}/delete', 'CommunicationLogController@destroy')->name('delete');
            $api->get('/{id}', 'CommunicationLogController@show')->name('show');
            $api->get('/print/log', 'CommunicationLogController@printLog')->name('print');

        });

        $api->group([
            'namespace' => 'Lead',
            'prefix'    => 'lead',
            'as'        => 'lead.',

        ], function ($api) {

            $api->get('/', 'LeadController@index')->name('index');
            $api->get('/create', 'LeadController@create')->name('create');
            $api->post('/', 'LeadController@store')->name('store');
            $api->get('/{id}/edit', 'LeadController@edit')->name('edit');
            $api->post('/{id}/update', 'LeadController@update')->name('update');
            $api->get('/{id}/delete', 'LeadController@destroy')->name('delete');
            $api->get('/{id}', 'LeadController@show')->name('show');
            $api->get('/print/log', 'LeadController@printLog')->name('print');

            $api->group([
                'prefix' => 'chase/{lead}',
                'as'     => 'chase.',

            ], function ($api) {

                $api->get('/create', 'LeadController@create')->name('create');
                $api->post('/', 'LeadController@store')->name('store');
                $api->get('/{id}/edit', 'LeadController@edit')->name('edit');
                $api->post('/{id}/update', 'LeadController@update')->name('update');
                $api->get('/{id}/delete', 'LeadController@destroy')->name('delete');
                $api->get('/{id}', 'LeadController@show')->name('show');
                $api->get('/print/log', 'LeadController@printLog')->name('print');

            });
        });

        $api->group([
            'namespace' => 'Anomolies',
            'prefix'    => 'reports/anomolies',
            'as'        => 'anomolies.',

        ], function ($api) {

            $api->get('/', 'AnomolieController@index')->name('index');
            $api->get('/create', 'AnomolieController@create')->name('create');
            $api->post('/', 'AnomolieController@store')->name('store');
            $api->get('/{id}/edit', 'AnomolieController@edit')->name('edit');
            $api->post('/{id}/update', 'AnomolieController@update')->name('update');
            $api->get('/{id}/delete', 'AnomolieController@destroy')->name('delete');
            $api->get('/{id}', 'AnomolieController@show')->name('show');
            $api->get('/print/log', 'AnomolieController@printLog')->name('print');

        });

        $api->group([
            'namespace' => 'UpdateInvoiceStatus',
            'prefix'    => 'update-invoice-status',
            'as'        => 'update_invoice_status.',

        ], function ($api) {

            $api->get('/', 'UpdateInvoiceStatusController@index')->name('index');
            $api->post('/login', 'UpdateInvoiceStatusController@login')->name('login');
            $api->post('/lock', 'UpdateInvoiceStatusController@lock')->name('lock');
            $api->post('/update-po-unmber', 'UpdateInvoiceStatusController@update_po_unmber')->name('update_po_unmber');
            $api->post('/update-payment-status', 'UpdateInvoiceStatusController@update_payment_status')->name('update_payment_status');

        });

        $api->group([
            'namespace' => 'PrepProduceReport',
            'prefix'    => 'prep-produce-report',
            'as'        => 'prep_produce_report.',

        ], function ($api) {

            $api->get('/', 'PrepProduceReportController@index')->name('index');
            $api->get('/print-report', 'PrepProduceReportController@printReport')->name('printreport');

        });

        $api->group([
            'namespace' => 'CustomersPostcodeListReport',
            'prefix'    => 'customers-postcode-list-report',
            'as'        => 'customers_postcode_list_report.',

        ], function ($api) {

            $api->get('/', 'CustomersPostcodeListReportController@index')->name('index');
            $api->post('/', 'CustomersPostcodeListReportController@exportReport')->name('exportreport');

        });
		
		Route::namespace ('Faq')->group(function () {
			Route::resource('faq', 'FaqController');
		});
		
		Route::namespace ('PostCode')->group(function () {
			Route::resource('post-code', 'PostCodeController');
		});
    });
});

/**
 * Frontend routes
 */
Auth::routes();
Route::namespace('Auth')->group(function () {
	Route::get('cart/login', 'CartLoginController@showLoginForm')->name('cart.login');
	Route::post('cart/login', 'CartLoginController@login')->name('cart.login');
	Route::get('logout', 'LoginController@logout');

	Route::post('/register-step1', 'RegisterController@step1')->name('registerStep1');
	
	Route::get('/register-step{num}', 'RegisterController@step')->name('registerStep');
	
	Route::post('/register-step2', 'RegisterController@step2')->name('registerStep2');
	Route::post('/register-step3', 'RegisterController@step3')->name('registerStep3');
	Route::post('/register-step4', 'RegisterController@register')->name('registerStep4');
	
	Route::get('/register-success/{id}', 'RegisterController@success')->name('registerSuccess');

	Route::get('/user/verify/{token}', 'RegisterController@verifyUser');
  
});

Route::namespace ('Front')->group(function () {
	
    Route::get('/', 'HomeController@index')->name('home');
    Route::get('/faq', 'HomeController@faq')->name('faq');
    Route::get('/sitemap', 'PageController@sitemap')->name('sitemap');
	Route::get('contact-us', 'PageController@contactUs')->name('page.contactUs');
	Route::post('contact-us', 'PageController@saveContactUs')->name('page.saveContactUs');
    Route::group(['middleware' => ['auth', 'web']], function () {

        Route::namespace ('Payments')->group(function () {
            Route::get('bank-transfer', 'BankTransferController@index')->name('bank-transfer.index');
            Route::post('bank-transfer', 'BankTransferController@store')->name('bank-transfer.store');
        });

        Route::namespace ('Addresses')->group(function () {
            Route::resource('country.state', 'CountryStateController');
            Route::resource('state.city', 'StateCityController');
        });

        Route::get('accounts', 'AccountsController@index')->name('accounts');
        Route::post('accounts/update/{id}', 'AccountsController@update')->name('accounts.update');
		
		Route::get('accounts/details/{id}', 'AccountsController@accountdetails')->name('accounts.accountdetails');
		
		Route::get('accounts/addressbook/{id?}', 'AccountsController@addressbook')->name('accounts.addressbook');
		
		Route::get('accounts/orders', 'AccountsController@orders')->name('accounts.orders');
		Route::get('accounts/orderdetail/{id}', 'AccountsController@orderdetail')->name('accounts.orderdetail');
		Route::get('accounts/order-print-download/{id}/{type?}', 'AccountsController@orderprint')->name('accounts.orderprintdownload');
		
		Route::get('accounts/statements/{id}', 'AccountsController@accountstatements')->name('accounts.accountstatements');
		
		Route::get('accounts/payments/{id}', 'AccountsController@accountpayments')->name('accounts.accountpayments');
		
		Route::get('accounts/payments/card/add/{id?}', 'AccountsController@accountpaymentsaddedit')->name('accounts.accountpaymentsaddedit');
		Route::post('accounts/payments/card/add/{id?}', 'AccountsController@savepaymentsaddedit')->name('accounts.savepaymentsaddedit');
		
		Route::post('accounts/payments/card/delete/{id}', 'AccountsController@deleteCard')->name('accounts.deleteCard');

        Route::get('order/copy/{order_id}', 'AccountsController@order_copy')->name('order.copy');
        
		Route::get('delivery-address', 'CheckoutController@index')->name('checkout.index');
        Route::post('delivery-address', 'CheckoutController@storeDeliveryAdd')->name('checkout.storeDeliveryAdd');
		
		Route::get('confirm', 'CheckoutController@confirm')->name('checkout.confirm');
		
        Route::post('apply-coupon', 'CheckoutController@storeConfirmation')->name('checkout.storeConfirmation');
		
		Route::get('payment-method/{order_id?}', 'CheckoutController@payment')->name('checkout.payment');
		Route::post('payment-method/{order_id?}', 'CheckoutController@storePayment')->name('checkout.storePayment');
		
        Route::get('thank-you/{order_id}', 'CheckoutController@thankYou')->name('checkout.thankYou');
		
        Route::resource('customer.address', 'CustomerAddressController');
		
		Route::get('customer/{customerid}/billing-address', 'CustomerAddressController@editBilling')->name('customer.address.billing');
        Route::post('customer/{customerid}/billing-address/update', 'CustomerAddressController@updateBilling')->name('customer.address.billingUpdate');
		
		
		/* Extra URLS*/
		
        Route::get('checkout-test', 'CheckoutController@testIndex')->name('checkout.test-index');
        Route::post('checkout', 'CheckoutController@store')->name('checkout.store');
        Route::post('checkout/confirmcheckout/{id}', 'CheckoutController@confirmcheckout')->name('checkout.confirmcheckout');
        Route::post('checkout/addorder/{id}', 'CheckoutController@addorder')->name('checkout.addorder');
        Route::get('checkout/execute', 'CheckoutController@executePayPalPayment')->name('checkout.execute');
        Route::post('checkout/execute', 'CheckoutController@charge')->name('checkout.execute');
        Route::get('checkout/cancel', 'CheckoutController@cancel')->name('checkout.cancel');
        Route::get('checkout/success/{id}', 'CheckoutController@checkoutsuccess')->name('checkout.success');
        Route::get('checkout/updatestatus/{id}', 'CheckoutController@updatestatus')->name('checkout.updatestatus');
		
        Route::get('customer/{customerid}/address/{addressid}/updateprimary', 'CustomerAddressController@updateprimary')->name('customer.address.updateprimary');
        
        Route::post('accounts/updatenewsletter/{id}', 'AccountsController@updatenewsletter')->name('accounts.updatenewsletter');
        Route::post('accounts/updateproductnotification/{id}', 'AccountsController@updateproductnotification')->name('accounts.updateproductnotification');
        Route::get('accounts/productslist', 'AccountsController@productslist')->name('accounts.productslist');
        Route::get('accounts/deletebasketproduct/{id}', 'AccountsController@deletebasketproduct')->name('accounts.deletebasketproduct');
        Route::get('accounts/updateshoppinglist', 'AccountsController@updateshoppinglist')->name('accounts.updateshoppinglist');
		
        Route::get('products/searchproduct', 'ProductController@searchproduct')->name('products.searchproduct');
        Route::get('products/subcategories/{id}', 'ProductController@subcategories')->name('products.subcategories');
        Route::post('products/search', 'ProductController@search')->name('products.search');
    });
	
    Route::resource('cart', 'CartController');
	Route::get('carts/updateshoppingcart', 'CartController@updateshoppingcart')->name('carts.updateshoppingcart');

	/* Route::get("category", 'CategoryController@getParentCategories')->name('front.parentcategories');
	Route::get('category/{slug}', 'CategoryController@getSubCategories')->name('front.subcategories');
    Route::get("category/{parent_category}/{sub_category}", 'CategoryController@getCategory')->name('front.category.slug'); */
	
	Route::get("favourites/{customer_id?}", 'ProductController@getFavouritesProducts')->name('front.favproducts');
	
    Route::get("search", 'ProductController@search')->name('search.product');
	
	Route::get('/shop', 'CategoryController@shop')->name('page.shop');
	Route::get('/guest-login', 'CartController@checkAuth')->name('page.auth');
	Route::get('/{slug}/{child_slug?}/{childOfChild_slug?}', 'CategoryController@index')->name('page.index');
	
    //Route::get("{product}", 'ProductController@show')->name('front.get.product');
	
	Route::group(['prefix' => 'ajax', 'as' => 'ajax.'], function () {
		Route::post('/add/favourite', 'AjaxController@add_fav')->name('addFav');
		Route::post('/check/order-coupon-status', 'AjaxController@checkOrderCouponCodeStatus')->name('checkOrderCouponCodeStatus');
	});
});

Route::namespace ('Admin\InvoicePartPayments')->group(function () {
	Route::group(['prefix' => 'payment-with-cc', 'as' => 'payment-with-cc.'], function () {
		Route::get('/{invoiceId}', 'PaymentWithCCController@index')->name('index');
		Route::get('/updatestatus/{invoiceId}', 'PaymentWithCCController@updateStatus')->name('statusupdate');
	});
});

/* Function for print array in formated form */
if(!function_exists('pr')){
	function pr($array){
		echo "<pre>";
		print_r($array);
		echo "</pre>";
	}
}
	
/* Function for print query log */
if(!function_exists('qLog')){
	DB::enableQueryLog();
	function qLog(){
		pr(DB::getQueryLog());
	}
}