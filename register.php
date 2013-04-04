<!-- Create a new admin account -->
<?php 

    // Connects to database and starts session
    require("common.php"); 
     
    // Checks if registration form has been submitted
    // If yes, run register code. If not, display registration form.
    if(!empty($_POST)) 
    { 
        // Make sure username field is not empty 
        if(empty($_POST['username'])) 
        { 
            die("What is your username?"); 
        } 
         
        // Make sure password field is not empty
        if(empty($_POST['password'])) 
        { 
            die("You didn't enter a password..."); 
        } 
         
        // Make sure the user entered a valid email
        if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) 
        { 
            die("The e-mail you entered isn't real you know."); 
        } 
         
        // Checks to see if the username inputted is already in use or not
        // :username will be replaced later on
        $query = " SELECT 1 FROM admins WHERE username = :username"; 
         
        // Defines value for :username
        // Using this method (tokens/parameters) instead of inserting directly into $query is more secure
        $query_params = array( 
            ':username' => $_POST['username'] 
        ); 
         
        try 
        { 
            // These two statements run the query against your database table. 
            $stmt = $db->prepare($query); 
            $result = $stmt->execute($query_params); 
        } 
        catch(PDOException $ex) 
        { 
            die("Failed to run query for checking new username"); 
        } 
         
        // Returns an array that represents all results matching query
        // Returns false if there are no results
        $row = $stmt->fetch(); 
         
        // If a row was returned, then we know a matching username was found in 
        // the database already and the user must use a different username.
        if($row) 
        { 
            die("This username is already in use"); 
        } 
         
        // Check to ensure that entered email address is unique
        $query = "SELECT 1 FROM admins WHERE email = :email"; 
         
        $query_params = array( 
            ':email' => $_POST['email'] 
        ); 
         
        try 
        { 
            $stmt = $db->prepare($query); 
            $result = $stmt->execute($query_params); 
        } 
        catch(PDOException $ex) 
        { 
            die("Failed to run query for checking new email"); 
        } 
         
        $row = $stmt->fetch(); 
         
        if($row) 
        { 
            die("This email address is already registered"); 
        } 
        
        // Inserts the new user into admins database
        // Tokens (ex :username) used to protect against SQL injection attacks
        $query = " 
            INSERT INTO users ( 
                username, 
                password, 
                salt, 
                email 
            ) VALUES ( 
                :username, 
                :password, 
                :salt, 
                :email 
            ) 
        "; 
         
        // Randomly generates salt to protect against attacks
        // Salt generated is a hex 8 byte salt
        $salt = dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647)); 
         
        // Hashes password with salt
        // Result is a 64 byte string in hex that represents 32 byte sha256 hash of password
        $password = hash('sha256', $_POST['password'] . $salt); 
         
        // Hashed password is then hashed 65536 more times
        // Makes brute force attacks exponentially harder
        for($round = 0; $round < 65536; $round++) 
        { 
            $password = hash('sha256', $password . $salt); 
        } 
        
        // Tokens made for SQL query
        // Note that password is stored in hash form, the salt in normal form
        $query_params = array( 
            ':username' => $_POST['username'], 
            ':password' => $password, 
            ':salt' => $salt, 
            ':email' => $_POST['email'] 
        ); 
         
        try 
        { 
            // Execute query to create the new user 
            $stmt = $db->prepare($query); 
            $result = $stmt->execute($query_params); 
        } 
        catch(PDOException $ex) 
        { 
            die("Failed to run query to create new user"); 
        } 
         
        // Redirects back to login page after registration
        header("Location: login.php"); 
         
        // Stops php script so it doesn't continue to run after redirecting
        die("Redirecting to login.php"); 
    } 
     
?> 