<!--

Orderve
Copyright (c) 2013 Alex Reynolds

index.php

    - The home page for the app
    - The available menu is displayed, where users can pick items of quantities up to 20 to add to cart

    TODO:
    - Add option for vegetarian fiter
    - Add live updating receipt at bottom of page

-->


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php session_start(); ?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<title>Orderve</title>

<!-- Imports fonts from Google Fonts API -->
<link href='http://fonts.googleapis.com/css?family=Maven+Pro:400,700' rel='stylesheet' type='text/css'>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<!-- Fits view to device screen width -->
<meta name="viewport" content="width=device-width,initial-scale=1">

<link rel="stylesheet" type="text/css" href="ordervestyle.css">

</head>

<body onload="window.addEventListener('orientationchange', handleOrientation, false);">

<!-- Creates the relevant tables in the database if they have not already been created -->
<?php include 'tablecreation.php' ?>


<!-- Nav bar with submit order button -->
<nav class="top">
	<table style="width:100%; height:100%; text-align:center; vertical-align: middle;"><tr>
		<td style="width:100px; vertical-align: middle;"></td>
		<td style="padding:0; vertical-align: middle;"><span class="head">Orderve</span></td>
		<td style="width:100px; vertical-align: middle;"><a form="orderform" class="checkout" onclick="orderform.submit();">Checkout</a></td>
	</tr></table>
</nav>

<div id="contentwrapper">

<!-- Page content -->
<div class="main">

<!-- Begins menu table -->
<table class="content" id="menu">
<!-- Form to submit order -->

<form method="post" action="orderinfo.php" id="orderform">

<!-- Start APPETIZERS section -->
<tr>
	<td align="left" class="catetitle">Appetizers</td>
	<td align="right">
	<img class="expand" id="appetizerbtnexpand" src="buttonplus2.png" onClick="toggleDisplay('appetizers', 'appetizerbtnexpand')">
	<img class="expand" id="appetizerbtncontract" src="buttonminus2.png" onClick="toggleDisplay('appetizers', 'appetizerbtncontract')" style="display:none;">
	</td>
