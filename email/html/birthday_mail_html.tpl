<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
  <head>
    <title>[{ $shop->oxshops__oxname->value }] [{ oxmultilang ident="SK_BIRTHDAY_MAIL_TITLE" }]</title>
    <meta http-equiv="Content-Type" content="text/html; charset=[{$charset}]">
  </head>
  <body bgcolor="#FFFFFF" link="#355222" alink="#355222" vlink="#355222" style="font-family: Verdana, Geneva, Arial, Helvetica, sans-serif; font-size: 10px;">
    <br>
    <img src="[{$imageurl}]logo_white.gif" border="0" hspace="0" vspace="0" alt="[{ $shop->oxshops__oxname->value }]" align="texttop"><br><br>
    <br>
    [{ oxcontent ident="birthday_mail_html" }]
	<br><br>
    [{ oxcontent ident="oxemailfooter"}]  
  </body>
</html>