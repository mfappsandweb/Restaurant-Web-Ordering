<?php
    require_once dirname(dirname(__FILE__)) . '/scripts/functions.php';
    $conn = sqlConnect();
    $sql = "UPDATE customer_info SET card=NULL;";
    if(!mysqli_query($conn,$sql)) break;
?>
