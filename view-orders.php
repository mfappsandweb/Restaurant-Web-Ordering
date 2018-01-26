<?php
    session_start();
    extract($_POST);
    require_once 'scripts/functions.php';

    //If user not logged in, redirect to login page
    if(!isset($_SESSION["username"]) || !$_SESSION['userEnabled']) headerLocation('login.php');

    //Check URL date
    if(isset($_GET['date'])) $date = $_GET['date'];
    else $date = getDateNow();

    //Check date is a valid date
    if($date != "0000-00-00" && checkValidDate($date))
    {
        // Load head info
        loadHead();
        loadNav();
        beginContent();
        //Open date from URL
        loadPreviousOrders($date);
        loadFoot();
    }
    
    else
    {
        // Load head info
        loadHead();
        loadNav();
        beginContent();
        echo "<div style='width:100vw;height:37em;'><p class='text-center h2'>No Orders Yet</p></div>";
        loadFoot();
    }
?>
