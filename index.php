<!-- HOME PAGE
	WRITTEN BY ALEX REYNOLDS, 2013
-->

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php session_start(); ?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<!-- Imports fonts from Google Fonts API -->
<link href='http://fonts.googleapis.com/css?family=Economica|Merriweather+Sans:400,300|Maven+Pro:400,700' rel='stylesheet' type='text/css'>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<!-- Fits view to device screen width -->
<meta name="viewport" content="width=device-width,initial-scale=1">

<title>Orderve</title>

<link rel="stylesheet" type="text/css" href="ordervestyle.css">

</head>

<body onload="window.addEventListener('orientationchange', handleOrientation, false);">

<!-- Creates the relevant tables in the database if they have not already been created -->
<?php include 'tablecreation.php' ?>


<script>

// Dictates what to do when device orientation changes
// Note that on some tablets, landscape mode is considered standard orientation,
// 	so must take that into consideration for orientation calculations
function handleOrientation() {

// Find standard screen orientation and adjust orientation accordingly
/*int normOrientation = ((WindowManager) getSystemService(Context.WINDOW_SERVICE)).getDefaultDisplay().getRotation();

if (naturalOrientation == Surface.ROTATION_0)
{
	// Do nothing
}
else if (naturalOrientation == Surface.ROTATION_90)
{
    orientation += 90;
}
else if (naturalOrientation == Surface.ROTATION_180)
{
    orientation += 180;
}
else if (naturalOrientation == Surface.ROTATION_270)
{
    orientation += 270;
}
*/
// Simpler attempt to fix orientation
if (orientation == 0 && window.width > window.height) { orientation += 90; }

// Keeps the orientation angle [0,360]
if (orientation > 360)
{
    orientation -= 360;
}

// Actions related to orientation
if (orientation == 0) {
	// Portrait
  alert('portrait');
}
else if (orientation == 90) {
	// Landscape
  alert('landscape');
}
else if (orientation == -90) {
  // Landscape
  alert('landscape');
}
else if (orientation == 180) {
  // Upside down portrait
}
else {
	// Do nothing, whack angles
}
}

// Checks to see if there is location information already
// If not, prompt the user to enter it and then continue on

var url = window.location.href;

// If there is no location information in the url already
if (url.indexOf("?seat=") == -1) {

	// Prompts the user to enter their location
	var loc = prompt("Please enter your location.\nex. Room 123");

	// Redirects to new url with parameter
	window.location.search += ("?seat=" + loc);
}

// Tests to see if a mobile device, and which browser is being used
var isMobile = {
    Android: function() {
        return navigator.userAgent.match(/Android/i);
    },
    BlackBerry: function() {
        return navigator.userAgent.match(/BlackBerry/i);
    },
    iOS: function() {
        return navigator.userAgent.match(/iPhone|iPad|iPod/i);
    },
    Opera: function() {
        return navigator.userAgent.match(/Opera Mini/i);
    },
    Windows: function() {
        return navigator.userAgent.match(/IEMobile/i);
    },
    any: function() {
        return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
    }
};

/* TESTING IT WORKS COOL

// SHOULD HAVE SO IF NOT MOBILE DO SOMETHING 

if ( isMobile.any() ) { alert('MOBILE'); }
if ( isMobile.Android() ) { alert('ANDROID'); }
if ( isMobile.Opera() ) { alert('OPERA'); }
*/

</script>



<!-- Nav bar with submit order button -->
<nav class="top">
	<table style="width:100%; text-align:center; vertical-align:center"><tr>
		<td style="width:100px"></td>
		<td style="padding:0"><span class="head">Orderve</span></td>
		<td style="width:100px;"><a form="orderform" class="checkout" onclick="orderform.submit();">Checkout</a></td>
	</tr></table>
</nav>

<!-- Page content -->
<div id="main">

