<?php
    session_start();
    require_once 'functions.php';
    extract($_POST);

    //Connect to DB
    $conn = sqlConnect();

    //Build query
    $sql = "UPDATE users 
            SET admin_level = 10 
            WHERE name = '$accountName';
            ";

    //Return result
    if(mysqli_query($conn,$sql)) echo "User account admin";
    else echo "Error making user admin";
?>
