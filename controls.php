<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>GOD MODE</title>


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

<h2>MASTER CONTROLS</h2><br />

Hello <?php echo htmlentities($_SESSION['user']['username'], ENT_QUOTES, 'UTF-8'); ?>, welcome to God Mode!<br /><br />

<a href="pending.php">PENDING ORDERS</a><br /><br />
<a href="menuedit.php">Edit the menu</a><br />
<a href="createqr.php">Create QR codes</a><br />
<a href="analytics.php">Order Analytics</a><br />

<h1>Add a New Admin</h1> 
<form action="register.php" method="post"> 
    Username:<br /> 
    <input type="text" name="username" value="" /> 
    <br /><br /> 
    E-Mail:<br /> 
    <input type="text" name="email" value="" /> 
    <br /><br /> 
    Password:<br /> 
    <input type="password" name="password" value="" /> 
    <br /><br /> 
    Re-enter password:<br />
    <input type="password" name="password2" value="" />
    <br /><br />
    <input type="submit" value="Register" /> 
</form><br />


<a href="logout.php">Log out</a><br />

<br /><br />
<script>
    document.write('<a href="' + document.referrer + '">Go Back</a>');
</script>

</body>
</html>