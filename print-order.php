<?php
    session_start();
    require_once 'scripts/functions.php';

    // If user not logged in, redirect to login page
    if(!isset($_SESSION["username"])) headerLocation('login.php');

    // Check for URL ID
    if(isset($_GET['id'])) $id = $_GET['id'];
    else headerLocation('index.php?error=order_file');

    // Load header info
    loadHead();
    // Load navigation list
    loadNav();
    // Begin main page content
    beginContent( basename($_SERVER['PHP_SELF']) );
    // Load page content
    echo "<div class='col-lg-12 col-sm-12'><iframe onload='printOrder()' style='width:100%; height:100%;' id='order' name='order' src='orders/order".$id.".pdf'></iframe></div>";
    // Load footer info
    loadFoot();
?>
