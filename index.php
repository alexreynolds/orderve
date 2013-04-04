<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Orderve</title>
</head>


<style>

body {
	font-family:Tahoma, Geneva, sans-serif;
	color:#000;
	font-size:.8em;
	background-color:#003366;
	padding-top:20px;
	background:url("back.jpg") no-repeat;
	background-size:cover;
}

/* logo header */
.logo {
	width:150px;
	height:150px;
}

.round {
	border-radius:15px;
	width:100px;
	height:100px;
}

/* table containing menu */
.menu {
	border-radius:25px;
	background-color:#FFF;
	padding:15px;
	box-shadow: 0px 5px 10px #444;
	width:350px;
}

/* table cell for food item info */
.iteminfo {
	text-align:left;
	vertical-align:center;
}

/* Bolded item names*/
.itemb {
	color:#37CEA0;
	font-weight:600;
}

td {
	padding:5px;
}

/* +/- button style */
.expand {
	width:20px;
	height:20px;
}

/* check out button
	NOT WORKING */
.checkout {
	background:#FFF url("buttoncheck.png") no-repeat;
	border:none;
	vertical-align:center;
	padding:10px;
	width:65px;
	height:40px;
}

</style>


<script type="text/javascript" charset="utf-8" src="cordova-2.2.0.js"></script>
<script type="text/javascript" charset="utf-8">

// Cordova is used for obtaining mobile device information
// Gets the device name and platform and UUID (universally unique identifer)
var devicename;
var deviceplatform;
var deviceuuid;
var deviceversion;
var devicecordova;

function onDeviceReady()
{	
	devicename = device.name;
	deviceplatform = device.platform;
	deviceuuid = device.uuid;
	deviceversion = device.version;
	devicecordova = device.cordova;
	
	// String to display info
	var devinfostring = "Name: " + devicename + "\nPlatform: " + deviceplatform + "\nUUID: "
						+ deviceuuid + "\nVersion: " + deviceversion + "\nCordova Version: " + devicecordova;
}

// Wait for Cordova to load
document.addEventListener("deviceready", onDeviceReady, false);

// Cordova is ready
onDeviceReady(); 

</script>

<!-- Creates the relevant tables in the database if they have not already been created -->
<? php include 'tablecreation.php' ?>



<script>
function seatz() {
var place = <?php echo $_GET["seat"]; ?>;

alert(place);
}
</script>

<body onload="seatz();">

<!-- Page content -->
<div id="content" align="left" style="text-align:center">

<!-- Displays the Orderve logo-->
<a href="controls.php"><img src="logoopaque.png" class="logo" alt="orderve"></a><br />

<script>

var seat = prompt("Please enter your location.", "Room 123");
alert("Hello person at " + seat);

// If seat/location parameter is empty, prompt the user to input location information



// Prompts the user to input their location
function seatInfo()
{
	var seat = prompt("Hmm, you don't seem to have any location information. Where are you right now?","ex. Room 12");
	
	// If answer is valid, set it as official location
	if (seat != null or seat != "")
	{
		<?php $_GET["seat"]; ?> = seat;
	}
	
}

</script>



<!-- Automatically generate menu table of food options -->
<?php

	$servername = "localhost";
	$username = "user";
	$password = "wachtwoord";

	$con = mysql_connect($servername, $username, $password);

	// If the server cannot connect, error.
	if (!$con)
  	{
  	die('Could not connect: ' . mysql_error());
  	}
	
	mysql_select_db("my_db", $con);
	
	
	// Begins menu table (white rounded rectangle background)
	echo "<table align=\"center\" class=\"menu\" id=\"menu\"><tr>";

	// Form to submit order
	echo "<form method=\"post\" action=\"orderinfo.php\">";

	// DRINKS section
	echo "<td align=\"left\"><div class=\"itemb\">Drinks</div></td><td align=\"right\">
			<img class=\"expand\" id=\"drinkbtnexpand\" src=\"buttonplus.png\" onClick=\"toggleDisplay('drinks', 'drinkbtnexpand')\">
			<img class=\"expand\" id=\"drinkbtncontract\" src=\"buttonminus.png\" onClick=\"toggleDisplay('drinks', 'drinkbtncontract')\"  style=\"display:none;\"></td></tr>";
	
	// Begins drinks table
	echo "<tr id=\"drinks\" style=\"display:none;\"><td><table>";

	// Selects all drinks from Foods table
	$result = mysql_query("SELECT * FROM Foods WHERE Category = 'drink'");



	while ($row = mysql_fetch_array($result))
	{
		// 3 items per row shown, start new row if current is filled
		//if ($i>3) { echo "</tr><tr>"; $i=1; }
		
		// Begin table cell
		echo "<tr><td>";

		// Begin food item div
		//echo "<div class=\"itemdiv\">";

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
		echo "<input type=\"hidden\" name=\"seat\" value=\"" . $_GET["seat"] . "\"/>";
		// Hidden price information
		echo "<input type=\"hidden\" name=\"price\" value=\"" . $row['FoodPrice'] . "\"/>";
		// End div
		//echo "</div>";
		// End table cell and row
		echo "</td><tr>";
	}
	
	// Close off DRINKS table
	echo "</tr></table></td></tr><br /></tr>";

	/* === SECTION DIVIDER BETWEEN DRINKS AND MAIN COURSES === */

	// MAIN COURSES section
	echo "<td align=\"left\"><div class=\"itemb\">Main Courses</div></td><td align=\"right\">
			<img class=\"expand\" id=\"mainbtnexpand\" src=\"buttonplus.png\" onClick=\"toggleDisplay('maincourses', 'mainbtnexpand')\">
			<img class=\"expand\" id=\"mainbtncontract\" src=\"buttonminus.png\" onClick=\"toggleDisplay('maincourses', 'mainbtncontract')\" style=\"display:none;\"></td></tr>";
	
	// Begins Main courses table (contains all selections for category)
	echo "<tr id=\"maincourses\" style=\"display:none;\"><td><table>";

	// Selects main course items from Foods table
	$result = mysql_query("SELECT * FROM Foods WHERE Category = 'main'");

	while ($row = mysql_fetch_array($result))
	{
		// 3 items per row shown, start new row if current is filled
		//if ($i>3) { echo "</tr><tr>"; $i=1; }
		
		// Begin table cell
		echo "<tr><td>";

		// Begin food item div
		//echo "<div class=\"itemdiv\">";

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
		echo "<input type=\"hidden\" name=\"seat\" value=\"" . $_GET["seat"] . "\"/>";
		// Hidden price information
		echo "<input type=\"hidden\" name=\"price\" value=\"" . $row['FoodPrice'] . "\"/>";
		// End div
		//echo "</div>";
		// End table cell and row
		echo "</td><tr>";
		
		// increment cell counter
		//$i++;
	}
	
	// Close off main courses table
	echo "</tr></table></td></tr><br /></tr>";

	// Button to submit order
	echo "<tr><td align=\"right\"><input type=\"submit\" value=\"Place Order\"></td></tr>";
	// Finish form
	echo "</form><br /><br />";




	// Close off menu table
	echo "</table>";




	?>
    
    
    <br /><br />
    
    		

</div>
    
    
    <script>

    /*
    // Gets width of food options table to set a static menu table width
	var width = document.getElementById(foodoptions).offsetWidth);
	
	alert("GOTHERE");

	document.getElementById(menu).style.width = width;
	*/
	
	// Script for order confirmation window
	function youSure()
	{
		var c=confirm("Are you sure you're ready to order?");
		// If yes
		if (c) {
			document.forms["orderform"].submit();
		}
		else {
		}
		
	}

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
