<!--

Orderve
Copyright (c) 2013 Alex Reynolds

menuedit.php

    - Page for the administrator to edit current menu offerings
    - A private page

    TODO:
    - Style

-->


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Manage Menu</title>
</head>

<!-- Fits view to device screen width -->
<meta name="viewport" content="width=device-width,initial-scale=1">

<!-- Imports fonts from Google Fonts API -->
<link href='http://fonts.googleapis.com/css?family=Economica|Merriweather+Sans:400,300|Maven+Pro:400,700' rel='stylesheet' type='text/css'>

<link rel="stylesheet" type="text/css" href="ordervestyle.css">

<script type="text/javascript">
	
	// For order confirmation window
	function youSure(str)
	{
		var c=confirm("Are you sure you want to clear all OrderCounts in " + str);
		// If yes, clear OrderCounts
		if (c && str=="Foods") { document.forms["clearfoods"].submit(); }
		else if (c && str=="Orders") { document.forms["clearorders"].submit(); }
		// Else, do nothing.
		else { }
		
	}
	
	// Prevents a switch to a new window when submitting by returning false
	function btnClick() {
		confirm("Are you sure you want to delete the current data?");
		return false;
	}
	
</script>

<body>

  <!-- Makes the page private. Only registered admins have access. -->
<?php 

    // Connects to database, begins session 
    require("common.php"); 
     
    // Checks to see if user has logged in
    if(empty($_SESSION['user'])) 
    { 
        // If not, redirect to login page
        header("Location: login.php");
        die("Redirecting to login.php"); 
    } 
?> 

<!-- Nav bar for control panel-->
<nav id="navside">
    <span class="head" style="color:#ABE52C;">Control Panel</span>

    <br><br>

    <ul>
        <li><a href="pending.php" style="font-weight: 700; font-size: 1.2em;">Pending Orders</a></li>
        <li><a href="menuedit.php">Edit Menu</a></li>
        <li><a href="createqr.php">Generate QR</a></li>
        <li><a href="analytics.php">Order Analytics</a></li>
        <li class="selected"><a href="controls.php">Controls Main</a></li>

        <br><br>
        
        <li><a href="index.php">Back to Home</a></li>
        <li><a href="logout.php">Log out</a></li>
    </ul>
</nav>

<div id="main" align="center">

<br /><br />

<!-- Takes the user back to the previous page -->
<a href="controls.php">Go Back to Controls</a><br /><br />
<!--<script>
    document.write('<a href="' + document.referrer + '">Go Back</a>');
</script><br /><br /><br />
-->


<!-- Table encapsulating the page's contents-->
<table id="outer">

<tr><td>

<h1>Menu</h1><br /><br />

<!-- Print out contents of tables to keep an eye on things -->
<?php

$servername = "localhost";
$username = "user";
$password = "wachtwoord";
$db = "my_db";

$con = mysql_connect($servername,$username,$password);
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }

mysql_select_db($db, $con);

// PRINT OUT FOODS TABLE //

// Gets items from table in alphabetical order
$result = mysql_query("SELECT * FROM Foods ORDER BY FoodName ASC");

echo "<table border='1' class='menu'>
<tr>
<th>Food Name</th>
<th>Price</th>
<th>Times Ordered</th>
<th>Image and URL</th>
<th>Category</th>
<th>Vegetarian?</th>
</tr>";

// Iterates through array of row results
while($row = mysql_fetch_array($result))
  {
  echo "<tr>";
  echo "<td>" . $row['FoodName'] . "</td>"; 
  echo "<td>$" . number_format($row['FoodPrice'], 2) . "</td>";
  echo "<td>" . $row['OrderCount'] . "</td>";
  echo "<td><img src=\"" . $row['ImageURL'] . "\" class=\"round\"><br /><br />"  . $row['ImageURL'] . "</td>";
  echo "<td>" . $row['Category'] . "</td>";
  echo "<td>";
  // Prints out yes/no depending on vegetarian status of item
  $isVeg = $row['Veg'];
  if ($isVeg) {
  	echo "Yes";
  } else {
  	echo "No";
  }
  echo "</td>";
  echo "</tr>";
  }
echo "</table><br />";

?>

</td>

<td>

<h1>Edit Menu</h1>

<br />

<!--
Allows establishment to edit menu via a form.
Form data is sent to modify.php
-->

<table><tr>
<td align="left">

<h2>Add item to menu</h2><br /><br />
<form action="modify.php" method="post">
Dish Name: <input type="text" name="foodname"> <br /><br />
Price (in dollars): <input type="text" name="foodprice"> <br /><br />
Image URL: <input type="text" name="imageurl"> <br /><br />
Dish Type:
<select name="category" id="category" onChange="getCategory()" required>
<option value="select" disabled>Select a type</option>
<option value="drink">Drink</option>
<option value="appetizer">Appetizer</option>
<option value="salad">Salad</option>
<option value="main">Main</option>
<option value="dessert">Dessert</option>
</select>
<br /><br />
Vegetarian?: <input type="checkbox" id="veg" name="veg" value="1">Yes <br /><br />
<input type="submit" value="Add"> <br />
<input type="hidden" name="action" value="insert" /><br />
</form>

<br /><br />

<h2>Remove item from menu</h2><br /><br />
<form action="modify.php" method="post">
Food Name: <input type="text" name="foodname"> <br /><br />
<input type="submit" value="Remove"> <br />
<input type="hidden" name="action" value="remove" /><br />
</form>

</td></tr>
</table>

</td></tr>

<!-- End of content table-->
</table>

</div>

<script>
// Gets the chosen food category from the drop down list
function getCategory()
{
    var titles = document.getElementById("category");
  document.getElementById("category").value=titles.options[titles.selectedIndex].value;
  return document.getElementById("category").value;
}

</script>

</body>
</html>