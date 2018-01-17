<?php
    session_start();
    require_once 'functions.php';
    extract($_POST);
    $conn = sqlConnect();
    $sql = "UPDATE users SET admin_level = 10 WHERE name = $accountName";
    if(mysqli_query($conn,$sql)) headerLocation('admin.php');
    else headerLocation('admin.php?error=make-admin-error');
?>
