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
	else
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
	$time = $row['Time'] . '\n' . $_POST['time'];
	mysql_query("UPDATE Users Set Time=$time WHERE userID='$userID'");
	
	
	
	// === UPDATES ORDERS TABLE === //
	/*
	// Description of user's location (i.e. seat/table/room number)
	$loc = $_POST['seat'];
	$result = mysql_query("SELECT * FROM Orders WHERE Description='$loc'");
	$num_rows = mysql_num_rows($result);

	// If true, there are already instances of the item in the table. Only increase count.
	if ($num_rows)
	{
		while($row = mysql_fetch_array($result))
		{
			if ($row['Description']==$loc)
			{
				// Increments order count by 1, updates value in table
				$count = $row['OrderCount'] + 1;
				mysql_query("UPDATE Orders SET OrderCount=$count WHERE Description='$loc'");
			}
		}
  
	}
	// Otherwise, insert item into table accordingly
	else 	
	{
	$sql="INSERT INTO Orders (OrderCount, Description)
	VALUES (1,'$_POST[seat]')";

	// Error catch
		if (!mysql_query($sql,$con))
		{
			die('Error: ' . mysql_error());
		}
	}
	*/

	// Gets rid of seat variable in session so it's not saved too long
	//unset($_SESSION['seat']); 
	
?>

<div id="content" align="center">

<?php echo $_POST['time']; ?><br /><br />

<h1>Thanks for your order!</h1> <br /><br />

Cheers for your service <?php echo $_POST['titles'] ?>  <?php echo $_POST['lastname'] ?>!<br />

You should recieve a confirmation email shortly,<br />
and your order soon after that.<br /><br />

Provided, you know, the world doesn't end or anything anytime soon.<br /><br />

<br /><br />

<a href="index.php?seat=<?php echo $_SESSION['seat'] ?>">Return Home</a><br /><br />

</div>

</body>
</html>