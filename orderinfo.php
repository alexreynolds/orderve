<!--

Orderve
Copyright (c) 2013 Alex Reynolds

orderinfo.php

    - The page shown once the user opts to check out
    - Lists what they have ordered along with total price for checking purposes
    - User inputs personal information here

    TODO:
    - Integrate e-commerce payment method

-->


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<!-- Imports fonts from Google Fonts API -->
<link href='http://fonts.googleapis.com/css?family=Maven+Pro:400,700' rel='stylesheet' type='text/css'>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<!-- Fits view to device screen width -->
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Order Information</title>

<link rel="stylesheet" type="text/css" href="ordervestyle.css">

</head>

<body>

<!-- Nav bar -->
<nav class="top">
	<table style="width:100%; height:100%; text-align:center; vertical-align:center"><tr>
		<td style="width:100px"><a class="back" href="javascript:javascript:history.go(-1)">Back</a></td>
		<td style="padding:0"><span class="head">Orderve</span></td>
		<td style="width:100px;"></td>
	</tr></table>
</nav>

<!-- Div is displayed when user navigates here without ordering anything -->
<div id="goback" style="display:none;" class="main">

    <h1 style="font-size: 4em;">Oops!</h1><br>
    Pick something before you try checking out!<br /><br />
    <span style="font-style:italic; font-size:.4em; color:#555">(Surely something must have tickled your fancy?)</span><br /><br />

</div>


<!-- Main page content -->
<div id="contentwrapper">
<!--<div id="infobody" align="center"> -->

<!-- Displayed if all is well with order -->
<div class="main" id="orderinfo">

<h2>Is everything correct?</h2>

<!--
Adds user to Orders list, if they are not already on there, and modifies OrderCount
of both tables accordingly.
-->
<?php

$servername = "localhost";
$username = "user";
$password = "wachtwoord";
$db = "my_db";

$con = mysql_connect($servername,$username,$password);
	
	// Error catch
	if (!$con)
  	{
  		die('Could not connect: ' . mysql_error());
  	}

	mysql_select_db($db, $con);

	// If a session hasn't been started for whatever reason, start.
	if(session_id() == '') {
    	session_start();
	}
	

// === ORDER RECEIPT GENERATION === //

	$result = mysql_query("SELECT * FROM Foods");
	
	// Array to keep track of what was ordered, quantity and price
	$myOrder = array();

	// Arrays to pass to other pages, containing items ordered and quantity
	// Using 2 separate arrays due to offset issues when attempting to use one serialized multi-dim. array
	$foodArray = array();
	$quanArray = array();

	// Accumulators
	$i = 0;
	$totalcost = 0;
	$totalitems = 0;
	
	// Iterates through the Foods table, taking note of what was ordered and in what amount
	while ($row = mysql_fetch_array($result)) {
		
		// Quantity of an item is accessed with its foodID
		$id = $row['foodID'];
		$quantity = $_POST[$id];
		
		// Quantity > 0 if someone ordered something
		if ($quantity > 0) {
			$price = $row['FoodPrice'] * $quantity;

			// Update the total cost to reflect addition of item(s)
			$totalcost = $totalcost + $price;

			// Update the total items to reflect order
			$totalitems = $totalitems + $quantity;

			// Add the item and relevant info to the array for future reference, as well as array for passing
			$myOrder[$i] = array($quantity, $row['FoodName'], "$" . number_format($price,2));
			$foodArray[$i] = $row['FoodName'];
			$quanArray[$i] = $quantity;
			
			$i++;
		} 
		
	}
	
	// Puts food and quantity arrays in session variables
	$_SESSION['foodArray'] = $foodArray;
	$_SESSION['quanArray'] = $quanArray;
	
	// Prints out some statistics at top of page
	echo "<i>Order is for user at " . $_POST['seat'] . "</i>";
	echo "<br />";
	echo "<b>" . $totalitems . "</b>";
		if ($totalitems > 1) {
			echo " items ordered";
		}
		else {
			echo " item ordered";
		}
	echo "<br /><br />";
	//echo "<br /><br /><br />";
	

	// Prints out a table of quantity, name, and cost of all items ordered
	
	echo "<table id=\"receipt\">";
	for ($row = 0; $row < $i; $row++)
	{
		echo "<tr>";
		for ($col = 0; $col < 3; $col++)
		{
			echo "<td>";
			if ($col == 1) { echo "<span class=\"recitem\">"; }
			echo $myOrder[$row][$col];
			if ($col == 1) { echo "</span>"; }
			echo "</td>";
		}
		echo "</tr>";
	}

	echo "<tr><td></td><td><b>TOTAL</b></td>";

	// Total cost
	echo "<td>$" . number_format($totalcost, 2) . "</td>";

	echo "</tr></table>";
	
	?>

<script>
	
	// If nothing is ordered, do not let user proceed 
	var items = <?php echo $totalitems ?>;
	
	if (items==0)
	{
		// Hides the main body of the page and prompts the user to go back
		document.getElementById("orderinfo").style.display="none";
		document.getElementById("goback").style.display="inline";
	}
	
</script>

<br /><br />


<!-- Harvesting personal information
	*** FIX so they clear when focused on and so they are required to submit form ***
-->

<div id="userinfo">

<h2>Information please!</h2>

<form action="placeorder.php" method="post">
Title<br />
<select name="titles" id="titles" onChange="getTitle()">
<option value="Mr.">Mr.</option>
<option value="Mrs.">Mrs.</option>
<option value="Ms.">Ms.</option>
<option value="Miss">Miss</option>
<option value="N/A">N/A</option>
</select><br /><br />
First name<br />
<input type="text" name="firstname" placeholder="Your first name" required/><br /><br />
Last name<br />
<input type="text" name="lastname" placeholder="Your last name" required/><br /><br />
Phone number<br />
<input type="tel" name="usertel" placeholder="ex. (XXX) XXX - XXXX"/><br /><br />
E-mail<br />
<input type="email" name="usermail" placeholder="ex. username@mail.com"/><br /><br />
Order comments<br />
<input type="text" name="comments" placeholder="Comments go here!" maxlength="250"/><br />

<br />

<!-- Hidden Values -->
<input type="hidden" name="seat" value="<?php echo $_POST["seat"]; ?>"/>
<input type="hidden" name="time" id="timestamp"/>
<input type="hidden" name="foodarray" value="<?php echo $_SESSION['foodArray'] ?>" />
<input type="hidden" name="quanarray" value="<?php echo $_SESSION['quanArray'] ?>" />
</div>

<!-- Submit Order button -->
<input type="submit" value="Place Your Order" onclick="getTime()">

</form>

<br><br>

<!-- End main div -->
</div>

<!-- End content wrapper -->
</div>

<script>

// Gets the chosen title from the drop down list
function getTitle()
{
    var titles = document.getElementById("titles");
	document.getElementById("titles").value=titles.options[titles.selectedIndex].value;
	return document.getElementById("titles").value;
}

// A method of getting the current time so that the orders may be timestamped
// 	Note: (currentDate.getMonth() + 1) because months are counted starting with 0.
var currentDate = new Date();
var dateTime = currentDate.getDate() + "." + (currentDate.getMonth() + 1)
				+ "@" + currentDate.getHours() + ":" +
				currentDate.getMinutes();
				
// Sets the value of the time hidden input to the current date and time
function setTime()
{
	document.getElementById('timestamp').value = dateTime;
	return;
}

setTime();

</script>


</body>
</html>