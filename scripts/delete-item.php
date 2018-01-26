<?php
    require_once "functions.php";
    extract($_POST);

    //Connect to DB
    $conn = sqlconnect();

    //Build SQL
    $sql = "DELETE FROM menu_items
            WHERE name = '$itemName';
            ";

    if(mysqli_query($conn,$sql)) echo "Item deleted";
    else echo "Item delete failed";
?>