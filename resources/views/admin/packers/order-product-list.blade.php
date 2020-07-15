<div class="box-body table-responsive">
                        <h4> 
						Packing Order {{$order->id}}
						</h4>
                        <table class="table table-striped ">
                            <thead>
							<tr>
                            <th class="col-md-1">Code</th>
                            <th class="col-md-2">Product Description</th>
                            <th class="col-md-1">Amount</th>
                            <th class="col-md-1">Packet Size</th>
                            <th class="col-md-1">Type</th>
                            <th class="col-md-1">Current Price</th>
                            <th class="col-md-2">Actual Weight</th>
                            <th class="col-md-2">Packed</th>
                            
                            <th class="col-md-2">Not Available</th>
                            <th class="col-md-2">Short</th>
							<th class="col-md-2">Estimated Total</th>
							</tr>
                            </thead>
                            <tbody>
							@php $sub_total=0; @endphp
						   @if(count($order->orderproducts)>0)
                           @foreach($order->orderproducts as $orderproduct)
                                <tr>
                                  <td>{{$orderproduct->product_code}}</td>
                                  <td>
								  <input type="hidden" name="order_product[]" value="{{$orderproduct->product_id}}"><textarea name="update_products[{{$orderproduct->product_id}}][p_name]" readonly="" cols="12" rows="4">{{$orderproduct->product_name}}</textarea></td>
                                  <td>{{$orderproduct->quantity}}</td>
                                  <td>{{$orderproduct->packet_size}}</td>
                                  <td>{{$orderproduct->type}}</td>
                                  <td>
								  {{$orderproduct->product_price}}
								  
								  </td>
                                  <td>
								  <a href="{{ route("admin.packer.view_cal", ["order_id" => $order->id, "product_id" => $orderproduct->product_id]) }}" class="actual_wgt_cal"> <font color="red" size="10"><b>
								  <img src="{{asset('images/ques.gif')}}" border="0" alt="Question Mark" title="Question Mark" align="absmiddle"></b></font></a><br>
								  <p>({{$orderproduct->actual_weight}} {{$orderproduct->weight_unit}})</p>
								  <input type="hidden" name="actual_weight[]" class="order_product_actual_weight" value="{{$orderproduct->actual_weight}}">
								  </td>
                                  <td>
								  <table>
								  <tbody>
								  <tr>
								  <td style="width:50px; height:50px;">
								  <input type="radio"  value="1" style="height:50px;width:50px;" class="product_packed packed_{{$orderproduct->product_id}}" data-id="{{$orderproduct->product_id}}" name="packed_{{$orderproduct->product_id}}" {{ ($orderproduct->is_packed==1) ? 'checked' : '' }}>
								  </td>
								  <td> Yes</td>
								  </tr>
								  <tr>
								  <td style="width:50px; height:50px;">
								  <input type="radio" class="product_packed packed_{{$orderproduct->product_id}}" style="height:50px;width:50px;" value="0" data-id="{{$orderproduct->product_id}}" name="packed_{{$orderproduct->product_id}}" {{ ($orderproduct->is_packed!=1) ? 'checked' : '' }}>
								  </td>
								  <td> No</td>
								  </tr>
								  </tbody>
								  </table>
								  </td>
								  <td><input type="checkbox" data-id="{{$orderproduct->product_id}}" class="product_available product_status_{{$orderproduct->product_id}}" {{ ($orderproduct->is_available==0) ? 'checked' : '' }} style="height:50px;width:50px;" name="product_status_{{$orderproduct->product_id}}" value="1">N/A</td>
								  <td><input type="checkbox" class="product_short product_short_{{$orderproduct->product_id}}" data-id="{{$orderproduct->product_id}}" {{ ($orderproduct->is_short==1) ? 'checked' : '' }} style="height:50px;width:50px;" name="product_short_{{$orderproduct->product_id}}" value="1">Short</td>
								  @php $sub_total+=$orderproduct->final_price; @endphp
                                  <td>
								  {{$orderproduct->final_price}}
								  </td>
                                  
                                </tr>
							@endforeach
							<tr style="text-align: -webkit-right;background:rgb(128, 128, 128, 1);">
							<td style="display: none;"></td>
                            <td style="display: none;"></td>
							<td style="display: none;"></td>
                            <td style="display: none;"></td>
							<td style="display: none;"></td>
                            <td style="display: none;"></td>
							<td style="display: none;"></td>
                            <td style="display: none;"></td>
							<td colspan="8"><b>Sub Total:</b></td>
							<td colspan="3" style="text-align: -webkit-left;color:rgb(0,0,0); "><input type="hidden" name="sub_total" id="sub_total" value="{{$sub_total}}"><b>{{$sub_total}}</b></td>
							
							</tr>
							
							
                           @endif
                            </tbody>
                        </table>
						<div class="box-footer pull-right">
							
							<a href="javascript:popupWindow('{{route('admin.orders.packing_slip.generate', $order->id)}}')" id="update-order-product-details" class="btn btn-info " target="_blank">Packing Slip</a>
							<a href="javascript:popupWindow('{{route('admin.orders.invoice.generate', $order->id)}}')" id="update-order-product-details" class="btn btn-info "  target="_blank">Invoice</a>
							</div>
                    </div>