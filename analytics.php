<!--

Orderve
Copyright (c) 2013 Alex Reynolds

analytics.php

    - Displays logistics and data about app/order history
    - Things like most popular item, least popular items, etc
    - A private page

    TODO:
    - Add graphs

-->


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Order Analytics</title>

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
        <li><a href="pending.php" style="font-weight: 700; font-size: 1.2em;">Pending Orders</a></li>
        <li><a href="menuedit.php">Edit Menu</a></li>
        <li><a href="createqr.php">Generate QR</a></li>
        <li class="selected"><a href="analytics.php">Order Analytics</a></li>
        <li><a href="controls.php">Controls Main</a></li>

        <br><br>
        
        <li><a href="index.php">Back to Home</a></li>
        <li><a href="logout.php">Log out</a></li>
    </ul>
</nav>

<div id="mainwrapper">

<div class="controlsmain" id="analyticsmain">

<h1>Order Analytics</h1>

<h2>Statistics</h2>

<table id="statstable">

  <th>Total orders to date</th>
  <th>Most popular item</th>
  <th>Least popular item</th>

  <tr>

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

// Gets total order count based on Foods table values

$result = mysql_query("SELECT SUM(OrderCount) AS ordersum FROM Foods"); 
$row = mysql_fetch_assoc($result); 
$sum = $row['ordersum'];

echo "<td><b>" . $sum . "</b> orders</td>";


// Figures out the most popular (highest order count) item

$result = mysql_query("SELECT MAX(OrderCount) AS pop FROM Foods");
$row = mysql_fetch_assoc($result); 

  // Holds the highest order count (an int)
  $item = $row['pop'];

$result = mysql_query("SELECT * FROM Foods WHERE OrderCount = $item");
$row = mysql_fetch_assoc($result);

echo "<td>" . $row['FoodName'] . " | <b>" . $row['OrderCount'] . "</b> orders</td>";


// Figures out the least popular (lowest order count) item

$result = mysql_query("SELECT MIN(OrderCount) AS notpop FROM Foods");
$row = mysql_fetch_assoc($result); 

  // Holds the lowest order count (an int)
  $item = $row['notpop'];

$result = mysql_query("SELECT * FROM Foods WHERE OrderCount = $item");
$row = mysql_fetch_assoc($result);

echo "<td>" . $row['FoodName'] . " | <b>" . $row['OrderCount'] . "</b> orders</td>";


// End menu stats, begin user stats
echo "</tr>";
echo "<th>Most frequent user</th>
      <th>Oldest user</th>
      <th>Newest user</th>";
echo "<tr>";

// Figures out the most frequent user (highest order count)

$result = mysql_query("SELECT MAX(OrderCount) AS freq FROM Users");
$row = mysql_fetch_assoc($result); 

  // Holds the highest order count (an int)
  $user = $row['freq'];

$result = mysql_query("SELECT * FROM Users WHERE OrderCount = $user");
$row = mysql_fetch_assoc($result);

echo "<td>" . $row['Title'] . " " . $row['FirstName'] . " " . $row['LastName'] . "<br /><b>" . $row['OrderCount'] . "</b> orders</td>";


// Figures out the oldest user (lowest user ID)

$result = mysql_query("SELECT MIN(userID) AS old FROM Users");
$row = mysql_fetch_assoc($result); 

  // Holds the lowest order count (an int)
  $user = $row['old'];

$result = mysql_query("SELECT * FROM Users WHERE userID = $user");
$row = mysql_fetch_assoc($result);

echo "<td>" . $row['Title'] . " " . $row['FirstName'] . " " . $row['LastName'] . "<br />User since <b>" . $row['Time'] . "</b></td>";


// Figures out the newest user (lowest user ID)

$result = mysql_query("SELECT MIN(userID) AS new FROM Users");
$row = mysql_fetch_assoc($result); 

  // Holds the lowest order count (an int)
  $user = $row['new'];

$result = mysql_query("SELECT * FROM Users WHERE userID = $user");
$row = mysql_fetch_assoc($result);

echo "<td>" . $row['Title'] . " " . $row['FirstName'] . " " . $row['LastName'] . "<br />User since <b>" . $row['Time'] . "</b></td>";

// End statistics table
echo "</tr></table>";


echo "<br /><br />";

// == PRINT OUT USERS TABLE == //

echo "<h2>Users</h2>";

$result = mysql_query("SELECT * FROM Users");

echo "<table id='usertable'>
<tr>
<th>Title</th>
<th>First</th>
<th>Last</th>
<th>Phone</th>
<th>E-mail</th>
<th># Orders</th>
<th>First Order</th>
</tr>";

// Iterates through array of row results
while($row = mysql_fetch_array($result))
  {
  echo "<tr>";
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

<!-- End contents div -->
</div>

<!-- End wrapper div -->
</div>


</body>

</html>