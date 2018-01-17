<?php
    extract($_POST);
    require_once 'functions.php';
    // Get price
    $price = getItemPrice($name,$meat);
    // Return price
    echo $price;
?>
