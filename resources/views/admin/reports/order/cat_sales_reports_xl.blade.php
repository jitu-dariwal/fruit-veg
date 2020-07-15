@if(request()->has('report') && request('report')==3)
    @include('admin.reports.partials.weekly_sales_reports')
@elseif(request()->has('report') && request('report')==1)
	@include('admin.reports.partials.yearly_sales_reports')
@elseif(request()->has('report') && request('report')==4)
	@include('admin.reports.partials.daily_sales_reports')
@else
	@include('admin.reports.partials.monthly_sales_reports')
@endif