<!-- Automatically generate menu table of food options -->
<?php

	$servername = "localhost";
	$username = "user";
	$password = "wachtwoord";
	$db = "my_db";

	$con = mysql_connect($servername, $username, $password);

	// If the server cannot connect, error.
	if (!$con)
  	{
  	die('Could not connect: ' . mysql_error());
  	}
	
	mysql_select_db($db, $con);

	// Begin session if one has not started already
	if(session_id() == '') {
		session_start();
	}

	// Sets session seat variable as user's current location
	$_SESSION['seat'] = $_GET['seat'];
	

	// Begins menu table (white rounded rectangle background)
	echo "<table align=\"center\" class=\"content\" id=\"menu\"><tr>";

	// Form to submit order
	echo "<form method=\"post\" action=\"orderinfo.php\" id=\"orderform\">";

	// DRINKS section
	echo "<td align=\"left\"><div class=\"itemb\">Drinks</div></td>
			<td align=\"right\">
			<img class=\"expand\" id=\"drinkbtnexpand\" src=\"buttonplus2.png\" onClick=\"toggleDisplay('drinks', 'drinkbtnexpand')\">
			<img class=\"expand\" id=\"drinkbtncontract\" src=\"buttonminus2.png\" onClick=\"toggleDisplay('drinks', 'drinkbtncontract')\"  style=\"display:none;\">
			</td></tr>";
			
	// Begins drinks table
	echo "<tr id=\"drinks\" style=\"display:none;\"><td><table>";

	// Selects all drinks from Foods table
	$result = mysql_query("SELECT * FROM Foods WHERE Category = 'drink'");

	// Generates drinks options table
	optionsTableGen($result);


	/* === SECTION DIVIDER BETWEEN DRINKS AND MAIN COURSES === */


	// MAIN COURSES section
	
	echo "<td align=\"left\"><div class=\"itemb\">Main Courses</div></td><td align=\"right\">
			<img class=\"expand\" id=\"mainbtnexpand\" src=\"buttonplus2.png\" onClick=\"toggleDisplay('maincourses', 'mainbtnexpand')\">
			<img class=\"expand\" id=\"mainbtncontract\" src=\"buttonminus2.png\" onClick=\"toggleDisplay('maincourses', 'mainbtncontract')\" style=\"display:none;\"></td></tr>";
	

	// Begins Main courses table (contains all selections for category)
	echo "<tr id=\"maincourses\" style=\"display:none;\"><td><table>";

	// Selects main course items from Foods table
	$result = mysql_query("SELECT * FROM Foods WHERE Category = 'main'");

	// Generates main course options table
	optionsTableGen($result);

	// Button to submit order
	// *** HAS BEEN MOVED TO THE TOP NAV BAR ***
	// echo "<tr><td align=\"right\"><input type=\"submit\" value=\"Place Order\"></td></tr>";

	// Finish form
	echo "</form><br /><br />";


	// Close off menu table
	echo "</table>";



	// Function to generate the options tables
	// INPUT: res, the mysql query result for the respective category
	function optionsTableGen($res) {

		while ($row = mysql_fetch_array($res))
		{
			// 3 items per row shown, start new row if current is filled
			//if ($i>3) { echo "</tr><tr>"; $i=1; }
			
			// Begin table cell
			echo "<tr><td>";

			// Begin table with item info. LHS is item image, RHS is info
			echo "<table><tr>";
				// LHS
				echo "<td>";
				// Draws image
				echo "<img src=\"" . $row['ImageURL'] . "\" class=\"round\"><br/><br/>";
				echo "</td><td class=\"iteminfo\">";
				// RHS
				// Name of food item
				echo "<div class=\"itemb\">" . $row['FoodName'] . "</div>";
				// Price of food item
				echo "$" . number_format($row['FoodPrice'],2) . "<br />";
				// Quantity of item
				echo "Quantity: <input type=\"number\" id=\"q\" value=\"0\" min=\"0\" max=\"20\" step=\"1\"
				name=\"" . $row['foodID'] . "\"><br />";
				echo "</td>";
			// End item info table
			echo "</tr></table>";
			// Hidden food name value
			echo "<input type=\"hidden\" name=\"foodtype\" value=\"" . $row['FoodName'] . "\" />";
			// Hidden seat location information
			echo "<input type=\"hidden\" name=\"seat\" value=\"" . $_SESSION['seat'] . "\"/>";
			// Hidden price information
			echo "<input type=\"hidden\" name=\"price\" value=\"" . $row['FoodPrice'] . "\"/>";
			// End div
			//echo "</div>";
			// End table cell and row
			echo "</td><tr>";
		}
		
		// Close off table
		echo "</tr></table></td></tr><br /></tr>";
	}

	?>
    
    
    <br /><br />
    
<nav class="bottom">
	<footer>Copyright © 2013 Alex Reynolds</footer>
</nav>

</div>
    
    
    <script>

	// Toggles the display of an element (for dropdown menus)
	function toggleDisplay(id,btn)
	{
		// Toggles display value (hidden vs. block)
		var item = document.getElementById(id);
		if (item.style.display != "none")
			item.style.display = "none";
		else
			item.style.display = "block";

		// Toggles section expansion button image (+ vs -)
		var btnimg = document.getElementById(btn);
		btnimg.style.display = "none";
		
		// FIND A WAY TO FIND THE LAST 9 CHARS IN STRING FOR TESTING

		if (btn == "drinkbtnexpand")
			document.getElementById('drinkbtncontract').style.display = "block";
		else if (btn == "drinkbtncontract")
			document.getElementById('drinkbtnexpand').style.display = "block";
		else if (btn == "mainbtnexpand")
			document.getElementById('mainbtncontract').style.display = "block";
		else if (btn ==	"mainbtncontract")
			document.getElementById('mainbtnexpand').style.display = "block";

		return false;

	}
	
	</script>

</body>
</html>
