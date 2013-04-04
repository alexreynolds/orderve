<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Manage Menu</title>
</head>

<style type="text/css">

body {
	text-align:center;
	font-family:Tahoma, Geneva, sans-serif;
	color:#000;
	font-size:10px;
}

table {
	
	text-align:center;
}

td {
	vertical-align:top;
}

.round {
	border-radius:15px;
}

td {
	padding:10px;
}

img {
	width:50%;
	height:50%;	
}

</style>

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

<div id="main" align="center">

<br /><br />

<a href="index.php?seat=hammock">Go Home</a><br /><br /><br />

<table id="outer">
<tr>
<td>

<!--
Allows establishment to edit menu via a form.
Form data is sent to modify.php
-->

<table><tr>
<td align="left">
<h2>Add item to Foods Table</h2><br /><br />
<form action="modify.php" method="post">
Food Name: <input type="text" name="foodname"> <br />
Food Price (in dollars): <input type="text" name="foodprice"> <br />
Image URL: <input type="text" name="imageurl"> <br />
<input type="submit" value="Add"> <br />
<input type="hidden" name="action" value="insert" /><br />
</form>

<br /><br />

<h2>Remove item from Foods Table</h2><br /><br />
<form action="modify.php" method="post">
Food Name: <input type="text" name="foodname"> <br />
<input type="submit" value="Remove"> <br />
<input type="hidden" name="action" value="remove" /><br />
</form>
</td>

<td>
<h2>Add item to Orders Table</h2><br /><br />
<form action="modify.php" method="post">
Description: <input type="text" name="description"> <br />
<!-- MOVED TO DIFFERENT TABLE
Title: <input type="text" name="title"> <br />
First Name:<input type="text" name="first"> <br />
Last Name:<input type="text" name="last" /><br />
Phone Number:<input type="tel" name="phone" /><br />
E-Mail:<input type="email" name="email" /><br />
Time:<input type="datetime" name="time"  /><br />
-->
<input type="submit" value="Add"> <br />
<input type="hidden" name="action" value="insert" /><br />
</form>
</td>

</tr>
<tr>

<td align="left">
<!-- Calls to return all OrderCount values in the Foods table to 0 -->
<h3>Clear Foods OrderCount</h3><br /><br />
<form action="modify.php" method="post" name="clearfoods"
	onsubmit="return confirm('Are you sure you want to return all order counts to 0?')">
<input type="submit" value="Clear Foods OrderCounts"/><br />
<input type="hidden" name="action" value="clearfoods" /><br />
</form>

<!-- Calls to return all OrderCount values in the Orders table to 0 -->
<h3>Clear Orders OrderCount</h3><br /><br />
<form action="modify.php" method="post" name="clearorders"
	onsubmit="btnClick()">
<input type="submit" value="Clear Orders OrderCounts"/><br />
<input type="hidden" name="action" value="clearorders" /><br />
</form>

</tr></table>


<tr><td>

<h1>Foods</h1><br /><br />

<!-- Print out contents of tables to keep an eye on things -->
<?php

$servername = "localhost";
$username = "user";
$password = "wachtwoord";

$con = mysql_connect($servername,$username,$password);
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }

mysql_select_db("my_db", $con);

// PRINT OUT FOODS TABLE //

$result = mysql_query("SELECT * FROM Foods");

echo "<table border='1'>
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


echo "</td><td>";


// == PRINT OUT ORDERS TABLE == //

echo "<h1>Orders</h1><br /><br />";

$result = mysql_query("SELECT * FROM Orders");

echo "<table border='1'>
<tr>
<th>seat ID</th>
<th>OrderCount</th>
<th>Seat Name</th>
</tr>";

// Iterates through array of row results
while($row = mysql_fetch_array($result))
  {
  echo "<tr>";
  echo "<td>" . $row['seatID'] . "</td>";
  echo "<td>" . $row['OrderCount'] . "</td>";
  echo "<td>" . $row['Description'] . "</td>";
  echo "</tr>";
  }
echo "</table>";

echo "</td><td>";


// == PRINT OUT USERS TABLE == //

echo "<h1>Users</h1><br /><br />";

$result = mysql_query("SELECT * FROM Users");

echo "<table border='1'>
<tr>
<th>user ID</th>
<th>Title</th>
<th>First Name</th>
<th>Last Name</th>
<th>Phone</th>
<th>E-mail</th>
<th>OrderCount</th>
<th>Time</th>
</tr>";

// Iterates through array of row results
while($row = mysql_fetch_array($result))
  {
  echo "<tr>";
  echo "<td>" . $row['userID'] . "</td>";
  echo "<td>" . $row['Title'] . "</td>";
  echo "<td>" . $row['FirstName'] . "</td>";
  echo "<td>" . $row['LastName'] . "</td>";
  echo "<td>" . $row['Phone'] . "</td>";
  echo "<td>" . $row['Email'] . "</td>";
  echo "<td>" . $row['OrderCount'] . "</td>";
  echo "<td>" . $row['Time'] . "</td>";
  echo "</tr>";
  }
echo "</table>";


mysql_close($con);
?>

</td></tr>
</table>

</div>

</body>
</html>