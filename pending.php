<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Pending Orders</title>
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

<!-- Takes user back to previous page-->
<script>
    document.write('<a href="' + document.referrer + '">Go Back</a>');
</script><br /><br /><br />


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

echo "<h1>Pending Orders</h1><br /><br />";

$result = mysql_query("SELECT * FROM pending");

echo "<table border='1'>
<tr>
<th>Order Number</th>
<th>Order</th>
<th>Location</th>
<th>Name</th>
<th>Comments</th>
<th>Time</th>
<th>Complete?</th>
</tr>";

// Iterates through array of row results
while($row = mysql_fetch_array($result))
  {
  echo "<tr>";
  echo "<td>" . $row['orderID'] . "</td>";
    // Table cell with id = order number
  echo "<td id=\"" . $row['orderID'] . "\">" /*. $row['Title']*/. "</td>"; // Will be filled later from another table
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




</body>

</html>