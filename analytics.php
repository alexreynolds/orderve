<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Manage Menu</title>
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





</body>

</html>