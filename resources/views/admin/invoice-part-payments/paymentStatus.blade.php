<style>
.head-div {
	border-color: #daf1d8!important;
	color: #000!important;
    border-style: solid;
    margin-left: 20%;
    margin-right: 20%;
    padding: 50px;
}
</style>
							<div class="head-div" style="background-color:{{($isPaymentSuccess==1)?'#daf1d8':'#dc5a5a'}}">
							@if($isPaymentSuccess==1)
							<table width="100%" border="0">
							<tr>
							<td width="16%" align="left" valign="top">&nbsp;</td>
							<td width="72%" align="left" valign="top" style="font-family:Verdana, Arial, Helvetica, sans-serif12; font-size:12px;">Thanks,<br> Payment is received sucessfully.<br><a onclick="window.top.close();" href="javascript:vod(0);">Close Page</a></td>
							<td width="12%" align="left" valign="top">&nbsp;</td>
							</tr>
							<tr>
							<td  align="right" valign="top"></td>
							</tr>
							</table>
							@endif
							@if($isPaymentSuccess==0)
                               <table width="100%" border="0">
								<tr>   
								<td width="100%" align="left" valign="top" style="font-family:Verdana, Arial, Helvetica, sans-serif12; font-size:12px;"><table width="100%">
								<tr>
								<td><table border="0" width="100%" cellspacing="0" cellpadding="2">
								<tr>
								<td class="main"><b>Sorry</b></td>
								</tr>
								</table></td>
								</tr>
								<tr>
								<td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBoxNotice">
								<tr class="infoBoxNoticeContents">
								<td><table border="0" width="100%" cellspacing="0" cellpadding="2">
								  <tr>
									<td>&nbsp;</td>
									<td class="main" width="100%" valign="top">Payment error Occured, Please check again <br><a onclick="window.top.close();" href="javascript:vod(0);">Close Page</a></td>
									<td>&nbsp;</td>
								  </tr>
								</table></td>
								</tr>
								</table></td>
								</tr>
								<tr>
								<td>&nbsp;</td>
								</tr>
								</table>
								</td>
								<td width="12%" align="left" valign="top">&nbsp;</td>
								</tr>
							
								</table>
								@endif
			
</div>