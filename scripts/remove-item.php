<?php
    session_start();
    require_once 'functions.php';
    extract($_POST);
    // Remove item from session order
    if(removeFromCart($id)) echo "Success";
?>
