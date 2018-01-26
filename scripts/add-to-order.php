<?php
    session_start();
    require_once 'functions.php';
    extract($_POST);

    //If customer dining mode is posted, process customer info
    if(isset($mode))
    {
        //Connect to Database
        $conn = sqlConnect();

        //Get next ID
        $id = getMaxId('customer_info');

        //Save eating mode for order
        $_SESSION['mode'] = $mode;

        //Set delivery cost
        if(!isset($delivCost)) $delivCost = 0;
        $_SESSION['delivCost'] = $delivCost;

        //Check customer info
        if(!isset($customerName) || $customerName == "") $customerName = "NULL";
        if(!isset($customerPhoneNumber) || $customerPhoneNumber == "") $customerPhoneNumber = "NULL";
        if(!isset($customerAddress) || $customerAddress == "") $customerAddress = "NULL";
        if(!isset($cardNumber) || $cardNumber == "") $cardNumber = NULL;
        if(!isset($tableNumber) || $tableNumber == "") $tableNumber = "NULL";

        //Build SQL query
        $sql = "INSERT INTO customer_info VALUES($id,\"$customerName\",\"$customerPhoneNumber\",\"$customerAddress\",\"$cardNumber\",$tableNumber);";

        //Check SQL query status
        if(mysqli_query($conn,$sql)) {
            mysqli_close($conn);
            $_SESSION['customer_info']=$id;
            echo('Customer info saved');
        }
        else {
            mysqli_close($conn);
            echo("Could not save customer info.");
        }
    }

    //If food quantity is posted, handle food order
    else if(isset($quantity))
    {
        //Connect to database
        $conn = sqlConnect();

        //Get ID based on name/meat
        if(isset($meat)) $sql = "SELECT ID FROM menu_items WHERE name=\"$name\" AND meat=\"$meat\";";
        else $sql = "SELECT ID FROM menu_items WHERE name=\"$name\";";
        $result = mysqli_query($conn,$sql);
        
        // Get ID and send for adding to cart
        while($row  = mysqli_fetch_assoc($result)) $id = $row['ID'];
        if(addToCart($id,$quantity))
            echo "Added successfully";
    }

    else echo($url);
?>
