<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Insert Items</title>
</head>

<body>


<!-- Adds record to the table -->
<?php

$servername = "localhost";
$username = "user";
$password = "wachtwoord";

$con = mysql_connect($servername, $user, $password);
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }

mysql_select_db("my_db", $con);

$sql="INSERT INTO Foods (FoodName, FoodPrice, OrderCount, ImageURL)
VALUES ('$_POST[foodname]','$_POST[foodprice]', 0, '$_POST[imageurl]')";

if (!mysql_query($sql,$con))
  {
  die('Error: ' . mysql_error());
  }
  
echo "<img src=\"" . $_POST[imageurl] . "\" class=\"round\"><br/><br/>";
echo "Added " . $_POST[foodname] . " to the food table.";

// ***NEED TO CHECK TO ENSURE THAT THERE ARE NO DUPLICATES

mysql_close($con);
?>

<br /><br />

<!-- Goes back to menu edit page -->
<a href="menuedit.php">back</a>

</body>
</html>