</tr>

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
	
	/*
	// Begins menu table
	echo "<table class=\"content\" id=\"menu\"><tr>";

	// Form to submit order
	echo "<form method=\"post\" action=\"orderinfo.php\" id=\"orderform\">";


	// APPETIZERS section
	
	echo "<tr>
			<td align=\"left\" class=\"catetitle\">Appetizers</td>
			<td align=\"right\">
			<img class=\"expand\" id=\"appetizerbtnexpand\" src=\"buttonplus2.png\" onClick=\"toggleDisplay('appetizers', 'appetizerbtnexpand')\">
			<img class=\"expand\" id=\"appetizerbtncontract\" src=\"buttonminus2.png\" onClick=\"toggleDisplay('appetizers', 'appetizerbtncontract')\" style=\"display:none;\">
			</td>
			</tr>";
	*/

	// Begins appetizer table (contains all selections for category)
	echo "<tr id=\"appetizers\" style=\"display: none;\"><td style='width: 800px'>";

	// Selects appetizer items from Foods table
	$result = mysql_query("SELECT * FROM Foods WHERE Category = 'appetizer'");

	// Generates appetizer options table
	optionsTableGen($result);



	// SALADS section
	
	echo "<tr>
			<td align=\"left\" class=\"catetitle\">Soups and Salads</td>
			<td align=\"right\">
			<img class=\"expand\" id=\"saladbtnexpand\" src=\"buttonplus2.png\" onClick=\"toggleDisplay('salads', 'saladbtnexpand')\">
			<img class=\"expand\" id=\"saladbtncontract\" src=\"buttonminus2.png\" onClick=\"toggleDisplay('salads', 'saladbtncontract')\" style=\"display:none;\">
			</td>
			</tr>";
	

	// Begins salad table (contains all selections for category)
	echo "<tr id=\"salads\" style=\"display:none;\"><td style='width: 800px'>";

	// Selects salad items from Foods table
	$result = mysql_query("SELECT * FROM Foods WHERE Category = 'salad'");

	// Generates salad options table
	optionsTableGen($result);



	// MAIN COURSES section
	
	echo "<tr>
			<td align=\"left\" class=\"catetitle\">Main Courses</td>
			<td align=\"right\">
			<img class=\"expand\" id=\"mainbtnexpand\" src=\"buttonplus2.png\" onClick=\"toggleDisplay('maincourses', 'mainbtnexpand')\">
			<img class=\"expand\" id=\"mainbtncontract\" src=\"buttonminus2.png\" onClick=\"toggleDisplay('maincourses', 'mainbtncontract')\" style=\"display:none;\">
			</td></tr>";
	

	// Begins Main courses table (contains all selections for category)
	echo "<tr id=\"maincourses\" style=\"display:none;\"><td style='width: 800px'>";

	// Selects main course items from Foods table
	$result = mysql_query("SELECT * FROM Foods WHERE Category = 'main'");

	// Generates main course options table
	optionsTableGen($result);



	// DESSERTS section
	echo "<tr>
			<td align=\"left\" class=\"catetitle\">Desserts</td>
			<td align=\"right\">
			<img class=\"expand\" id=\"dessbtnexpand\" src=\"buttonplus2.png\" onClick=\"toggleDisplay('dess', 'dessbtnexpand')\">
			<img class=\"expand\" id=\"dessbtncontract\" src=\"buttonminus2.png\" onClick=\"toggleDisplay('dess', 'dessbtncontract')\"  style=\"display:none;\">
			</td></tr>";
			
	// Begins desserts table
	echo "<tr id=\"dess\" style=\"display:none;\"><td style='width: 800px'>";

	// Selects all desserts from Foods table
	$result = mysql_query("SELECT * FROM Foods WHERE Category = 'dessert'");

	// Generates desserts options table
	optionsTableGen($result);



	// DRINKS section
	echo "<tr>
			<td align=\"left\" class=\"catetitle\">Drinks</td>
			<td align=\"right\">
			<img class=\"expand\" id=\"drinkbtnexpand\" src=\"buttonplus2.png\" onClick=\"toggleDisplay('drinks', 'drinkbtnexpand')\">
			<img class=\"expand\" id=\"drinkbtncontract\" src=\"buttonminus2.png\" onClick=\"toggleDisplay('drinks', 'drinkbtncontract')\"  style=\"display:none;\">
			</td></tr>";
			
	// Row containing drinks options table
	echo "<tr id=\"drinks\" style=\"display:none;\"><td style='width: 800px'>";

	// Selects all drinks from Foods table
	$result = mysql_query("SELECT * FROM Foods WHERE Category = 'drink'");

	// Generates drinks options table
	optionsTableGen($result);


	// Finish form
	echo "</form><br /><br />";


	// Close off menu table
	echo "</table>";



	// Function to generate the options tables
	// INPUT: res, the mysql query result for the respective category
	function optionsTableGen($res) {

		echo "<table class=\"options\">";

		while ($row = mysql_fetch_array($res))
		{
			echo "<tr><td class=\"iteminfo\">";
				// Name of food item
				echo "<span class=\"itemname\">" . $row['FoodName'] . "</span>";
					// If the item is vegetarian, display the vegetarian icon next to the item name
					if ($row['Veg'] == '1')
					{
						echo "  <img src=\"vegicon.png\" class=\"vegicon\">";
					}
				echo "<br />";
				// Item description
				echo "<span class=\"desc\">" . $row['description'] . "</span><br />";
				// Price & quantity of food item
				echo "$" . number_format($row['FoodPrice'],2) . "  <input type=\"number\" id=\"q\" value=\"0\" min=\"0\" max=\"20\" step=\"1\"
				name=\"" . $row['foodID'] . "\"><br />";
			// Hidden food name value
			echo "<input type=\"hidden\" name=\"foodtype\" value=\"" . $row['FoodName'] . "\" />";
			// Hidden seat location information
			echo "<input type=\"hidden\" name=\"seat\" value=\"" . $_SESSION['seat'] . "\"/>";
			// Hidden price information
			echo "<input type=\"hidden\" name=\"price\" value=\"" . $row['FoodPrice'] . "\"/>";

			// Close off item info cell and row
			echo "</td></tr>";
		}
		
		// Close off options table
		echo "</table>";

		// Close off cell and row of menu category row
		echo "</tr>";
	}

	?>
    
<!-- End content div -->
</div>

<!-- End content wrapper -->
</div>
    

<!--
<nav class="bottom">
	<footer><a href="controls.php">Control Panel</a><br><br>
			Copyright © 2013 Alex Reynolds</footer>
</nav>
-->
    
    
    <script>

	// Toggles the display of an element (for dropdown menus)
	function toggleDisplay(id, btn)
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
		else if (btn == "appetizerbtnexpand")
			document.getElementById('appetizerbtncontract').style.display = "block";
		else if (btn ==	"appetizerbtncontract")
			document.getElementById('appetizerbtnexpand').style.display = "block";
		else if (btn == "saladbtnexpand")
			document.getElementById('saladbtncontract').style.display = "block";
		else if (btn ==	"saladbtncontract")
			document.getElementById('saladbtnexpand').style.display = "block";
		else if (btn == "mainbtnexpand")
			document.getElementById('mainbtncontract').style.display = "block";
		else if (btn ==	"mainbtncontract")
			document.getElementById('mainbtnexpand').style.display = "block";
		else if (btn == "dessbtnexpand")
			document.getElementById('dessbtncontract').style.display = "block";
		else if (btn ==	"dessbtncontract")
			document.getElementById('dessbtnexpand').style.display = "block";

		return false;

	}


	// Dictates what to do when device orientation changes
	// Note that on some tablets, landscape mode is considered standard orientation,
	// 	so must take that into consideration for orientation calculations
	function handleOrientation() {

	// Attempt to fix orientation
	if (orientation == 0 && window.width > window.height) { orientation += 90; }

	// Keeps the orientation angle [0,360]
	if (orientation > 360)
	{
	    orientation -= 360;
	}

	// Actions related to orientation
	if (orientation == 0) {
		// Portrait
	  //alert('portrait');
	}
	else if (orientation == 90) {
		// Landscape
	  //alert('landscape');
	}
	else if (orientation == -90) {
	  // Landscape
	  //alert('landscape');
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

</body>
</html>
