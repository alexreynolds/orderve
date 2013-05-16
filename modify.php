<!--

Orderve
Copyright (c) 2013 Alex Reynolds

modify.php

    - PHP scripts to insert and remove items from the menu

    TODO:
    - Style

-->


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Insert Items</title>

<!-- Import fonts from Google Fonts -->
<link href='http://fonts.googleapis.com/css?family=Maven+Pro:400,700' rel='stylesheet' type='text/css'>

<link rel="stylesheet" type="text/css" href="ordervestyle.css">

</head>

<body>

<?php

// Establishes a connection with the database
$db = "my_db";
$servername = "localhost";
$username = "user";
$password = "wachtwoord";

$con = mysql_connect($servername,$username,$password);

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }

mysql_select_db($db, $con);

// INSERTING an item into Foods table
if ($_POST['action']=='insert') {
	
	$itemname = $_POST['foodname'];
	$result = mysql_query("SELECT * FROM Foods WHERE foodname='$itemname'");
	$num_rows = mysql_num_rows($result);

	// If true, there are already instances of the item in the table. Do nothing.
	if ($num_rows)
	{
		echo "This item already exists in the menu.";
	} 
	else 	// Otherwise, insert item into table accordingly
	{
		$sql="INSERT INTO Foods (FoodName, FoodPrice, OrderCount, Category, Veg, description)
		VALUES ('$_POST[foodname]','$_POST[foodprice]', 0, '$_POST[category]', '$_POST[veg]', '$_POST[desc]')";

	// Error catch
	if (!mysql_query($sql,$con))
  	{
  		die('Error: ' . mysql_error());
  	}
  
	echo "<img src=\"thumbsup.jpg\" class=\"round\"><br/><br/>";
	echo "Added " . $_POST['foodname'] . " to the menu.";
	}

} 

// REMOVING an item from Foods table
else if ($_POST['action']=='remove') {
	
	// Itemname is the foodID
	$itemname = $_POST['itemname'];
	$result = mysql_query("SELECT * FROM Foods WHERE foodID='$itemname'");
	$num_rows = mysql_num_rows($result);

	// If true, there are already instances of the item in the table. Can be removed.
	if ($num_rows)
		{
				
			$sql="DELETE FROM Foods WHERE foodID='$itemname'";
			
			// Error catch
			if (!mysql_query($sql, $con))
			{
				die('Error: ' . mysql_error());
			}
			
			echo "<img src=\"thumbsup.jpg\" class=\"round\"><br/><br/>";
			echo "Removed item from the menu.";
			
		}
	else // There is no instance of item in table. Cannot remove.
		{
			echo "There is no instance of " . $_POST['itemname'] . " in the menu to remove. Sorry.";
		}

}

// EDITING an item in Foods table
else if ($_POST['action']=='edit') {
	
	// Itemname = foodID
	$itemname = $_POST['itemname'];
	$result = mysql_query("SELECT * FROM Foods WHERE foodID='$itemname'");
	$num_rows = mysql_num_rows($result);

	// Set veg variable
	$veg = "";
	if (!isset($_POST['veg'])) {
		$veg = 0;
	}
	else {
		$veg = 1;
	}

	// If true, there are already instances of the item in the table. Can be edited
	if ($num_rows)
		{
			// If name must be changed
			if (isset($_POST['foodname'])) {
				$newname = $_POST['foodname'];

				if (strlen($newname) > 1) {
					$sql = "UPDATE Foods SET FoodName='$newname' WHERE foodID='$itemname'";

					// Error catch
					if (!mysql_query($sql, $con))
					{
						die('Error: ' . mysql_error());
					}
				}

			}
			// If price must be changed
			if (isset($_POST['foodprice'])) {
				$newprice = $_POST['foodprice'];

				if (strlen($newprice) > 1) {
					$sql = "UPDATE Foods SET FoodPrice='$newprice' WHERE foodID='$itemname'";

					// Error catch
					if (!mysql_query($sql, $con))
					{
						die('Error: ' . mysql_error() . "PRICE: " . $newprice . ".");
					}
				}
			}
			// If category must be changed
			if (isset($_POST['category'])) {
				$newcategory = $_POST['category'];

				if (strlen($newcategory) > 1) {
					$sql = "UPDATE Foods SET Category='$newcategory' WHERE foodID='$itemname'";

					// Error catch
					if (!mysql_query($sql, $con))
					{
						die('Error: ' . mysql_error());
					}
				}
			}
			// If description must be changed
			if (isset($_POST['desc'])) {
				$newdesc = $_POST['desc'];

				if (strlen($newdesc) > 1) {
					$sql = "UPDATE Foods SET description='$newdesc' WHERE foodID='$itemname'";

					// Error catch
					if (!mysql_query($sql, $con))
					{
						die('Error: ' . mysql_error());
					}
				}
			}
			// If veg must be changed
			if (isset($_POST['veg'])) {
				$newveg = $_POST['veg'];

				if (strlen($newveg) > 0) {

					$sql = "UPDATE Foods SET Veg='$newveg' WHERE foodID='$itemname'";

					// Error catch
					if (!mysql_query($sql, $con))
					{
						die('Error: ' . mysql_error());
					}
				}
			}
			
			echo "<img src=\"thumbsup.jpg\" class=\"round\"><br/><br/>";
			echo "Edited menu item.";
			
		}
	else // There is no instance of item in table. Cannot remove.
		{
			echo "There is no instance of chosen item in the menu to edit. Sorry.";
		}

}

// CLEARS all of the stored OrderCount values from ORDERS/FOODS tables (either)
//	Resets the values to 0
else if ($_POST['action']=='clearfoods' || $_POST['action']=='clearorders') {
	
	if ($_POST['action']=='clearfoods') { $sql="UPDATE Foods SET OrderCount=0"; }
	else { $sql="UPDATE Orders SET OrderCount=0"; }
	
	// Error catch
		if (!mysql_query($sql, $con))
		{
			die('Error: ' . mysql_error());
		}
		
}

// REMOVING an item from pending table
// Done when an order has been completed
else if ($_POST['action']=='ordercomplete') {
	
	$ordernum = $_POST['ordernumber'];
	$result = mysql_query("SELECT * FROM pending WHERE orderID='$ordernum'");
	$num_rows = mysql_num_rows($result);

	// If true, there are already instances of the item in the table. Can be removed.
	if ($num_rows)
		{
				
			$sql="DELETE FROM pending WHERE orderID='" . $_POST['ordernumber'] . "'";
			
			// Error catch
			if (!mysql_query($sql, $con))
			{
				die('Error: ' . mysql_error());
			}
			
			echo "<img src=\"thumbsup.jpg\" class=\"round\"><br/><br/>";
			echo "Removed order " . $_POST['ordernumber'] . " from the pending orders list.";
			
		}
	else // There is no instance of item in table. Cannot remove.
		{
			echo "There is no instance of " . $_POST['ordernumber'] . " in the menu to remove. Sorry.";
		}

}


// Close server connection
mysql_close($con);
?>

<br /><br />

<!-- Goes back to menu edit page -->
<a href="menuedit.php">Back to menu edit</a>

</body>
</html>