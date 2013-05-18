<!--

Orderve
Copyright (c) 2013 Alex Reynolds

pending.php

    - Lists all of the pending orders for the establishment
    - Has an option to remove orders from the list once filled
    - A private page

    TODO:
    - Style

-->

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Pending Orders</title>

<!-- Fits view to device screen width -->
<meta name="viewport" content="width=device-width,initial-scale=1">

<!-- Imports fonts from Google Fonts API -->
<link href='http://fonts.googleapis.com/css?family=Maven+Pro:400,700' rel='stylesheet' type='text/css'>

<link rel="stylesheet" type="text/css" href="ordervestyle.css">

</head>

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
        <li class="selected"><a href="pending.php" style="font-weight: 700; font-size: 1.2em;">Pending Orders</a></li>
        <li><a href="menuedit.php">Edit Menu</a></li>
        <li><a href="createqr.php">Generate QR</a></li>
        <li><a href="analytics.php">Order Analytics</a></li>
        <li><a href="controls.php">Controls Main</a></li>

        <br><br>

        <li><a href="index.php">Back to Home</a></li>
        <li><a href="logout.php">Log out</a></li>
    </ul>
</nav>

<div id="#mainwrapper">

<div class="controlsmain" id="controlsmain">

<h1>Pending Orders</h1>

<!-- Refresh button for page-->
<input type="button" value="Refresh page" onClick="document.location.reload(true)">

<br><br>

<div id="tablecontent">

<!-- Print out contents of user table -->
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

// == PRINT OUT PENDING ORDERS TABLE == //

// $foodOrders is an array to hold actual food orders in
// Array keys are order numbers
$foodOrders[0] = '';

// Select all food orders from Orders table (to be inserted into table as well)
$foodresult = mysql_query("SELECT * FROM Orders");

// Iterate through all Orders table rows
// Add each order as a string to the foodOrders array
while($row = mysql_fetch_array($foodresult))
{
  $ordernum = $row['orderID'];
  // If orderID exists as key already, edit its value
  if (array_key_exists($ordernum, $foodOrders)) {
    // String to store
    $tempstr = $foodOrders[$ordernum];
    $foodOrders[$ordernum] = $tempstr . "(" . $row['count'] . ") " . $row['foodID'] . "<br>";
  }
  else {
    $foodOrders[$ordernum] = "(" . $row['count'] . ") " . $row['foodID'] . "<br>";
  }
}


$result = mysql_query("SELECT * FROM pending");

echo "<table id=\"pendingtable\">
<tr>
<th id=\"ordernum\">Order Number</th>
<th id=\"foodorder\">Order</th>
<th id=\"loc\">Location</th>
<th id=\"name\">Name</th>
<th id=\"comments\">Comments</th>
<th id=\"time\">Timestamp</th>
<th id=\"complete\">Complete?</th>
</tr>";

// Iterates through array of row results
while($row = mysql_fetch_array($result))
  {
    $orderid = $row['orderID'];
    echo "<tr>";
    echo "<td>" . $row['orderID'] . "</td>";
    // Table cell with id = order number
    echo "<td>" . $foodOrders[$orderid] . "</td>";
    echo "<td>" . $row['Location'] . "</td>";
    echo "<td>" . $row['Name'] . "</td>";
    echo "<td>" . $row['Comments'] . "</td>";
    echo "<td>" . $row['Time'] . "</td>";
    echo "<td>";
    // Button to remove an order from the list once it has been filled
    echo "<form action=\"modify.php\" method=\"post\">
    <input type=\"submit\" value=\"Remove\"> <br />
    <input type=\"hidden\" name=\"action\" value=\"ordercomplete\"/>
    <input type=\"hidden\" name=\"ordernumber\" value=\"" . $row['orderID'] . "\"/>
    </form>";
    echo "</td>";

    echo "</tr>";
  }

echo "</table>";

// USE UPDATE INNER HTML TO GO THROUGH AND ADD FOOD ORDER TO TABLE


mysql_close($con);
?>

<!-- End table div -->
</div>

<!-- End content div -->
</div>

<!-- End content wrapper div -->
</div>


</body>

</html>