<!--

Orderve
Copyright (c) 2013 Alex Reynolds

common.php

    - Used to establish a connection to the mySQL database
    - Called on secure pages

-->

<?php 

    // Database connection information
    $username = "user"; 
    $password = "wachtwoord"; 
    $host = "localhost"; 
    $dbname = "my_db"; 

    // Set database encoding as UTF8
    $options = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'); 
     
    try 
    { 
        // This statement opens a connection to your database using the PDO library 
        // PDO is designed to provide a flexible interface between PHP and many 
        // different types of database servers.  For more information on PDO: 
        // http://us2.php.net/manual/en/class.pdo.php 
        $db = new PDO("mysql:host={$host};dbname={$dbname};charset=utf8", $username, $password, $options); 
    } 
    catch(PDOException $ex) 
    { 
        // If connection fails, error
        die("Failed to connect to the database: "); 
    } 
     
    // Configures PDO to throw exception when an error is encountered
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
     
    // Configures PDO to return rows from database using associative arrays
    //  Arrays have string indexes that represent column in database
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); 
     
    // Undo magic quotes -- magic quotes removed from PHP as of v.5.4
    //  Prevents problems if older versions of PHP are used
    if(function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc()) 
    { 
        function undo_magic_quotes_gpc(&$array) 
        { 
            foreach($array as &$value) 
            { 
                if(is_array($value)) 
                { 
                    undo_magic_quotes_gpc($value); 
                } 
                else 
                { 
                    $value = stripslashes($value); 
                } 
            } 
        } 
     
        undo_magic_quotes_gpc($_POST); 
        undo_magic_quotes_gpc($_GET); 
        undo_magic_quotes_gpc($_COOKIE); 
    } 
     
    // Tells browser UTF-8 is used and it should respond with the same
    header('Content-Type: text/html; charset=utf-8'); 
     
    // Begin session
    session_start(); 

    // It is a good practice to NOT end your PHP files with a closing PHP tag. 
    // This prevents trailing newlines on the file from being included in your output, 
    // which can cause problems with redirecting users.