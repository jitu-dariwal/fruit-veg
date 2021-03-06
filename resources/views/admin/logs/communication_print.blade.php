


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html dir="LTR" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Communication Log Report</title>

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
			Communication Log Report date  : {{date('d M Y',strtotime(Request::query('printdate')))}}</h1>
			</td>
	
		</tr>
		
		 <tr>
			
		<td >
		
		<?php //echo "CK<pre>"; print_r(Auth::guard('employee')->user()->first_name); echo "</pre>CK"; exit;
 ?>
	          <h2 style="margin:0px;">Report Run By: {{ Auth::guard('employee')->user()->first_name.' '.Auth::guard('employee')->user()->last_name}} on : {{ date("d M Y")." @ ". date("H:i a") }}</h2>
			  
        <p style="float:right; margin: 0px; padding-top:10px;" class="print">
          <input onClick="window.print()" style="background:#333; font-size:12px; color:#fff; padding:3px;" type="button" value="Print Report">
        </p></td>
		</tr>
		
    <tr>
      <td width="100%" align="left" valign="top">
	  <table width="100%" cellpadding="0" cellspacing="0">
          <tr bgcolor="#367FA9" class="row" >
            <td align="left" valign="top" class="dataTableHeadingContent">Company Name</td>
            <td align="left" valign="top" class="dataTableHeadingContent">Company Contact</td>
            <td align="left" valign="top" class="dataTableHeadingContent">Admin Clerk</td>
            <td align="left" valign="top" class="dataTableHeadingContent" >Create Date</td>
          </tr>
		  
		  @if(!empty($CommunicationLogAdmins[0]))
		  @foreach($CommunicationLogAdmins as $CommunicationLogAdmin)
		  <tr>
			 <td align="left" valign="top" class="dataTableContent">@if(!empty($CommunicationLogAdmin->companyNameShow->company_name)){{ $CommunicationLogAdmin->companyNameShow->company_name }} @endif
			</td>
			
			
			<td align="left" valign="top" class="dataTableContent">{{ $CommunicationLogAdmin->CompanyContact }}</td>
			<td align="left" valign="top" class="dataTableContent">{{ $CommunicationLogAdmin->AdminClerk }}</td>
            <td align="left" valign="top" class="dataTableContent">{{ $CommunicationLogAdmin->created_at }}</td>
			
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
