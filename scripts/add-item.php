<?php
    session_start();
    require_once 'functions.php';
    extract($_POST);

    //Connect to DB
    $conn = sqlConnect();

    //Get max ID
    $id = getMaxId('menu_items');

    //Replace useless chars
    str_replace("'","",$itemName);
    str_replace("$","",$priceIn);
    str_replace("$","",$priceOut);
    str_replace(" ","-",$category);
    strtolower($category);

    //Get business ID 
    $business = $_SESSION['businessID'];

    //Insert item
    $sql = "INSERT INTO menu_items 
            VALUES($id,'$itemName','$itemOption',$priceIn,$priceOut,'$category',NULL,$business);
            ";

    //echo result
    if(mysqli_query($conn,$sql)) echo "Item added";
    else echo "Error adding item";
?>
