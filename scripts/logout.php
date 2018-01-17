<?php
    session_start();
    unset($_SESSION["username"]);
    unset($_SESSION["adminLevel"]);
    session_destroy();
    echo "Logout successful";
?>
