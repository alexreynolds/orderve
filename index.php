<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php session_start(); ?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

	<!-- Imports fonts from Google Fonts API -->
	<link href='http://fonts.googleapis.com/css?family=Economica|Merriweather+Sans:400,300|Maven+Pro' rel='stylesheet' type='text/css'>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<title>Orderve</title>

<style>

body {
	font-family:'Maven Pro', Geneva, sans-serif;
	color:#000;
	font-size:.8em;
	background-color:#003366;
	padding-top:20px;
	background:url("back.jpg") no-repeat;
	/*background-size:cover;*/
	background-size:100%;
}

/* logo header */
.logo {
	width:150px;
	height:150px;
}

/* logo footer */
.logotxt {
	font-size:2em;
	color:#FFF;
	vertical-align:center;
	position:relative;
}

.round {
	border-radius:15px;
	width:100px;
	height:100px;
}

/* table containing menu */
.menu {
	border-radius:15px;
	background-color:#FFF;
	padding:15px;
	box-shadow: 0px 5px 10px #444;
	width:50%;
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

.actionbar {
	height:7%;
	width:100%;
	background-color:#222222;
	position:fixed;
	left:0px;
	top:0px;
	text-align:center;
	vertical-align:center;
	padding:.5%;
	box-shadow: 0px 2px 10px #111;
	padding-top:.5%;
}

</style>

</head>

<body>

<!-- Creates the relevant tables in the database if they have not already been created -->
<?php include 'tablecreation.php' ?>


<!-- Checks to see if location information exists, otherwise, prompt and redirect -->
<?php
	if (empty($_GET['seat']))
	{
		$_SESSION['seat'] = "unknown";
		
	}
	else
	{
		// Takes note of the user's location for the rest of the session
		$_SESSION['seat'] = $_GET['seat'];
	}
?>

<!-- <div id="actionbar" class="actionbar"> <span class="logotxt">orderve</span> </div> -->


<!-- Page content -->
<div id="content" align="left" style="text-align:center">

<!-- Displays the Orderve logo-->
<a href="controls.php"><img src="logoopaque.png" class="logo" alt="orderve"></a>



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
		echo "<input type=\"hidden\" name=\"seat\" value=\"" . $_SESSION['seat'] . "\"/>";
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
		echo "<input type=\"hidden\" name=\"seat\" value=\"" . $_SESSION['seat'] . "\"/>";
		// Hidden price information
		echo "<input type=\"hidden\" name=\"price\" value=\"" . $row['FoodPrice'] . "\"/>";
		// End div
		//echo "</div>";
		// End table cell and row
		echo "</td><tr>";
		
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
