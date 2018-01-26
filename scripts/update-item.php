<?php
    session_start();
    require_once 'functions.php';
    extract($_POST);

    //Connect to DB
    $conn = sqlConnect();

    //Get max ID
    $id = getMaxId('menu_items');

    //Replace useless chars
    str_replace("$","",$priceIn);
    str_replace("$","",$priceOut);

    //Get current data
    $sql = "SELECT category,img,business 
            FROM menu_items 
            WHERE name = \"$itemName\" 
            LIMIT 1;
            ";

    $result = mysqli_query($conn,$sql);

    while($row = mysqli_fetch_assoc($result)) {
        $category = $row['category'];
        $img = $row['img'];
        $business = $row['business'];
    }

    //Check for image
    if(!isset($img)) $img = "NULL";

    //Insert item
    $sql = "INSERT INTO menu_items 
            VALUES($id,'$itemName','$itemOption',$priceIn,$priceOut,'$category',$img,$business);";

    if(mysqli_query($conn,$sql)) echo "Item updated";
    else echo "Item update failed";
?>
