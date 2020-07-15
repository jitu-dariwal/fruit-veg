<div class="table-responsive">
@php $salesReportType = 0 @endphp 
@if(request()->segment(3) =='sales-report-per-category')
@php $salesReportType = 1 @endphp     
@endif                  
<table class="table table-bordered table-striped">
                        <thead class="thead-light">
                            <tr>
                                <th>Date</th>
                                <th>Orders</th>
                                <th>Items</th>
								@if($salesReportType)
                                <th class="col-md-2">Fruit and Veg</th>
                                <th class="col-md-2">Other Items</th>
								@endif
                                <th>Revenue</th>
                                <th>Shipping</th>
                                <th>Discount</th>
                            </tr>
                        </thead>
                        <tbody>
						@php
						$start    = new DateTime($start_date);
						$end    = new DateTime($to_date);
						$interval = DateInterval::createFromDateString('1 day');
						$period   = new DatePeriod($start, $interval, $end);
						$c=0;
						@endphp
						@foreach($period as $dt)
						
						
						@php
						$c++;
						$startDate=$dt->format("Y-m-d");
						$lastDate=strtotime("+1 day", strtotime($startDate));
						$lastDate= date('Y-m-d', $lastDate);
						if($c==1){
						$startDate=$start_date;
						}
						if(strtotime($lastDate) > strtotime($to_date)){
							$lastDate=$to_date;
						}
						$sales=Finder::getSalesReportbyDate($startDate,$startDate,$show_top,$status,$sort,$export);
						$order_items=Finder::getSalesReportofItems($startDate,$startDate,$show_top,$status,$sort,$export);
						@endphp
						{{--Start Calaculation for sale report as per category--}}
						@if($salesReportType)
							@php
							$fnvItemsRevenue   = 0;
							$otherItemsRevenue = 0;
							@endphp
							@foreach($order_items as $SalesPerCat)
						     @php
							 $parentCatsArr = Finder::getProductsParentCategory($SalesPerCat->product_id);
							 
							 if(count($parentCatsArr)==2){
								 if(isset($parentCatsArr[1])){
									$parentID = $parentCatsArr[1];
								 }else{
									 $parentID = $parentCatsArr[0];
								 }
								}elseif(count($parentCatsArr)==1){
									$parentID = $parentCatsArr[0];
								}else{		
									$parentID = '';
								}
							  if(in_array($parentID, Config::get('constants.FNV_CATEGORY_ARRAY'))){
								$fnvItemsRevenue   += $SalesPerCat->prd_revenue;	 
							  }
							  if(in_array($parentID, Config::get('constants.OTHER_ITEMS_CATEGORY_ARRAY'))){
								$otherItemsRevenue   += $SalesPerCat->prd_revenue;	 
							  }
							 @endphp
						    @endforeach
						@endif
						{{--End Calaculation for sale report as per category--}}
						@if(count($sales)>0)
						@foreach($sales as $sale)
						<tr>
						<td>{{date('d-m-Y', strtotime($startDate))}}</td>
						<td>{{$sale->total_order}}</td>
						<td>{{$order_items->sum('product_qty')}}</td>
						@if($salesReportType)
						<td>{!! config('cart.currency_symbol') !!} {{$fnvItemsRevenue}}</td>
						<td>{!! config('cart.currency_symbol') !!} {{$otherItemsRevenue}}</td>
						@endif
						<td>{!! config('cart.currency_symbol') !!} {{(!empty($sale->revenue))?$sale->revenue:0}}</td>
						<td>{!! config('cart.currency_symbol') !!} {{(!empty($sale->total_shipping))?$sale->total_shipping:0}}</td>
						<td>{!! config('cart.currency_symbol') !!} {{(!empty($sale->total_discount))?$sale->total_discount:0}}</td>
						</tr>
						@endforeach
						@else
						
						<tr>
						<td>{{date('d-m-Y', strtotime($startDate))}}</td>
						<td>0</td>
						<td>0</td>
						@if($salesReportType)
						<td>{!! config('cart.currency_symbol') !!} 0</td>
						<td>{!! config('cart.currency_symbol') !!} 0</td>
						@endif
						<td>{!! config('cart.currency_symbol') !!} 0</td>
						<td>{!! config('cart.currency_symbol') !!} 0</td>
						<td>{!! config('cart.currency_symbol') !!} 0</td>
						</tr>	
						@endif
						@foreach($order_items as $items)
						<tr class="{{(request('detail') && !empty(request('detail'))) ? 'show_items' : 'product_items'}}">
						<td colspan="2"><a href="{{route('admin.products.show',$items->product_id)}}">{{$items->product_name}} {{(!empty($items->type))?'('.$items->type.')':''}}</a></td>
						<td colspan="1">{{$items->product_qty}}</td>
						@if($salesReportType)
						<td colspan="2"></td>
					    @endif
						<td colspan="3">{!! config('cart.currency_symbol') !!} {{(request('detail') && request('detail')==2) ? $items->prd_revenue : '0.00'}}</td>
						</tr>
						@endforeach
						@endforeach
						
                        </tbody>
                    </table>
</div>