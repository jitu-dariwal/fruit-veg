<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title>Product PDF</title>
        <style>
            body{font-family:Arial, Helvetica, sans-serif; margin:0px; padding:0px; font-size:13px;}
        </style>
    </head>
    <body>
        <table border="0" align="center" cellpadding="0" cellspacing="0" width="100%" style="border-collapse:collapse;">
            
            <tr>
                <td valign="middle" align="center" style="background-image:url('{{ asset('img/header-grad.gif') }}'); background-size:100% background-position: left top;  background-repeat-x: repeat; background-repeat-y: no-repeat;" ><img src="{{ asset('img/logo.gif') }}" alt="" style="width:321px; height:109px"   /></td>
            </tr>

            <tr>
                <td align="center" style="background-image:url('{{ asset('img/heading_bg.gif') }}'); background-size:100% background-position: left top;  background-repeat-x: repeat; background-repeat-y: no-repeat;color:#fff;"  ><strong>Fruit &amp; Veg Price List</strong></td>
            </tr>

            <tr>
                <td style="background-color:#F2F3EF;">
                    <table >
                        <tr>
                            <td> Produce by <a href="mailto:{{$current_uemail}}" style="color:#990000;">
                                    {{$current_uemail}} </a> @ {{date("F j, Y, g:i a")}}<br />
                                <br />For: <font color="#990000">{{$product_data['customer_name']}}</font>   
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>  

            <tr>
                <td>
                    <table width="100%"  border="0" align="center" cellspacing="2" cellpadding="5" style="border-collapse:collapse;">
                        <tr>
                            <td bgcolor="#53554F" align="left"><strong><font color="#FFFFFF">Product Name</font></strong></td>
                            <td bgcolor="#53554F" align="center"><strong><font color="#FFFFFF">Size</font></strong></td>
                            <td bgcolor="#53554F" align="center"><strong><font color="#FFFFFF">Price(£)</font></strong></td>
                        </tr>
                        @for ($i = 2; $i <= $product_data['total_products']; $i++)
                            @if (isset($product_data[$i.'_productName']) && !empty($product_data[$i.'_productName']))
                                <tr>
                                    <td align="left" bgcolor="#FFFFFF">
                                        {{$product_data[$i.'_productName']}}
                                        @if (isset($product_data[$i.'_productType']) && !empty($product_data[$i.'_productType']))
                                            ({{$product_data[$i.'_productType']}})
                                        @endif
                                    </td>
                                    <td align="center" bgcolor="#FFFFFF">{{$product_data[$i.'_productSize']}}</td>
                                    <td align="center" bgcolor="#FFFFFF">{{$product_data[$i.'_productPrice']}}</td>
                                </tr>
                             @endif
                        @endfor   
                        <tr>
                            <td colspan="3" style="background-color:#F2F3EF;">
                                <table cellspacing="0" cellpadding="5">
                                    <tr>
                                        <td>
                                            Established over 100 years our company will provide you with the very highest quality fresh produce, delivered with exceptional service.
                                            <p>To commence ordering  we require you to complete the account registration process. This can be online or by telephone.</p>
                                            <p>Should you require any further information, please contact us <strong>0808 141 2828</strong> or e-mail <a href="mailto:info@fruitandveg.co.uk" style="color:#333333;"><strong>info@fruitandveg.co.uk</strong></a></p>
                                            <p>Thank you for your interest in our products and service and we look forward to serving you.</p>
                                            <p><strong>The Fruit and Veg Team</strong></p>
                                            <p style="font-size:10px; color:#666666;"><em>
                                                    *Information contained in this document is considered private and confidential<br />
                                                    *All prices are subject to change under market conditions</em>
                                            </p>
                                        </td>
                                    </tr>
                                </table> 
                            </td>
                        </tr>  
                    </table>
                </td>
            </tr>
            
            
        </table>
    </body>
</html>