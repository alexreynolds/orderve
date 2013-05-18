<!--

Orderve
Copyright (c) 2013 Alex Reynolds

controls.php

    - A menu to the different pages for menu/user list customization for the web admin
    - Has the option to add new admins to the administrator list
    - A private page

    TODO:
    - Style

-->


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Master Controls</title>

<!-- Fits view to device screen width -->
<meta name="viewport" content="width=device-width,initial-scale=1">

<!-- Imports fonts from Google Fonts API -->
<link href='http://fonts.googleapis.com/css?family=Maven+Pro:400,700' rel='stylesheet' type='text/css'>

<link rel="stylesheet" type="text/css" href="ordervestyle.css">


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

<!-- Nav bar for control panel-->
<nav id="navside">
    <span class="head" style="color:#ABE52C;">Control Panel</span>

    <br><br>

    <ul>
        <li><a href="pending.php" style="font-weight: 700; font-size: 1.2em;">Pending Orders</a></li>
        <li><a href="menuedit.php">Edit Menu</a></li>
        <li><a href="createqr.php">Generate QR</a></li>
        <li><a href="analytics.php">Order Analytics</a></li>
        <li class="selected"><a href="controls.php">Controls Main</a></li>

        <br><br>

        <li><a href="index.php">Back to Home</a></li>
        <li><a href="logout.php">Log out</a></li>
    </ul>
</nav>

<nav id="navtop" class="top">

    <ul>
        <li><a href="pending.php" style="font-weight: 700; font-size: 1.2em;">Pending Orders</a></li>
        <li><a href="menuedit.php">Edit Menu</a></li>
        <li><a href="createqr.php">Generate QR</a></li>
        <li><a href="analytics.php">Order Analytics</a></li>
        <li class="selected"><a href="controls.php">Controls Main</a></li>

        <li><a href="index.php">Back to Home</a></li>
        <li><a href="logout.php">Log out</a></li>
    </ul>
</nav>

<div id="#mainwrapper">

<div class="controlsmain">

<img src="logo.png" class="logo"><h1>Master Controls</h1>

<table id="tablecontrols">
    <tr>
        <td id="greeting">
            <h2>Hiya <i><?php echo htmlentities($_SESSION['user']['username'], ENT_QUOTES, 'UTF-8'); ?></i>.</h2>
            Welcome to God Mode!<br /><br />
            <h2>So what now?</h2>
            From here, you can use the navigation 
            to the left to direct you to places to edit 
            your menu settings and oversee operations.
            <br><br>
            Or, you can use the form on the left 
            to add a <i>new</i> administrator to 
            do all of your dirty work for you.
        </td>

        <td id="addadmin">
            <h2>Add new administrator</h2> 
            <form action="register.php" method="post"> 
                Username<br /> 
                <input type="text" name="username" value="" /> 
                <br /><br /> 
                E-Mail<br /> 
                <input type="text" name="email" value="" /> 
                <br /><br /> 
                Password<br /> 
                <input type="password" name="password" value="" /> 
                <br /><br /> 
                Re-enter password<br />
                <input type="password" name="password2" value="" />
                <br /><br />
                <input type="submit" value="Register" /> 
            </form><br />
        </td>


<!-- End main div -->
</div>

<!-- End main wrapper -->
</div>

</body>
</html>