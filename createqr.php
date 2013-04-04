<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Create QR</title>
</head>

<body>

<div id="gencontainer">

<!-- 	
	IFrame containing the resulting QR code image
	An IFrame is used so that the image is automatically generated
    without having to refresh the page
-->
<div id="qrresult">

<iframe name="qrcode-frame" frameborder="0" id="qrcode" src="gen.php" height ="350px" width="350px"></iframe>

</div>

<div id="gensettings">

<form target="qrcode-frame" action="gen.php" method="post">
<fieldset>
	<legend>QR Size:</legend>
	<input type="radio" name="size" value="200x200" checked />200x200 px<br />
    <input type="radio" name="size" value="250x250" />250x250 px<br />
    <input type="radio" name="size" value="300x300" />300x300 px<br />


</body>


</html>