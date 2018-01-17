<?php
    session_start();
    require_once 'functions.php';
    extract($_POST);
    submitCurrentOrder($price);
 ?>
