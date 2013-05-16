<!--

Orderve
Copyright (c) 2013 Alex Reynolds

createqr.php

    - A page to customize and generate QR codes for the web admin
    - A private page

    TODO:
	- Make it possible to download code files
	- Make it possible to create QRs for a list of locations with numerical endings

-->

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Generate QR</title>

<style>

.qr {
	padding:20px;
}

fieldset {
	border:0px;
}

</style>

<!-- Standard QR code framework created by Kazuhiko Arase and licensed by MIT -->
<script type="text/javascript" src="qrcode.js"></script>
<!-- Used to generate the QR canvas drawing -->
<script type="text/javascript" src="qrcanvas.js"></script>

</head>

<body>

<h1> Generate QR Code </h1><br /><br />

<table><tr>

<td id="inputs">

<!-- Allows user to customize their QR code style -->
<form name="qrForm" id="qrForm">

<fieldset>
Input URL: <input type="text" name="url" id ="url" placeholder="http://yourdomain.com"><br />
</fieldset>

<br />

<fieldset>
<b>QR Error Correction Level</b><br />
<i>L is lowest level of error correction, H is highest</i><br />


<input type="radio" name="level" value="L" checked>L</input><br />
<input type="radio" name="level" value="M">M</input><br />
<input type="radio" name="level" value="Q">Q</input><br />
<input type="radio" name="level" value="H">H</input><br />
</fieldset>

<br />

<fieldset>
<b>QR Style</b><br />


<input type="radio" name="style" value="standard">Square (standard QR)</input><br />
<input type="radio" name="style" value="circle" checked>Circular</input><br />
<input type="radio" name="style" value="roundrect">Rounded Rectangle</input><br />

</fieldset>

<br />

<fieldset>

<b>QR Color</b><br />
Select the color for your QR code.<br /><br />

<input type="color" name="colordark"><br /><br />


<i>Note that the lighter the color, the less contrast your code will<br />
	have and the less readable it will be. Try to stick to darker colors.</i><br />

</fieldset>

<br /><br />

<!-- The name of the div where the code should be drawn -->
<input type="hidden" name="divname" value="qr_spot">
<input type="submit" class="submit" value="Generate">

</form>

</td>

<td id="qr" style="text-align:center; width:40%">

<!-- Where the QR code appears when generated -->
<div id="qr_spot" class="qr">
</div>

<br /><br />

<button type="button" id="savecode" onclick="saveCode()" disabled>Save QR</button>

</td>

</tr></table>

<br /><br /><br />


<!-- Include jQuery code -->
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>
<script type="text/javascript" src="jquery-1.9.1.min.js"></script>
<script>

// Run upon form submission
// Gets values of all inputs and uses them to generate QR
$('#qrForm').submit(function () {

	// Used to hold form info
	// Cleared upon form resubmission
	//var $inputs ={};
	//var values = {};

	var divName = $('input[name=divname]').val();
	var urlValue = $('input[name=url]').val();
	var levelValue = $('input[name=level]:checked').val();
	var styleValue = $('input[name=style]:checked').val();
	var colorValue = $('input[name=colordark]').val();

    // Clears the div so that there is only 1 code shown at a time
    document.getElementById(divName).innerHTML="";
    
    // Draws the QR in the div
	drawqr(4, divName, urlValue, levelValue, styleValue, colorValue);

	// Makes the button to save image active
	document.getElementById("savecode").disabled = false;

	return false;

});

// Saves the generated QR code as .png file on the user's system
function saveCode()
{
	alert("savecode");
	var qrcanvas = document.getElementById("qrcode");
	var cavasDataURL = qrcanvas.toDataURL("image/png");

	// Saves qr code image to local storage
	try {
		localStorage.setItem("qrcode", canvasDataURL);
	}
	catch(err) {
		var errorP = document.createElement("p");
		var errormsg = document.createTextNode("Storage failed: " + err);
		errorP.appendChild(errormsg);
		return errorChild;
	}
}

</script>


<a href="index.php?seat=THRONE">Back to main</a><br />

</body>


</html>