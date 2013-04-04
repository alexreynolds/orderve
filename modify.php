<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Insert Items</title>
</head>

<style>

body {
	text-align:center;
	font-family:Tahoma, Geneva, sans-serif;
	color:#000;
	font-size:.8em;
}

.round {
	border-radius:15px;
}

</style>

<body>

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

// INSERTING an item into Foods table
if ($_POST['action']=='insert') {
	
	$itemname = $_POST['foodname'];
	$result = mysql_query("SELECT * FROM Foods WHERE foodname='$itemname'");
	$num_rows = mysql_num_rows($result);

	// If true, there are already instances of the item in the table. Do nothing.
	if ($num_rows)
	{
		echo "This item already exists in the table.";
	} 
	else 	// Otherwise, insert item into table accordingly
	{
	$sql="INSERT INTO Foods (FoodName, FoodPrice, OrderCount, ImageURL)
	VALUES ('$_POST[foodname]','$_POST[foodprice]', 0, '$_POST[imageurl]')";

	// Error catch
	if (!mysql_query($sql,$con))
  	{
  		die('Error: ' . mysql_error());
  	}
  
	echo "<img src=\"thumbsup.jpg\" class=\"round\"><br/><br/>";
	echo "Added " . $_POST['foodname'] . " to the Foods table.";
	}

} 

// REMOVING an item from Foods table
else if ($_POST['action']=='remove') {
	
	$itemname = $_POST['foodname'];
	$result = mysql_query("SELECT * FROM Foods WHERE foodname='$itemname'");
	$num_rows = mysql_num_rows($result);

	// If true, there are already instances of the item in the table. Can be removed.
	if ($num_rows)
		{
				
			$sql="DELETE FROM Foods WHERE FoodName='" . $_POST['foodname'] . "'";
			
			// Error catch
			if (!mysql_query($sql, $con))
			{
				die('Error: ' . mysql_error());
			}
			
			echo "<img src=\"thumbsup.jpg\" class=\"round\"><br/><br/>";
			echo "Removed " . $_POST['foodname'] . " from the Foods table.";
			
		}
	else // There is no instance of item in table. Cannot remove.
		{
			echo "There is no instance of " . $_POST['foodname'] . " in the table to remove. Sorry.";
		}

}

// CLEARING all of the stored OrderCount values and setting them to 0
else if ($_POST['action']=='clearfoods' || $_POST['action']=='clearorders') {
	
	if ($_POST['action']=='clearfoods') { $sql="UPDATE Foods SET OrderCount=0"; }
	else { $sql="UPDATE Orders SET OrderCount=0"; }
	
	// Error catch
		if (!mysql_query($sql, $con))
		{
			die('Error: ' . mysql_error());
		}
		
}


// Close server connection
mysql_close($con);
?>

<br /><br />

<!-- Goes back to menu edit page -->
<a href="menuedit.php">Go Back</a>

</body>
</html>