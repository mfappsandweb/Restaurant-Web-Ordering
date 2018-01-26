<?php
    session_start();
    require_once 'functions.php';
    extract($_POST);
    //If mode isn't saved already, redirect with warning
    if(!isset($_SESSION['mode']))  echo('index.php?category=other&error=dining_not_set');
    else submitCurrentOrder($price);
 ?>
