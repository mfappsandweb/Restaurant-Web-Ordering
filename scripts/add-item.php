<?php
    session_start();
    require_once 'functions.php';
    extract($_POST);
    $conn = sqlConnect();
    //Get max ID
    $id = getMaxId('menu_items');
    //Replace useless chars
    str_replace("'","",$itemName);
    str_replace("$","",$priceIn);
    str_replace("$","",$priceOut);
    strtolower($category);
    //Insert item
    $sql = "INSERT INTO menu_items VALUES($id,'$itemName','$itemOption',$priceIn,$priceOut,'$category',NULL);";
    if(mysqli_query($conn,$sql)) headerLocation('admin.php');
    else headerLocation('admin.php?error=add-item');
?>
