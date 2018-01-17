<?php
    session_start();
    require_once 'functions.php';
    extract($_POST);
    $conn = sqlConnect();
    //Get max ID
    $id = getMaxId('menu_items');
    //Replace useless chars
    str_replace("$","",$priceIn);
    str_replace("$","",$priceOut);
    //Get current data
    $sql = "SELECT category,img FROM menu_items WHERE name = \"$itemName\" LIMIT 1;";
    $result = mysqli_query($conn,$sql);
    while($row = mysqli_fetch_assoc($result)) {
        $category = $row['category'];
        $img = $row['img'];
    }
    //Check for image
    if(!isset($img)) $img = "NULL";
    //Insert item
    $sql = "INSERT INTO menu_items VALUES($id,'$itemName','$itemOption',$priceIn,$priceOut,'$category',$img);";
    if(mysqli_query($conn,$sql)) headerLocation('admin.php');
    else headerLocation('admin.php?error=add-option');
?>
