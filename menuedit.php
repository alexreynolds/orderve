<!--

Orderve
Copyright (c) 2013 Alex Reynolds

menuedit.php

    - Page for the administrator to edit current menu offerings
    - A private page

    TODO:
    - Style

-->


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Edit Menu</title>
</head>

<!-- Fits view to device screen width -->
<meta name="viewport" content="width=device-width,initial-scale=1">

<!-- Imports fonts from Google Fonts API -->
<link href='http://fonts.googleapis.com/css?family=Economica|Merriweather+Sans:400,300|Maven+Pro:400,700' rel='stylesheet' type='text/css'>

<link rel="stylesheet" type="text/css" href="ordervestyle.css">

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

  // Gets the chosen food category from the drop down list
  function getCategory()
  {
    var category = document.getElementById("category");
    document.getElementById("category").value = category.options[category.selectedIndex].value;
    return document.getElementById("category").value;
  }

  // Gets the chosen food name from the drop down list
  function getName()
  {
    var name = document.getElementById("itemname");
    document.getElementById("itemname").value = name.options[name.selectedIndex].value;
    return document.getElementById("itemname").value;
  }
	
</script>

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
        <li class="selected"><a href="menuedit.php">Edit Menu</a></li>
        <li><a href="createqr.php">Generate QR</a></li>
        <li><a href="analytics.php">Order Analytics</a></li>
        <li><a href="controls.php">Controls Main</a></li>

        <br><br>
        
        <li><a href="index.php">Back to Home</a></li>
        <li><a href="logout.php">Log out</a></li>
    </ul>
</nav>

<div id="mainwrapper">

<div class="controlsmain" id="menumain">

<h1>Edit Menu</h1>

<h2>Current Menu</h2>

<table id="menutable">
<tr>
  <th id="foodname">Item Name</th>
  <th id="price">Price</th>
  <th id="count">Order Count</th>
  <th id="cat">Category</th>
  <th id="veg">Vegetarian?</th>
</tr>

<!-- Print out contents of menu from database -->
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

// PRINT OUT FOODS TABLE //

// Gets items from table in alphabetical order
$result = mysql_query("SELECT * FROM Foods ORDER BY FoodName ASC");


// Iterates through array of row results
while($row = mysql_fetch_array($result))
  {
    echo "<tr>";
    echo "<td class=\"foodname\">" . $row['FoodName'] . "</td>"; 
    echo "<td>$" . number_format($row['FoodPrice'], 2) . "</td>";
    echo "<td>" . $row['OrderCount'] . "</td>";
    //echo "<td><img src=\"" . $row['ImageURL'] . "\" class=\"round\"><br /><br />"  . $row['ImageURL'] . "</td>";
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
echo "</table>";

?>

<!--
Allows establishment to edit menu via a form.
Form data is sent to modify.php
-->
<table id="editmenucontrols">
<tr>

<td>  

  <h2>Add item</h2>
  <span>Add a new item to the menu.<br><br></span>
  <form action="modify.php" method="post">
  Item name<br />
  <input type="text" name="foodname" maxlength="30"><br /><br />
  Price <i>(in dollars)</i><br />
  <input type="text" name="foodprice" placeholder="ex. 10.50" maxlength="5"><br /><br />
  Category<br />
  <select name="category" id="category" onChange="getCategory()" required>
  <option value="select" disabled>Select a type</option>
  <option value="drink">Drink</option>
  <option value="appetizer">Appetizer</option>
  <option value="salad">Salad</option>
  <option value="main">Main</option>
  <option value="dessert">Dessert</option>
  </select>
  <br /><br />
  Vegetarian?<br>
  <input type="checkbox" id="veg" name="veg" value="1"> Yes<br />
  Description
  <input type="text" id="desc" name="desc" maxlength="250"><br />

  <br /><br />

  <input type="submit" value="Add item"><br />
  <input type="hidden" name="action" value="insert" />
  </form>

</td>
<td style="border-left: solid 1px #BBBBBB;">

  <h2>Remove item</h2>
  <span>Remove an existing item from the menu. 
    This action is irreversible.<br><br></span>
  <form action="modify.php" method="post">
  Item name<br />
  <select name="itemname" id="itemname" onChange="getName()" required>
  <option value="select" disabled>Select the item</option>
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

    // LIST ITEMS IN FOODS TABLE AS OPTIONS

    // Gets items from table in alphabetical order
    $result = mysql_query("SELECT * FROM Foods ORDER BY FoodName ASC");

    // Iterates through array of row results
    while($row = mysql_fetch_array($result))
      {
        echo "<option value=\"" . $row['foodID'] . "\">" . $row['FoodName'] . "</option>";
      }
  ?>
  </select><br /><br />
  <input type="submit" value="Remove item"> <br />
  <input type="hidden" name="action" value="remove" />
  </form>

</td>

<td style="border-left: solid 1px #BBBBBB;">

  <h2>Edit item</h2>
  <span>Edit an existing item in the menu. 
    Enter only new values.<br><br>
    (If not vegetarian, submit without box checked.)<br><br></span>
  <form action="modify.php" method="post">
  Item name<br />
  <select name="itemname" id="itemname" onChange="getName()" required>
  <option value="select" disabled>Select the item</option>
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

    // LIST ITEMS IN FOODS TABLE AS OPTIONS

    // Gets items from table in alphabetical order
    $result = mysql_query("SELECT * FROM Foods ORDER BY FoodName ASC");

    // Iterates through array of row results
    while($row = mysql_fetch_array($result))
      {
        echo "<option value=\"" . $row['foodID'] . "\">" . $row['FoodName'] . "</option>";
      }
  ?>
  </select><br /><br />
  New item name<br />
  <input type="text" name="foodname" maxlength="30"><br /><br />
  New price <i>(in dollars)</i><br />
  <input type="text" name="foodprice" placeholder="ex. 10.00" maxlength="5"><br /><br />
  Category<br />
  <select name="category" id="category" onChange="getCategory()" required>
  <option value="select" disabled>Select a type</option>
  <option value="drink">Drink</option>
  <option value="appetizer">Appetizer</option>
  <option value="salad">Salad</option>
  <option value="main">Main</option>
  <option value="dessert">Dessert</option>
  </select>
  <br /><br />
  Vegetarian?<br>
  <input type="checkbox" id="veg" name="veg" value="1"> Yes<br /><br />
  Description
  <input type="text" id="desc" name="desc" maxlength="250"><br />

  <br /><br />

  <input type="submit" value="Edit item"><br />
  <input type="hidden" name="action" value="edit" />
  </form>

</tr><table>

<!-- End of content div -->
</div>

<!-- End of wrapper div -->
</div>

</body>
</html>