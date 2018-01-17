<?php
    session_start();
    extract($_POST);
    require_once 'scripts/functions.php';

    //If logged in, redirect to homepage
    if(isset($_SESSION["username"])) header("Location: index.php");

    //Check for errors
    if(isset($_GET["error"]) && $_GET["error"] == "login-incorrect") printError("Username or password incorrect or doesn't exist");

    //Load page
    loadHead();
    loadNav();
    beginContent();
    loadLogin();
    loadFoot();
?>
