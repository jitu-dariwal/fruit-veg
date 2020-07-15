<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html dir="LTR" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Fruit And Veg</title>
<style type="text/css"><!--
a { color:#080381; text-decoration:none; }
a:hover { color:#aabbdd; text-decoration:underline; }
a.text:link, a.text:visited { color: #000000; text-decoration: none; }
a:text:hover { color: #000000; text-decoration: underline; }



.smallText { font-family: Verdana, Arial, sans-serif; font-size: 10px; }
/* info box */
.DATAHeading { font-family: Verdana, Arial, sans-serif; font-size: 11px; color: #ffffff; background-color: #B3BAC5; }
.DATAContent { font-family: Verdana; font-size: 10pt; border: 1px outset #9B9B9B; 
               padding-left: 4; padding-right: 4; padding-top: 1; 
               padding-bottom: 1; background-color: #FFFFFF }
//--></style>

<body bgcolor="#DEE4E8">
<p class="smallText" align="right"><a href="javascript:window.close()"><u>Close Window</u> [x]</a></p>
<form name="calc" action="" method="post"><!--<table border="0" cellpadding="2" cellspacing="2" >
<tr><td valign="top" align="left">&nbsp;</td><td valign="top" align="left">&nbsp;</td></tr>
<tr><td valign="top" align="left" class="DATAHeading">Actual Weight : </td><td valign="top" align="left">
<input type="text" name="actual_weight" ></td></tr>
<tr><td valign="top" align="left">&nbsp;</td><td valign="top" align="left">&nbsp;</td></tr>
<tr><td valign="top" align="center" colspan="2"><input type="submit" name="update" value="update"></td></tr>
<tr><td valign="top" align="left">&nbsp;</td><td valign="top" align="left">&nbsp;</td></tr>
</table>-->

@csrf
<table width="618">
<tr><td colspan="12"><input name="wtcalc" type="text" style="width:600px; height:50px; font-size:36px;"></td></tr>
<tr><td width="120"><input type="button" onClick="document.calc.wtcalc.value+='7'" value="7" name="7"  title="7" style="width:120px; height:60px; font-size:48px;"></td><td width="120"><input type="button" onClick="document.calc.wtcalc.value+='8'" value="8" name="8" title="8" style="width:120px;height:60px; font-size:48px;"></td><td width="120"><input type="button" onClick="document.calc.wtcalc.value+='9'" value="9" name="9" title="9" style="width:120px;height:60px; font-size:48px;"></td>
<td width="225"><input type="button" onClick="document.calc.wtcalc.value=''"  value="C" name="C" title="C" style="width:140px; color:#FF0000; font-weight:bold; height:60px; font-size:48px;"></td>
</tr>
<tr><td width="120"><input type="button" onClick="document.calc.wtcalc.value+='4'" value="4" name="4" title="4" style="width:120px;height:60px; font-size:48px;" ></td><td width="120"><input type="button" onClick="document.calc.wtcalc.value+='5'" value="5" name="5" title="5" style="width:120px;height:60px; font-size:48px;"></td><td width="120"><input type="button" onClick="document.calc.wtcalc.value+='6'" value="6" name="6" title="6" style="width:120px;height:60px; font-size:48px;" ></td><td width="225" rowspan="4" ><input type="submit"  name="enter" value="Enter" title="Enter" style="width:140px; height:290px; color:#FF0000; font-weight:bold; font-size:36px;" /></td></tr>

<tr><td width="120"><input type="button" onClick="document.calc.wtcalc.value+='1'" value="1" name="1" title="1" style="width:120px;height:60px; font-size:48px;"></td><td width="120"><input type="button" onClick="document.calc.wtcalc.value+='2'" value="2" name="2" title="2" style="width:120px;height:60px; font-size:48px;"></td><td width="120"><input type="button" onClick="document.calc.wtcalc.value+='3'" value="3" name="3" title="3" style="width:120px;height:60px; font-size:48px;"></td></tr>
<tr><td width="120"><input type="button" onClick="document.calc.wtcalc.value+='0'" value="0" name="0" title="0" style="width:120px;height:60px; font-size:48px;"></td><td width="120" ><input type="button" onClick="document.calc.wtcalc.value+='.'" value="&bull;" name="." title="." style="width:120px; height:60px;font-size:10px; font-weight:bolder; font-family:verdana, Helvetica, sans-serif;"></td><td width="120" rowspan="2"><input type="button" onClick="document.calc.wtcalc.value=eval(calc.wtcalc.value);" value="=" style="width:120px; height:140px;font-size:48px;"></td></tr>
<tr><td width="120"><INPUT TYPE="button" NAME="plus"  VALUE="  +  " OnClick="calc.wtcalc.value += ' + '" style="width:120px; height:60px;font-size:48px; "></td><td width="120"><INPUT TYPE="button" NAME="minus" VALUE="  -  " OnClick="calc.wtcalc.value += ' - '" style="width:120px; height:60px;font-size:48px; "></td>
  </tr>
</table>
</form>
</body>
@if(session('sucess_status'))
<script type="text/javascript"> 
window.opener.location.href="{{route('admin.packer.order.show', [$order_id])}}"; 
javascript:window.close() 
</script>
@endif
</html>