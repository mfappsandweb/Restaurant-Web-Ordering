<?php
    session_start();
    extract($_POST);
    require_once 'functions.php';

    //If registering user
    if(isset($regUser))
    {
        if(!sqlExists($regUser,'name','users')) {
            makeUser($regUser,$regPassword,$regBusiness);
            setLoggedIn($regUser);
            echo "Register successful";
        }
    }

    //If logging in
    if(isset($username))
    {
        if(checkUser($password,$username)) {
            setLoggedIn($username);
            echo "Login successful";
        }
        else echo "Login failed";
    }

    //If already logged in
    else if(isset($_SESSION["username"])) echo "Login successful";

    //If POST is empty
    else echo "Login failed";
?>
