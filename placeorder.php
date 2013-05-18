<!--

Orderve
Copyright (c) 2013 Alex Reynolds

placeorder.php

    - Order confirmation page shown once the user has placed their order

    TODO:
    - Style

-->

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Thanks for Your Order!</title>

<!-- Fits view to device screen width -->
<meta name="viewport" content="width=device-width,initial-scale=1">

<!-- Imports fonts from Google Fonts API -->
<link href='http://fonts.googleapis.com/css?family=Maven+Pro:400,700' rel='stylesheet' type='text/css'>

<link rel="stylesheet" type="text/css" href="ordervestyle.css">

</head>

<body>

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
	
	session_start();

	// === UPDATES PENDING ORDERS TABLE === //

	$fullname = $_POST['titles'] . " " . $_POST['firstname'] . " " . $_POST['lastname'];

	$sql="INSERT INTO pending (Location, Name, Comments, Time)
			VALUES ('$_POST[seat]', '$fullname', '$_POST[comments]', '$_POST[time]')";

	// Error catch
		if (!mysql_query($sql,$con))
		{
			die('Error: ' . mysql_error() . $sql);
		}

	// Gets the assigned orderID from pending (for orders)
	// 	by getting it via the time
	$result = mysql_query("SELECT * FROM pending where Time='$_POST[time]'");
	$row = mysql_fetch_array($result);
	$orderID = $row['orderID'];


	// === UPDATES FOODS AND ORDERS TABLE === //
	
	// The arrays from previous page, containing items ordered and quantities
	$foodArray = $_SESSION['foodArray'];	// Array of food names
	$quanArray = $_SESSION['quanArray'];	// Array of quantities corresponding to prev. array
		
	$result = mysql_query("SELECT * FROM Foods");
	while($row = mysql_fetch_array($result))
  	{
		// Iterate through orderArray to see if any match the current row
		for ($i = 0 ; $i < count($foodArray); $i++)
		{
			$foodname = $foodArray[$i];
			if ($row['FoodName']==$foodname)
			{
				// New OrderCount of item is equal to old count + quantity from new order
				$quantity = $quanArray[$i];
				$count = $row['OrderCount'] + $quanArray[$i];

				// Inserts food order and quantity into Orders table
				$sql="INSERT INTO Orders (orderID, foodID, count)
					VALUES ('$orderID', '$foodname', '$quantity')";

				// Error catch
				if (!mysql_query($sql,$con))
				{
					die('Error: ' . mysql_error() . $sql);
				}
				
				// Updates OrderCount value in Foods table
				mysql_query("UPDATE Foods SET OrderCount=$count WHERE FoodName='$foodname'");
			}
		}
	}


	
	// === UPDATES USERS TABLE ===
	
	// Gets row to update based on user's last name
	$lastname = $_POST['lastname'];
	$result = mysql_query("SELECT * FROM Users WHERE LastName='$lastname'");
	$num_rows = mysql_num_rows($result);
	
	// If there are multiple users in the table with the same last name
	if ($num_rows > 1)
	{
		// Also search using first name
		$firstname = $_POST['firstname'];
		$result = mysql_query("SELECT * FROM Users WHERE LastName='$lastname' AND FirstName='$firstname'");
		$num_rows = mysql_num_rows($result);
		
		// If there are still multiple users, search using email *** LATER CHANGE TO DEVICE ID ***
			if ($num_rows > 1)
			{
				$phone = $_POST['usertel'];
				$result = mysql_query("SELECT * FROM Users WHERE LastName='$lastname' AND FirstName='$firstname' AND Phone='$phone'");
			}
	}
	// Else if user does not yet exist in table, insert
	else if ($num_rows < 1)
	{
	$sql="INSERT INTO Users (Title, FirstName, LastName, Phone, Email, Time, OrderCount)
	VALUES ('$_POST[titles]', '$_POST[firstname]', '$_POST[lastname]', '$_POST[usertel]', '$_POST[usermail]', '$_POST[time]', 1)";

	
	// Error catch
		if (!mysql_query($sql,$con))
		{
			die('Error: ' . mysql_error() . $sql);
		}
	}
	
	// Updates OrderCount in User table
	$row = mysql_fetch_array($result);
	$userID = $row['userID'];
	$count = $row['OrderCount'] + 1;
	mysql_query("UPDATE Users Set OrderCount=$count WHERE userID='$userID'");
	
	// Updates timestamps in User table
	$time = $_POST['time'];
	mysql_query("UPDATE Users Set Time=$time WHERE userID='$userID'");
	
?>

<!-- Nav bar -->
<nav class="top">
	<table style="width:100%; height:100%; text-align:center; vertical-align:center"><tr>
		<td style="width:100px"><a class="back" href="javascript:javascript:history.go(-2)">Back</a></td>
		<td style="padding:0"><span class="head">Orderve</span></td>
		<td style="width:100px;"></td>
	</tr></table>
</nav>


<!-- Content wrapper -->
<div id="contentwrapper">

<!-- Main page content -->
<div class="main" id="orderinfo">

<h1>Cheers <?php echo $_POST['titles'] ?>  <?php echo $_POST['lastname'] ?>!</h1><br /><br />

Stay where you are!<br />
Your order should be coming to you soon.<br /><br />

<span class="desc"><i>(Provided, you know, the world doesn't end or anything anytime soon.</i></span><br /><br />

</div>

</div>

</body>
</html>