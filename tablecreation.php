<!-- Author: Alex Reynolds, 2013 
	Connects to database server, creates food database -->

<?php

// mysql_connect(servername, username, password) ** all inputs optional
// servername: specifies server to connect to. default "localhost:3306"
// username: specifies username to log in with. default is name of user that owns server
// password: specifies password to log in with. default is ""

$servername = "localhost";
$username = "user";
$password = "wachtwoord";
$dbname = "my_db";

$con = mysql_connect($servername, $username, $password);
// If the server cannot connect, error.
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }

// Creates food table in database
// Table is arrnaged by food id
mysql_select_db($dbname, $con);
$sql = "CREATE TABLE Foods
(
foodID int NOT NULL AUTO_INCREMENT,
PRIMARY KEY(foodID),
FoodName varchar(30),
FoodPrice double,
OrderCount int,
ImageURL varchar(30),
Veg tinyint(1)
)";

// Executes Foods table creation
mysql_query($sql,$con);

// Creates order history table in database
// Table is arranged by seat number
// ** For now, just has how many times they ordered something
// ** Should be ordered by customer name/number, have more detailed order history

mysql_select_db($dbname, $con);
$sql = "CREATE TABLE Orders
(
seatID int NOT NULL AUTO_INCREMENT,
PRIMARY KEY(seatID),
OrderCount int,
Description varchar(30)
)";

// Executes Orders table creation
mysql_query($sql,$con);

// Creates order history table in database
// Table is arranged by seat number

mysql_select_db($dbname, $con);
$sql = "CREATE TABLE Users
(
userID int NOT NULL AUTO_INCREMENT,
PRIMARY KEY(userID),
Title varchar(6),
FirstName varchar(15),
LastName varchar(20),
Phone varchar(20),
Email varchar(25),
Time varchar(100),
OrderCount int
)";

// Executes Users table creation
mysql_query($sql,$con);

// Creates an admin table in database
// Table contains a list of people that can modify menu/view analytics

mysql_select_db($dbname, $con);
$sql = "CREATE TABLE admins
(
  id int(11) NOT NULL AUTO_INCREMENT,
  username varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  password char(64) COLLATE utf8_unicode_ci NOT NULL,
  salt char(16) COLLATE utf8_unicode_ci NOT NULL,
  email varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY username (username),
  UNIQUE KEY email (email)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1";

// Executes admins table creation
mysql_query($sql, $con);


// Creates a pending table in database
// Table contains a list of orders that have yet to be filled

mysql_select_db($dbname, $con);
$sql = "CREATE TABLE pending
(
  orderID int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY(orderID),
  Location varchar(20),
  Name varchar(50),
  Comments varchar(250),
  Time time
  )";

// Executes pending table creation
mysql_query($sql, $con);

// connection closes when script ends
mysql_close($con);
?>