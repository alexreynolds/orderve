<!--

Orderve
Copyright (c) 2013 Alex Reynolds

login.php

    - Page for administrator to log in before proceeding to private controls

    TODO:
    - Style

-->


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Secure Area Login</title>

<!-- Fits view to device screen width -->
<meta name="viewport" content="width=device-width,initial-scale=1">

<!-- Imports fonts from Google Fonts API -->
<link href='http://fonts.googleapis.com/css?family=Economica|Merriweather+Sans:400,300|Maven+Pro:400,700' rel='stylesheet' type='text/css'>

<link rel="stylesheet" type="text/css" href="ordervestyle.css">

</head>

<body>

<?php 

    // Connect to database and begin session
    require("common.php"); 
     
    // Will be used to show submitted username should the login attempt fail
    $submitted_username = ''; 
    
    // Checks to see if form has already been submitted
    // If not, display form, otherwise run login code
    if(!empty($_POST)) 
    { 
        // Retrieves user info based on username
        $query = " 
            SELECT 
                id, 
                username, 
                password, 
                salt, 
                email 
            FROM admins 
            WHERE 
                username = :username 
        "; 
         
        // Parameter values 
        $query_params = array( 
            ':username' => $_POST['username'] 
        ); 
         
        try 
        { 
            $stmt = $db->prepare($query); 
            $result = $stmt->execute($query_params); 
        } 
        catch(PDOException $ex) 
        { 
            die("Failed to run query to find user in database"); 
        } 
         
        // Tracks whether login was successful or not
        $login_ok = false; 
         
        // Get user information from database
        // If result is empty, the user is not registered
        $row = $stmt->fetch(); 
        if($row) 
        { 
        	// Hashes submitted password using salt in database
        	// Compares it to password stored for submitted user
            $check_password = hash('sha256', $_POST['password'] . $row['salt']); 
            for($round = 0; $round < 65536; $round++) 
            { 
                $check_password = hash('sha256', $check_password . $row['salt']); 
            } 
             
            if($check_password === $row['password']) 
            { 
                // If passwords are the same, login successful
                $login_ok = true; 
            } 
        } 
         
        // If login is successful, private content can be shown
        // Else, display an error message and remain at login form
        if($login_ok) 
        { 
            // Removes sensitive information from the session for security's sake
            unset($row['salt']); 
            unset($row['password']); 
             
            // Stores user's data in session
            // Checked whenever accessing a page that is private
            $_SESSION['user'] = $row; 
             
            // Redirects to private page
            header("Location: controls.php"); 
            die("Redirecting to: controls.php"); 
        } 
        else 
        { 
            // Login error message
            print("Your login was unsuccesful, try again or go away."); 
             
            // Re-display username so only the password must be re-entered 
            $submitted_username = htmlentities($_POST['username'], ENT_QUOTES, 'UTF-8'); 
        } 
    } 
     
?> 

<!-- Nav bar with submit order button -->
<nav class="top">
    <table style="width:100%; text-align:center; vertical-align:middle"><tr>
        <td style="width:50px"><a class="back" href="javascript:javascript:history.go(-1)">Back</a></td>
        <td style="padding:0"><span class="head">Orderve</span></td>
        <td style="width:50px;"></td>
    </tr></table>
</nav>

<div id="contentwrapper">

<div class="main" id="logincontent">

<h1>Admins only!</h1>

<h2>Login</h2> 
<form action="login.php" method="post"> 
    Username<br /> 
    <input type="text" name="username" value="<?php echo $submitted_username; ?>" /> 
    <br /><br /> 
    Password<br /> 
    <input type="password" name="password" value="" /> 
    <br /><br /> 
    <input type="submit" value="Login" /> 
</form> 


<!-- End main content div -->
</div>

<!-- End wrapper div -->
</div>

</body>
</html>