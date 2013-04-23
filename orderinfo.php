<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<!-- Imports fonts from Google Fonts API -->
<link href='http://fonts.googleapis.com/css?family=Economica|Merriweather+Sans:400,300|Maven+Pro:400,700' rel='stylesheet' type='text/css'>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<!-- Fits view to device screen width -->
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Order Information</title>
</head>


<style>

/* Sets the background to cover the whole window always and forever */
html { 
  background: url("back.jpg") no-repeat center center fixed; 
  -webkit-background-size: cover;
  -moz-background-size: cover;
  -o-background-size: cover;
  background-size: cover;
}

body{
	font-family:Tahoma, Geneva, sans-serif;
	color:#FFF;
	font-size:14px;
}

a {
	color:#FFF;
	text-decoration:none;
}

.receipt {
	text-align:right;
}

/* For addition line at end of receipt */
.bottom {
	border-bottom-color:transparent;
	border-left-color:transparent;
	border-right-color:transparent;
	border-top-color:#FFF;
	border-top-width:medium;
	border-top-style:double;
}

</style>

<body>

<div align="center" id="main">

<br />
<a href="index.php?seat=hammock">Home</a>
<br /><br />

<!-- Div is displayed when user navigates here without ordering anything -->
<div id="goback" style="display:none;">

    <p style="font-size:100px; font-weight:900; line-height:1%;">Oops!</p><br />
    <p>You don't seem to have selected anything...<br />
    Why don't you go back and try again?</p><br /><br />
    <a href="javascript:javascript:history.go(-1)">Return from whence you came!</a><br /><br />

</div>


<div id="infobody">

<h2>Order Confirmation</h2>
<br />

<!--
Adds user to Orders list, if they are not already on there, and modifies OrderCount
of both tables accordingly.
-->
<?php

$servername = "localhost";
$username = "user";
$password = "wachtwoord";

$con = mysql_connect($servername,$username,$password);
	
	// Error catch
	if (!$con)
  	{
  		die('Could not connect: ' . mysql_error());
  	}

	mysql_select_db("my_db", $con);
	

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
	
	// Start a session to put arrays to be passed in
	session_start();
	$_SESSION['foodArray'] = $foodArray;
	$_SESSION['quanArray'] = $quanArray;
	
	// Prints out some statistics at top of page
	echo "Number of items ordered: " . $totalitems;
	echo "<br />";
	echo "Order is for " . $_POST['seat'];
	echo "<br /><br /><br />";
	
	// Prints out a table of quantity, name, and cost of all items ordered
	
	echo "<table text-align=\"right\">";
	for ($row = 0; $row < $i; $row++)
	{
		echo "<tr>";
		for ($col = 0; $col < 3; $col++)
		{
			echo "<td>";
			echo $myOrder[$row][$col];
			echo "</td>";
		}
		echo "</tr>";
	}
	
	echo "</table>";
	
	?>
	
	<div id="bar" align="center" style="height:2px; color:#FFF; width:200;"></div>
	
    <!-- Prints out the total cost -->
	<div align="center" style="width:200;">
	<b>TOTAL COST:</b> <?php echo "$" . number_format($totalcost, 2) ?>
    </div>

<script>
	
	// If nothing is ordered, do not let user proceed 
	var items = <?php echo $totalitems ?>;
	
	if (items==0)
	{
		// Hides the main body of the page and prompts the user to go back
		document.getElementById("infobody").style.display="none";
		document.getElementById("goback").style.display="inline";
	}
	
</script>

<br /><br /><br />


<!-- Harvesting personal information
	*** FIX so they clear when focused on and so they are required to submit form ***
-->

<form action="placeorder.php" method="post">
<table>
<tr>
<td>Your name, perhaps?:</td>
<td>
<div id="select">
<select name="titles" id="titles" onChange="getTitle()">
<option value="Mr.">Mr.</option>
<option value="Mrs.">Mrs.</option>
<option value="Ms.">Ms.</option>
<option value="Miss">Miss</option>
<option value="N/A">N/A</option>
</select>
</div>
<input type="text" name="firstname" value="First Name" onclick="clearValue(this)" required/> *
<input type="text" name="lastname" value="Last Name" onclick="clearValue(this)"/></td>
</tr><tr>
<td>A telephone number, just in case?:</td>
<td><input type="tel" name="usertel" value="(XXX) XXX - XXXX" onclick="clearValue(this)"/></td>
</tr><tr>
<td>Why not an e-mail as well? You never know:</td>
<td><input type="email" name="usermail" value="username@mail.com" onclick="clearValue(this)"/></td>
</tr><tr>
<td>Comments about order:</td>
<td><input type="text" name="comments" value="Type your comments here." onClick="clearValue(this)"/></td>
</tr>
</table>

<br /><br />

<!-- Submit Order button -->
<input type="submit" value="Place Your Order" onclick="getTime()">

<!-- Hidden Values -->
<input type="hidden" name="seat" value="<?php echo $_POST["seat"]; ?>"/>
<input type="hidden" name="time" id="timestamp"/>
<input type="hidden" name="foodarray" value="<?php echo $_SESSION['foodArray'] ?>" />
<input type="hidden" name="quanarray" value="<?php echo $_SESSION['quanArray'] ?>" />
</form>

</div>

</div>

<script>

// Gets the chosen title from the drop down list
function getTitle()
{
    var titles = document.getElementById("titles");
	document.getElementById("titles").value=titles.options[titles.selectedIndex].value;
	return document.getElementById("titles").value;
}

// A script to clear values from the input boxes upon focus
function clearValue(instance)
{
	instance.value = "";
}

// A method of getting the current time so that the orders may be timestamped
// 	Note: (currentDate.getMonth() + 1) because months are counted starting with 0.
var currentDate = new Date();
var dateTime = currentDate.getDate() + "/" + (currentDate.getMonth() + 1)
				+ "/" + currentDate.getFullYear() + "@" + currentDate.getHours() + ":" +
				currentDate.getMinutes();
				
// Sets the value of the time hidden input to the current date and time
function setTime()
{
	document.getElementById('timestamp').value = dateTime;
	return;
}

setTime();

</script>

<!-- ** SHOULD PUT IN OPTION TO CHANGE LOCATION DOWN HERE IF GIVEN IS INCORRECT
		ALSO AN OPTION TO CHANGE QUANTITY OF ORDERS OR GO BACK AND ADD MORE ** -->



</body>
</html>