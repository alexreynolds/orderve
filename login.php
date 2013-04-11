<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Secure Area Login</title>
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

<h1>YOU MUST BE SPECIAL TO ACCESS THIS AREA</h1><br /><br />

<h2>Login</h2> 
<form action="login.php" method="post"> 
    Username:<br /> 
    <input type="text" name="username" value="<?php echo $submitted_username; ?>" /> 
    <br /><br /> 
    Password:<br /> 
    <input type="password" name="password" value="" /> 
    <br /><br /> 
    <input type="submit" value="Login" /> 
</form> 

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




<br /><br />
<a href="index.php?seat=THRONE">Back to main</a>

</body>
</html>