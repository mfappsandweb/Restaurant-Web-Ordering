<?php
    session_start();
    extract($_POST);
    require_once 'scripts/functions.php';

    //Load page
    loadHead();
    loadNav();
    beginContent();
    echo "
    <div class='col-lg-12' style='margin-top:15px;'>
        <div class='box'>
            <p class='alert alert-warning'>
                <i class='fa fa-exclamation-circle' style='color: black;'></i> Account not yet enabled. Please wait for administrators to enable your account or contact us <a href='mailto:mfappsandweb@gmail.com'>here</a> with your order ID from MF Apps and Web and your restaurant order app username.
            </p>
        </div>
    </div>
    ";

    loadFoot();
?>
