<?php
    session_start();
    require_once 'functions.php';
    if(!isset($_SESSION['username']) || $_SESSION['username'] != "Arran") headerLocation('index.php');

    //Set account enabled
    extract($_GET);
    if(enableAccount($id)) echo "Account #$id enabled.";
    else echo "Couldn't enable account #$id.";
?>
