<!--

Orderve
Copyright (c) 2013 Alex Reynolds

logout.php

    - PHP script to log out the current administrator

-->


<?php 

    // Connects to database, begins session
    require("common.php"); 
     
    // Removes user's data from session
    unset($_SESSION['user']); 
     
    // Redirects to login page 
    header("Location: login.php"); 
    die("Redirecting to: login.php");