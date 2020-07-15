<html dir="LTR" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Fruit and veg - ORDER #{{$order->id}}</title>
        <base href="{{url('/')}}">
        <style>
			/* links */
			a:link { font-family: Verdana, Arial, sans-serif; font-size: 10px; color: #FF9900; font-weight: normal; }
			a:hover { font-family: Verdana, Arial, sans-serif; font-size: 12px; color: #0000ff; font-weight: bold; }

			/* page */
			body { background-color: #ffffff; color: #000000; margin: 0px; font-family:Arial, Helvetica, sans-serif;font-size:13px;padding:0px;}
			.pageHeading { font-family: Verdana, Arial, sans-serif; font-size: 14px; color: #727272; font-weight: bold; }

			/* data table */
			.dataTableHeadingRow { background-color: #C9C9C9; }
			.dataTableHeadingContent { font-family: Verdana, Arial, sans-serif; font-size: 10px; color: #000000; font-weight: bold; }
			.dataTableRow { background-color: #F0F1F1; }
			.dataTableRowSelected { background-color: #DEE4E8; }
			.dataTableRowOver { background-color: #FFFFFF; }
			.dataTableContent { font-family: Verdana, Arial, sans-serif; font-size: 10px; color: #000000; }

			/* attributes */
			.attributes-odd { background-color: #f4f7fd; }
			.attributes-even { background-color: #ffffff; }

			/* miscellaneous */
			.specialPrice { color: #ff0000; }
			.oldPrice { text-decoration: line-through; }
			.fieldRequired { font-family: Verdana, Arial, sans-serif; font-size: 10px; color: #ff0000; }
			.smallText { font-family: Verdana, Arial, sans-serif; font-size: 10px; }
			.main { font-family: Verdana, Arial, sans-serif; font-size: 12px; }
			.titleHeading {font-family: Verdana, Arial, sans-serif; font-size: 18px; color: #727272; font-weight: bold;
			}
			
			
			@media print {
			  .section-not-print{
				visibility: hidden;
			  }
			}
		</style>
    </head>
    <body>
        <!-- body_text //-->
        <table width="100%" border="0" align="center" cellpadding="2" cellspacing="0">
            <tbody>
				@if($type == 'print')
                <tr class="section-not-print">
                    <td align="center" class="main">
                        <table align="center" width="100%" border="0" cellspacing="0" cellpadding="5">
                            <tbody>
                                <tr>
                                    <td valign="top" align="left" class="main">
                                        <a href="javascript:;" onclick="javascript:window.print()" onmouseout="document.imprim.src=&quot;images/printimage.gif&quot;" onmouseover="document.imprim.src=&quot;images/printimage_over.gif&quot;"><img src="images/printimage.gif" width="43" height="28" align="absbottom" border="0" name="imprim">Print</a>
                                    </td>
                                    <td align="right" valign="bottom" class="main">
                                        <p align="right" class="main"><a href="javascript:window.close();"><img src="images/close_window.jpg" border="0"></a></p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
				@endif
                <tr align="left">
                    <td class="titleHeading">&nbsp;</td>
                </tr>
                <tr>
                    <td>
                        <table border="0" align="center" width="100%" cellspacing="0" cellpadding="0">
                            <tbody>
                                <tr>
                                    <td>
                                        <table border="0" align="center" width="75%" cellspacing="0" cellpadding="0">
                                            <tbody>
                                                <tr>
                                                    <td class="pageHeading">
														{!! __('content.orders.order_print_shop_add') !!}
                                                    </td>
                                                    <td class="pageHeading" align="right">
														<img src="{{ asset('images/fruit-for-the-office.svg') }}" border="0" alt="Fruit For The Office" title=" Fruit & Veg ">
													</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2" align="center" class="titleHeading"><b>ORDER #{{$order->id}}</b></td>
                                                </tr>
                                                <tr align="left">
                                                    <td colspan="2" class="titleHeading">&nbsp;</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td align="left" class="main">
                        <table width="100%" border="0" cellspacing="0" cellpadding="2">
                            <tbody>
                                <tr>
                                    <td class="main"><b>Payment Method:</b> {{$order->payment_method}}</td>
                                </tr>
                                <tr>
                                    <td class="main"></td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td class="main">
						<b>Date Purchased:</b> {{$order->created_at}}
						&nbsp;&nbsp;
						<b>Delivery Date</b> {{$order->orderDetail->shipdate}}
					</td>
                </tr>
                <tr>
                    <td align="center">
                        <table align="center" width="100%" border="0" cellspacing="0" cellpadding="2">
                            <tbody>
                                <tr>
                                    <td width="50%" align="center" valign="top">
                                        <table align="center" width="100%" border="0" cellspacing="0" cellpadding="1" bgcolor="#000000">
                                            <tbody>
                                                <tr>
                                                    <td align="center" valign="top">
													<table align="center" width="100%" border="0" cellspacing="0" cellpadding="2">
													<tbody>
														<tr class="dataTableHeadingRow">
															<td class="dataTableHeadingContent"><b>SOLD TO:</b></td>
														</tr>
														<tr class="dataTableRow">
															<td class="dataTableContent">
																@if(!empty($order->orderDetail) && $userAdd = $order->orderDetail)
																
																@if(!empty($userAdd->first_name))
																&nbsp;{{$userAdd->first_name.' '.$userAdd->last_name}}<br/>
																@endif
																@if(!empty($userAdd->company_name))
																&nbsp;{{$userAdd->company_name}}<br/>
																@endif
																@if(!empty($userAdd->street_address))
																&nbsp;{{$userAdd->street_address}},<br/>
																@endif
																@if(!empty($userAdd->address_line_2))
																&nbsp;{{$userAdd->address_line_2}},<br/>
																@endif
																
																@if(!empty($userAdd->city) || !empty($userAdd->country_state))
																&nbsp;{{$userAdd->city.', '.$userAdd->country_state}}<br/>
																@endif
																
																@if(!empty($userAdd->post_code))
																&nbsp;{{$userAdd->post_code}},<br/>
																@endif
																
																@if(!empty($userAdd->country))
																&nbsp;{{$userAdd->country}},<br/>
																@endif
																
																@if(!empty($userAdd->tel_num))
																&nbsp;{{$userAdd->tel_num}},<br/>
																@endif
																
																@if(!empty($userAdd->email))
																&nbsp;{{$userAdd->email}},<br/>
																@endif
																
																@else
																	Not available.
																@endif
																
															</td>
														</tr>
													</tbody>
													</table>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                    <td width="50%" align="center" valign="top">
                                        <table align="center" width="100%" border="0" cellspacing="0" cellpadding="1" bgcolor="#000000">
                                            <tbody>
                                                <tr>
                                                    <td align="center" valign="top">
													<table align="center" width="100%" border="0" cellspacing="0" cellpadding="2">
													<tbody>
														<tr class="dataTableHeadingRow">
															<td class="dataTableHeadingContent"><b>SHIP TO:</b></td>
														</tr>
														<tr class="dataTableRow">
															<td class="dataTableContent">
															
															@if(!empty($order->orderDetail) && $shipAdd = $order->orderDetail)
															
															@if(!empty($shipAdd->shipping_add_name))
															&nbsp;{{$shipAdd->shipping_add_name}}<br/>
															@endif
															@if(!empty($shipAdd->shipping_add_company))
															&nbsp;{{$shipAdd->shipping_add_company}}<br/>
															@endif
															@if(!empty($shipAdd->shipping_street_address))
															&nbsp;{{$shipAdd->shipping_street_address}},<br/>
															@endif
															@if(!empty($shipAdd->shipping_address_line2))
															&nbsp;{{$shipAdd->shipping_address_line2}},<br/>
															@endif
															
															@if(!empty($shipAdd->shipping_city) || !empty($shipAdd->shipping_state))
															&nbsp;{{$shipAdd->shipping_city.', '.$shipAdd->shipping_state}}<br/>
															@endif
															
															@if(!empty($shipAdd->shipping_post_code))
															&nbsp;{{$shipAdd->shipping_post_code}},<br/>
															@endif
															
															@if(!empty($shipAdd->shipping_country))
															&nbsp;{{$shipAdd->shipping_country}},<br/>
															@endif
															
															@if(!empty($shipAdd->shipping_tel_num))
															&nbsp;{{$shipAdd->shipping_tel_num}},<br/>
															@endif
															
															@if(!empty($shipAdd->shipping_email))
															&nbsp;{{$shipAdd->shipping_email}},<br/>
															@endif
															
															@else
																Not available.
															@endif
															
															</td>
														</tr>
													</tbody>
													</table>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td>
					<table border="0" width="100%" cellspacing="0" cellpadding="1" bgcolor="#000000">
						<tbody>
							<tr>
								<td>
								<table border="0" width="100%" cellspacing="0" cellpadding="2">
								<tbody>
								<tr class="dataTableHeadingRow">
									<td class="dataTableHeadingContent" colspan="2">Products</td>
									<td class="dataTableHeadingContent">Model</td>
									<td class="dataTableHeadingContent" align="right">Tax</td>
									<td class="dataTableHeadingContent" align="right">Price (ex)</td>
									<td class="dataTableHeadingContent" align="right">Total (ex)</td>
									<td class="dataTableHeadingContent" align="right">Total (inc)</td>
								</tr>
								@if(!$order->orderproducts->isEmpty() && $order->orderproducts->count() > 0)
									@foreach($order->orderproducts as $product)
									<tr class="dataTableRow">
										<td class="dataTableContent" valign="top" align="right">
											{{$product->quantity}} &nbsp;x
										</td>
										<td class="dataTableContent" valign="top">
											{{$product->product_name}}<br>
										</td>
										<td class="dataTableContent" valign="top">
											{{$product->products_model}}
										</td>
										<td class="dataTableContent" align="right" valign="top">
											{{$order->tax}}%
										</td>
										<td class="dataTableContent" align="right" valign="top">
											<b>£{{$product->product_price}}</b>
										</td>
										<td class="dataTableContent" align="right" valign="top">
											<b>£{{$product->final_price}}</b>
										</td>
										<td class="dataTableContent" align="right" valign="top"><b>£{{$product->final_price}}</b></td>
									</tr>
									@endforeach
								@else
									<tr>
										<td>No products found</td>
									</tr>
								@endif
								</tbody>
								</table>
								</td>
							</tr>
						</tbody>
					</table>
                    </td>
                </tr>
                <tr>
                    <td align="right" colspan="7">
						<table border="0" width="100%" cellspacing="0" cellpadding="2">
							<tbody>
								<tr>
									<td width="80%" align="right" class="smallText">Sub-Total:</td>
									<td width="20%" align="right" class="smallText">
										£{{$order->sub_total}}
									</td>
								</tr>
								<tr>
									<td align="right" class="smallText">
										Discount:
									</td>
									<td align="right" class="smallText">
										£{{$order->customer_discount}}
									</td>
								</tr>
								<tr>
									<td align="right" class="smallText">
										Delivery:
									</td>
									<td align="right" class="smallText">
										£{{$order->shipping_charges}}
									</td>
								</tr>
								<tr>
									<td align="right" class="smallText">Total:</td>
									<td align="right" class="smallText">
										<b>£{{$order->total}}</b>
									</td>
								</tr>
							</tbody>
						</table>
                    </td>
                </tr>
            </tbody>
        </table>
        <!-- body_text_eof //-->
    </body>
</html>