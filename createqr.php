<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Generate QR</title>

<style>
.qr {
	padding:20px;
}

</style>

<!-- Standard QR code framework created by Kazuhiko Arase and licensed by MIT -->
<script type="text/javascript" src="qrcode.js"></script>
<!-- Used to generate the QR canvas drawing -->
<script type="text/javascript" src="qrcanvas.js"></script>

</head>

<body>

<h1> Generate QR Code </h1><br /><br />

<!-- Allows user to customize their QR code style -->
<form name="qrForm" id="qrForm">

<fieldset>
Input URL: <input type="text" name="url" id ="url" placeholder="http://yourdomain.com"><br />
</fieldset>

<br />

<b>QR Error Correction Level</b><br />
<i>L is lowest level of error correction, H is highest</i><br />

<fieldset>
<input type="radio" name="level" value="L" checked>L</input><br />
<input type="radio" name="level" value="M">M</input><br />
<input type="radio" name="level" value="Q">Q</input><br />
<input type="radio" name="level" value="H">H</input><br />
</fieldset>

<br />

<b>QR Style</b><br />

<fieldset>
<input type="radio" name="style" value="standard">Square (standard QR)</input><br />
<input type="radio" name="style" value="circle" checked>Circular</input><br />
<input type="radio" name="style" value="roundrect">Rounded Rectangle</input><br />

</fieldset>

<br />

<b>QR Color</b><br />
Select the color for your QR code.<br /><br />

<fieldset>
<input type="color" name="colordark"><br /><br />
</fieldset>

<i>Note that the lighter the color, the less contrast your code will<br />
	have and the less readable it will be. Try to stick to darker colors.</i><br />

<br /><br />

<!-- The name of the div where the code should be drawn -->
<input type="hidden" name="divname" value="qr_spot">
<input type="submit" class="submit" value="Generate">

</form>

<!-- Where the QR code appears when generated -->
<div id="qr_spot" class="qr">
</div>

<br /><br />

<button type="button" id="savecode" onclick="saveCode()" disabled>Save QR</button>

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
	var $inputs ={};
	var values = {};

	// Puts all of the inputs from the form into an array
	$inputs = $('#qrForm :input');


	// Makes an associative array of values from the input array
    $inputs.each(function() {
        values[this.name] = $(this).val();
    });

    // Clears the div so that there is only 1 code shown at a time
    clearDiv(values['divname']);
    
    // Draws the QR in the div
	drawqr(4, values['divname'], values['url'], values['level'], values['style'], values['colordark']);

	// Makes the button to save image active
	document.getElementById("savecode").disabled = false;


	// NOTE SHOULD ALSO CLEAR OUT DIV IF IT IS FULL ALREADY
	return false;
});

// Clears out the div where the QR code goes so only one
// is shown at a time
function clearDiv(id)
{
	document.getElementById(id).innerHTML="";
}

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