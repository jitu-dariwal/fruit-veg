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
						$d1     = new DateTime($start_date);
						$start    = date('Y-m-d', strtotime($start_date));
						$startday    = date('d', strtotime($start_date));
						$startmonth  = date('m', strtotime($start_date));
						$startyear   = date('Y', strtotime($start_date));
						$d2    = new DateTime($to_date);
						$end    = date('Y-m-d', strtotime($to_date));
						$diff = $d2->diff($d1);
						$diff = $diff->y;
						$startsDa =array();
						$endDa =array();
						for($yY=0;$yY<=$diff;$yY++){
							$startyear=date("Y", strtotime("+$yY years", strtotime($start_date)));
							if($yY==0){
							$startsDa[$yY] = date('d-m-'.$startyear, strtotime($start));
							}else{
							$startsDa[$yY] = date('01-01-'.$startyear, strtotime($start));	
							}
							$endDa[$yY]    = date('31-12-'.$startyear, strtotime($end));
						}
						$c=0;
						
						@endphp
						@foreach($startsDa as $key=>$val)
						
						
						@php
						$c++;
						$startDate = date('Y-m-d', strtotime($val));
						$lastDate  = date('Y-m-d', strtotime($endDa[$key]));
						if(strtotime($lastDate) > strtotime($to_date)){
							$lastDate=$to_date;
							
						}
						$sales=Finder::getSalesReportbyDate($startDate,$lastDate,$show_top,$status,$sort,$export);
						$order_items=Finder::getSalesReportofItems($startDate,$lastDate,$show_top,$status,$sort,$export);
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
						<td>{{date('d-m-Y', strtotime($startDate))}} To {{date('d-m-Y', strtotime($lastDate))}}</td>
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
						<td>{{date('d-m-Y', strtotime($startDate))}} To {{date('d-m-Y', strtotime($lastDate))}}</td>
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