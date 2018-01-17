<?php
    require_once ABSPATH . "vendor/fpdf181/fpdf.php";
    require_once ABSPATH . "vendor/fpdf181/font/helveticab.php";
    //Get correct price in/out
    function getPriceMode()
    {
        if($_SESSION["mode"] == 'Dine-in') $price = "price_in";
        else $price = "price_out";
        return $price;
    };

    // Get price for item by name & meat
    function getItemPrice($name,$meat)
    {
        //Get correct price in/out
        $priceMode = getPriceMode();
        // Make SQL Connection
        $conn = sqlConnect();
        // Select price for item/meat combination
        $sql = "SELECT $priceMode from menu_items WHERE name=\"$name\" AND meat=\"$meat\";";
        if(!mysqli_query($conn,$sql)) echo("index.php?category=other&error=order_file");
        else $result = mysqli_query($conn,$sql);
        //While there's an option store in variable
        while($row = mysqli_fetch_assoc($result)) $price = $row["$priceMode"];
        mysqli_close($conn);
        //Return varible
        return $price;
    };

    //Save order item-quantities to SQL
    function saveOrderItemQuantities()
    {
        $conn = sqlConnect();
        $id = getMaxId('menu_item_order_quantity');
        $items = $_SESSION['order'];
        foreach($items as $itemID => $quantity)
        {
            $sql = "INSERT INTO menu_item_order_quantity VALUES($id,$itemID,$quantity)";
            if(!mysqli_query($conn,$sql)) echo("index.php?category=other&error=order_file");
            $idArray[] = $id;
            $id++;
        }
        mysqli_close($conn);
        return $idArray;
    };

    //Submit order info with SQL
    function submitCurrentOrder($price)
    {
        //Connect to DB
        $conn = sqlConnect();

        //Save items to array
        $idArray = saveOrderItemQuantities();

        //Get order ID
        $id = getMaxId('orders');

        //Set order info
        $user = $_SESSION['username'];
        $mode = $_SESSION['mode'];
        if(!isset($_SESSION['delivCost']) || $_SESSION['delivCost'] == NULL) $delivCost = 0;
        else $delivCost = $_SESSION['delivCost'];
        $dateNow = getDateNow();
        $customerID = $_SESSION['customer_info'];
        $itemQuantityID = implode(",",$idArray);

        //Save order to DB
        if(isset($customerID))
        {
            $sql = "INSERT INTO orders VALUES($id,'$itemQuantityID',$price,$delivCost,'$mode','$user','$dateNow',$customerID);";
            if(!mysqli_query($conn,$sql)) echo("index.php?category=other&error=order_file");
            else {
                makeOrderFile($id,$customerID,$mode);
                unset($_SESSION['order']);
                unset($_SESSION['customer_info']);
                unset($_SESSION['mode']);
            }
            mysqli_close($conn);
            echo("print-order.php?id=$id");
        }

        //If customer info not set, redirect
        else echo("index.php?category=other&error=customer_info");
    };

    //Create order file for printing
    function makeOrderFile($id,$customerID,$mode)
    {
        //Set file location
        $file = ABSPATH . 'orders/order'.$id.'.pdf';

        //Create PDF order file
        $pdf = new FPDF();
        $pdf->SetAuthor("MF Apps and Web Restaurant Order App");
        $pdf->SetTitle("Order $id");
        $pdf->SetFont("Helvetica","",14);
        $pdf->AddPage("P");
        $pdf->SetDisplayMode("real");
        //Create text file
        //$handle = fopen($file,'a+') or die('index.php?category=other&error=order_file');

        //Print title
        $data = "Order #$id\n\n";

        //Connect to DB
        $conn = sqlConnect();
        //Get items
        $items = $_SESSION['order'];
        //For each item in order get info
        foreach($items as $itemID => $quantity)
        {
            $sql = "SELECT name,meat FROM menu_items WHERE ID = $itemID;";
            $result = mysqli_query($conn,$sql);
            while($row = mysqli_fetch_assoc($result))
            {
                $name = $row['name'];
                $type = $row['meat'];
            }
            //Set data to print for items
            switch ($name) {
                case "Value Pack A":
                    $data .= "VALUE PACK A --\n    Spring Roll x 4\n    Chicken Cashew - Chicken x 1\n    Jasmine Rice - Sml x 1\n    Can Drink x 1\n";
                    break;
                case "Value Pack B":
                    $data .= "VALUE PACK B --\n    Spring Roll x 2\n    Curry Puff x 2\n    Pork Pad Ginger - Pork x 1\n    Chicken Green Curry - Chicken x 1\n    Jasmine Rice - Lrg x 1\n    Can Drink x 2\n";
                    break;
                case "Value Pack C":
                    $data .= "VALUE PACK C --\n    Golden Bag x 1\n    Spring Roll x 1\n    Chicken Pa Thai - Chicken x 1\n    Beef Mussamun Curry - Beef x 1\n    Pork Pad Garlic & Pepper - Pork x 1\n    Jasmine Rice - Sml x 2\n    Coke - 1.25L x 1\n";
                    break;
                case "Banquet A":
                    $data .= "BANQUET A --\n    Spring Roll x 1\n    Fish Cake -Fish x 1\n    Chicken Satay - Chicken x 1\n    Curry Puff x 1\n    Chicken Green Curry - Chicken x 1\n    Thai Beef Salad - Beef x 1\n    Crispy Prawn Sweet Chilli - Prawn x 1\n    Pork Pad Cashew Nut - Pork x 1\n    Jasmine Rice x 1\n";
                    break;
                case "Banquet B":
                    $data .= "BANQUET B --\n    Coconut Prawn - Prawn x 1\n    Fish Cake - Fish x 1\n    Spring Roll x 1\n    Golden Bag x 1\n    Beef Mussamun Curry - Beef x 1\n    Crispy Duck - Duck x 1\n    Crispy Prawn Basil - Prawn x 1\n    Chicken Pad Thai - Chicken x 1\n    Jasmine Rice x 1\n";
                    break;
                case "Banquet C":
                    $data .= "BANQUET C --\n    Free Entree per person\n    Whole Fish Ginger - Fish x 1\n    Roasted Red Duck Curry - Duck x 1\n    Crispy Prawn Tamarind - Prawn x 1\n    Calamari Salt & Pepper x 1\n    Jasmine Rice x 1\n";
                    break;
                case "Mix Entrée":
                    $data .= "MIX ENTRÉE --\n    Spring Roll x 1\n    Fish Cake - Fish x 1\n    Curry Puff x 1\n    Satay Chicken - Chicken x 1\n    Coconut Prawn x 1\n";
                    break;
                default:
                    $data .= "$name - $type x $quantity\n";
            }
        }
        //Get customer info
        $sql = "SELECT name,phone,table_num FROM customer_info WHERE ID = $customerID";
        $result = mysqli_query($conn,$sql);
        while($row = mysqli_fetch_assoc($result))
        {
            $name = $row['name'];
            $phone = $row['phone'];
            $table = $row['table_num'];
        }
        //Save customer info to print
        $data .= "\nMode: $mode\nName: $name\nPhone: $phone\nTable: $table";
        //fwrite($handle,$data);
        //fclose($handle);

        //Write data to PDF
        $pdf->Write("6",$data);
        $pdf->Output($file,"F");
    };

    //Add item to current order
    function addToCart($id,$quan)
    {
        if($quan == 0) unset($_SESSION['order'][$id]);
        if(!isset($_SESSION['order'])) $_SESSION['order']=array($id=>$quan);
        else $_SESSION['order'][$id] = $quan;
        return true;
    };

    //Remove item from current order
    function removeFromCart($id)
    {
        // Remove item from order
        unset($_SESSION['order'][$id]);
        if(reset($_SESSION['order']) == NULL)
            unset($_SESSION['order']);
        return true;
    };
?>
