<?php
    require_once "functions.php";
    session_start();
    extract($_POST);

    //Check username doesn't exist already 
    if( !sqlExists($regUser,"name","users") ) {

        //Connect to DB
        $conn = sqlConnect();

        //Get user ID
        $id = getMaxId("users");

        //Seure password
        $pass = hashPass($regPassword);

        //Get business ID
        $businessID = $_SESSION['businessID'];

        //Build SQL query
        $sql = "INSERT INTO users(ID,name,password,business,admin_level,enabled)
                VALUES($id,'$regUser','$pass',$businessID,0,1);
                ";

        //Echo SQL query success
        if( mysqli_query($conn,$sql) ) echo "Employee added";
        else echo "Employee add failed";

        //Close DB connection
        mysqli_close($conn);

    }
    else {
        echo "Employee add failed";
    }
?>