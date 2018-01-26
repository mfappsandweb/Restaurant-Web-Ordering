<?php
    session_start();
    require_once 'scripts/functions.php';
    extract($_POST);

    //If user not logged in, redirect to login page
    if(!isset($_SESSION["username"]) || !$_SESSION['userEnabled']) headerLocation('login.php');

    //If catagory set in URL load catagory, otherwise load special
    if(isset($_GET["category"])) $category = $_GET["category"];
    else $category = "special";

    // Load header info
    loadHead();
    // Load navigation list
    loadNav();
    // Begin main page content
    beginContent();
    // Load menu category list and current order list
    loadCategories();
    // Load menu items from category
    loadMenu($category,$_SESSION['businessID']);

    //Check for errors
    if(isset($_GET["error"]))
    {
        switch($_GET["error"]) {
            case "customer_info":
                printError("Add table number or customer info before submitting order");
                break;
            case "order_file":
                printError("Order could not be printed");
                break;
            case "dining_not_set":
                printError("Set customer dining before adding menu items");
                break;
            case "admin_level":
                printError("You must be an admin to access the admin page");
                break;
            }
    }

    // Load footer info
    loadFoot();
?>
