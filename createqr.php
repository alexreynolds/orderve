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

<!-- Fits view to device screen width -->
<meta name="viewport" content="width=device-width,initial-scale=1">

<!-- Include jQuery code -->
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>
<script type="text/javascript" src="jquery-1.9.1.min.js"></script>

<!-- Imports fonts from Google Fonts API -->
<link href='http://fonts.googleapis.com/css?family=Maven+Pro:400,700' rel='stylesheet' type='text/css'>

<link rel="stylesheet" type="text/css" href="ordervestyle.css">

<!-- Standard QR code framework created by Kazuhiko Arase and licensed by MIT -->
<script type="text/javascript" src="qrcode.js"></script>
<!-- Used to generate the QR canvas drawing -->
<script type="text/javascript" src="qrcanvas.js"></script>

</head>

<body>

<!-- Nav bar for control panel-->
<nav id="navside">
    <span class="head" style="color:#ABE52C;">Control Panel</span>

    <br><br>

    <ul>
        <li><a href="pending.php" style="font-weight: 700; font-size: 1.2em;">Pending Orders</a></li>
        <li><a href="menuedit.php">Edit Menu</a></li>
        <li class="selected"><a href="createqr.php">Generate QR</a></li>
        <li><a href="analytics.php">Order Analytics</a></li>
        <li><a href="controls.php">Controls Main</a></li>

        <br><br>

        <li><a href="index.php">Back to Home</a></li>
        <li><a href="logout.php">Log out</a></li>
    </ul>
</nav>

<div id="#mainwrapper">

<div class="controlsmain" id="qrmain">

<h1> Generate QR Code </h1>

<table><tr>

<td id="inputs">

<!-- Allows user to customize their QR code style -->
<form name="qrForm" id="qrForm">

<fieldset>
<b>Input location name or number</b><br />
<span class="desc">Input will be appended to the home menu URL.<br />
					<i>(https://qrproject.cs.vassar.edu/?seat=)</i><br />
<input type="text" name="url" id ="url" size="20" placeholder="ex. 123, front" style="margin-left: 0;"><br />
</fieldset>

<fieldset>
<b>Error Correction Level</b><br />
<span class="desc">L is the lowest level of error correction, H is the highest.<br />
					For more on error correction, <a href="http://blog.qr4.nl/category/QR-Code-Error-Correction.aspx">see here</a>.</span><br />


<input type="radio" name="level" value="L" checked>L</input><br />
<input type="radio" name="level" value="M">M</input><br />
<input type="radio" name="level" value="Q">Q</input><br />
<input type="radio" name="level" value="H">H</input><br />
</fieldset>

<fieldset>
<b>Code Style</b><br />
<span class="desc">The style of the cells of your QR code.</span><br />
<input type="radio" name="style" value="circle" checked>Circular</input><br />
<input type="radio" name="style" value="standard">Square (standard QR)</input><br />
<input type="radio" name="style" value="roundrect">Rounded Rectangle</input><br />

</fieldset>

<fieldset>

<b>Code Color</b><br />
<span class="desc">The lighter the color, the less contrast your code will 
	have, and the less readable it will be.<br />
	Try to stick to darker colors.</span><br /><br />

<input type="color" name="colordark"><br />

</fieldset>

<br />

<!-- The name of the div where the code should be drawn -->
<input type="hidden" name="divname" value="qr_spot">
<input type="submit" class="submit" value="Generate">

</form>

</td>

<td id="qr">

<!-- Where the QR code appears when generated -->
<div id="qr_spot" class="qr">
	<span class="desc"><i>Your generated QR code will<br />
							appear here.</i></span>
</div>

<br /><br />

<button type="button" id="savecode" onclick="saveCode()" disabled>Save QR</button>

</td>

</tr></table>


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

	urlValue = "https://qrproject.cs.vassar.edu/?seat=" + urlValue;

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

</body>


</html>