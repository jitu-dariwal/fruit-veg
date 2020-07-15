


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html dir="LTR" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>ANOMOLIES report</title>

<!--[if IE]>
<link rel="stylesheet" type="text/css" href="includes/stylesheet-ie.css">
<![endif]-->
<style type="text/css">
td{padding:7px; font-size:12px;}
input{border:none; background:none}
			/*demo page css*/
			body{
}
			.demoHeaders { margin-top: 2em; }
			#dialog_link {padding: .4em 1em .4em 20px;text-decoration: none;position: relative;}
			#dialog_link span.ui-icon {margin: 0 5px 0 0;position: absolute;left: .2em;top: 50%;margin-top: -8px;}
			ul#icons {margin: 0; padding: 0;}
			ul#icons li {margin: 2px; position: relative; padding: 4px 0; cursor: pointer; float: left;  list-style: none;}
			ul#icons span.ui-icon {float: left; margin: 0 4px;}
		.price {
	float: right;
	width: 50px;
}
        .tailor_item {
	float: left;
	width: 500px;
}
   

   .dataTableHeadingContent {
    font-family: Verdana,Arial,sans-serif;
    font-size: 10px;
    color: rgb(255, 255, 255);
    font-weight: bold;
}
	td {
    padding: 7px;
    font-size: 12px;
}	

body, td, th {
    font-family: Verdana,Arial,Helvetica,sans-serif;
    font-size: 11px;
    color: rgb(0, 0, 0);
    line-height: 1.5em;
}

td[Attributes Style] {
    width: 100%;
    text-align: -webkit-left;
    vertical-align: top;
}

        </style>
<style type="text/css" media="print">
		.print{display:none;}
		table{border:none}
		td{border:1px solid #ccc; background:#ccc;}
		.row{background:#333;}
		</style>
</head>
<body>
<!-- header //-->
<!-- header_eof //-->
<!-- body //-->
<div id="wrap" style="width:100%; margin:auto; text-align:center;">
  <table width="700" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
			<td >
			<h1 style="margin:0px;">
			ANOMOLIES  report on  : {{date('d M Y',strtotime(Request::query('printdate')))}}</h1>
			</td>
	
		</tr>
		
		 <tr>
			
		<td >
		
		<?php //echo "CK<pre>"; print_r($issuelogAdmins); echo "</pre>CK"; exit;
 ?>
	        
			  
        <p style="float:right; margin: 0px; padding-top:10px;" class="print">
          <input onClick="window.print()" style="background:#333; font-size:12px; color:#fff; padding:3px;" type="button" value="Print Report">
        </p></td>
		</tr>
		
    <tr>
      <td width="100%" align="left" valign="top">
	  <table width="100%" cellpadding="0" cellspacing="0">
          <tr bgcolor="#367FA9" class="row" >
            <td align="left" valign="top" class="dataTableHeadingContent">S No	</td>
            <td align="left" valign="top" class="dataTableHeadingContent">Anomiles Points	</td>
            <td align="left" valign="top" class="dataTableHeadingContent">Anomiles Points Reply	</td>
           
            <td align="left" valign="top" class="dataTableHeadingContent">Add Date	</td>
           
          </tr>
		  
		  @if(!empty($Anomolies[0]))
		 
		
		@php
						$i = 0;
						@endphp
                        @foreach ($Anomolies as $Anomolie)
						@php
						$i++;
						@endphp
                            <tr>
							
                                <td>{{ $i }}</td>
                                
                                <td align="left" valign="top" class="dataTableContent">@if(!empty($Anomolie->anomolies_points))
								{!! $Anomolie->anomolies_points !!}
								@else
								N/A	
								@endif
								</td>
                                <td align="left" valign="top" class="dataTableContent">
								@if(!empty($Anomolie->anomolies_points_reply))
								{!! $Anomolie->anomolies_points_reply !!}
								@else
								N/A	
								@endif
								 </td>
                                <td align="left" valign="top" class="dataTableContent">
								@if(!empty($Anomolie->anomolies_date))
								{!! date('d-m-Y',strtotime($Anomolie->anomolies_date))  !!}
								@else
								N/A	
								@endif
								</td>
                              
                            </tr>
							
                        @endforeach
						
		  @else
			<tr>
			<td class="dataTableContent"  colspan="10" style="color:red;"> <center> No Record found </center></td>
			</tr>
		  @endif
		          </table></td>
    </tr>
  </table>
  <!-- Tabs -->
</div>
<!-- body_eof //-->
<!-- footer //-->
<!-- footer_eof //-->
<br>
</body>
</html>
