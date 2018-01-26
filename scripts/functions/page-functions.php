<?php
//Load header info
function loadHead()
{
    echo '<!DOCTYPE html>
    <html lang="en">
      <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">
        <title>Restaurant Ordering App</title>
        <!-- Bootstrap core CSS -->
        <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <!-- Font Awesome CSS -->
        <link href="vendor/fortawesome/font-awesome/css/font-awesome.min.css" rel="stylesheet">
        <!-- Custom styles for this template -->
        <link href="css/shop-homepage.css" rel="stylesheet">
        <!-- Bootstrap core JavaScript -->
        <script src="vendor/jquery/jquery.min.js"></script>
        <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="js/functions.js"></script>
        <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
      </head>';
};

//Load navigation bar info
function loadNav()
{
    echo "<body>
    <!-- Navigation -->
    <nav class='navbar navbar-expand-lg navbar-dark bg-dark fixed-top'>
      <div class='container'>
        <a class='navbar-brand' href='index.php'>Business Ordering Web-app</a>
        <button class='navbar-toggler' type='button' data-toggle='collapse' data-target='#navbarResponsive' aria-controls='navbarResponsive' aria-expanded='false' aria-label='Toggle navigation'>
          <span class='navbar-toggler-icon'></span>
        </button>
        <div class='collapse navbar-collapse' id='navbarResponsive'>
            <ul class='navbar-nav ml-auto'>
            <li class='nav-item' name='index.php'>
              <a class='nav-link' href='index.php'>Make New Order</a>
            </li>
            <li class='nav-item' name='view-orders.php'>
              <a class='nav-link' href='view-orders.php'>View Previous Orders</a>
            </li>
            <li class='nav-item' name='admin.php'>
              <a class='nav-link' href='admin.php'>Admin</a>
            </li>
            <li class='nav-item' name='login.php'>";
                LoginLink();
        echo "</li>
            <li class='nav-item' name='register.php'>
              <a class='nav-link' href='register.php'>Register Business</a>
            </li>
            </ul>
        </div>
      </div>
    </nav>";
};

//Begin page main content
function beginContent($page = NULL)
{
    echo "<!-- Page Content -->
            <div class='container'>
                <div class='row' ";
    switch ($page) {
        case 'print-order.php':
            echo "style='height:100vh;'";
            break;
        default:
            break;
    }
    echo ">";
};

//Load footer info
function loadFoot()
{
    if($_SERVER['PHP_SELF'] == '/restaurant/login.php' || $_SERVER['PHP_SELF'] == '/restaurant/account-not-enabled.php') $pos = 'absolute';
    else $pos = 'relative';
    echo "</div>
        <!-- /.row -->
        </div>
        <!-- Footer -->
        <footer class='py-5 bg-dark' style='position: $pos; right: 0; bottom: 0; left: 0; padding: 1rem; text-align: center;'>
            <div class='container'>
                <p class='m-0 text-center text-white'>Copyright &copy; MF Apps &amp; Web 2017</p>
            </div>
        <!-- /.container -->
        </footer>
        </body>
    </html>";
};

//Load category menu for new order
function loadCategories()
{
    //Get business ID
    $businessID = $_SESSION['businessID'];
    if($businessID == 1) {
        echo "<div class='col-lg-3' style='padding-bottom: 15px;'>
                <h1 class='my-4'>Categories</h1>
                <div class='list-group'>
                    <a href='index.php?category=entree' class='list-group-item'>Entrée</a>
                    <a href='index.php?category=special' class='list-group-item'>Special</a>
                    <a href='index.php?category=soup' class='list-group-item'>Soup</a>
                    <a href='index.php?category=curry' class='list-group-item'>Curry</a>
                    <a href='index.php?category=salad' class='list-group-item'>Salad</a>
                    <a href='index.php?category=stir-fried' class='list-group-item'>Stir fried</a>
                    <a href='index.php?category=whole-fish-barramundi' class='list-group-item'>Fish</a>
                    <a href='index.php?category=noodle-rice' class='list-group-item'>Noodle & Rice</a>
                    <a href='index.php?category=drinks' class='list-group-item'>Drinks & Dessert</a>
                    <a href='index.php?category=value-pack' class='list-group-item'>Value Pack (Takeaway Only)</a>
                    <a href='index.php?category=banquet' class='list-group-item'>Banquet (Dine-in Only)</a>
                    <a href ='index.php?category=other' class='list-group-item'>Other</a>
                </div>";
    }
    else {
        //Connect to DB
        $conn = sqlConnect();

        //Get categories 
        $sql = "SELECT DISTINCT category
                FROM menu_items
                WHERE business = $businessID;
                ";

        $result = mysqli_query($conn,$sql);
        $row = mysqli_fetch_assoc($result);

        //Print category list
        echo "<div class='col-lg-3' style='padding-bottom: 15px;'>
                <h1 class='my-4'>Categories</h1>
                <div class='list-group'>";
        foreach($row['category'] as $c) {
            $title = ucwords( str_replace("-"," ",$c) );
            echo "<a href='index.php?category=$c' class='list-group-item'>$title</a>";
        }
    }

    //Load current order items list
    loadOrderList();
    //End section
    echo "</div>
        <!-- /.col-lg-3 -->";
};

//load login page login.php
function loadLogin()
{
    echo "
    <form action='javascript:void(0)' id='login-form' style='padding-bottom:30px; margin: auto; width: 35%; text-align: center;'>
        <h1 class='my-4' style='margin: auto; width: 50%; text-align: center;'>Login</h1>
        <div class='form-group' style='list-group-item'>
          <label for='username'>Username</label>
          <input type='text' name='username' class='form-control' id='username'>
        </div>
        <div class='form-group' style='list-group-item'>
          <label for='password'>Pin</label>
          <input type='password' name='password' class='form-control' id='password'>
        </div>
        <div class='text-center' style='list-group-item'>
          <button type='submit' class='btn btn-primary'><i class='fa fa-sign-in'></i> Log in</button>
        </div>
    </form>";
};

//Load content for registering new business
function loadRegister()
{
    echo "
    <div class='col-lg-12' style='margin-top:15px;'>
        <div class='box'>
            <p class='alert alert-info'>
                <i class='fa fa-exclamation-circle' style='color: black;'></i> This is to register a new business, if you wish to add an employee to your business open the <a href='admin.php'>admin</a> panel and add a new employee.
            </p>
        </div>
    </div>
    <form action='javascript:void(0)' id='register-form' style='padding-bottom:30px; margin: auto; width: 35%; text-align: center;'>
        <h1 class='my-4' style='margin: auto; width: 50%; text-align: center;'>Register</h1>
        <div class='form-group' style='list-group-item'>
          <label for='regUser'>Username</label>
          <input type='text' name='regUser' class='form-control' id='regUser'>
        </div>
        <div class='form-group' style='list-group-item'>
          <label for='regPassword'>Pin</label>
          <input type='password' name='regPassword' class='form-control' id='regPassword'>
        </div>
        <div class='form-group' style='list-group-item'>
          <label for='regBusiness'>Business</label>
          <input type='text' name='regBusiness' class='form-control' id='regBusiness'>
        </div>
        <div class='text-center' style='list-group-item'>
          <button type='submit' class='btn btn-primary'><i class='fa fa-sign-in'></i> Register</button>
        </div>
    </form>";
};

//Load content for viewing previous orders view-orders.php
function loadPreviousOrders($selectedDate)
{
    //Set business ID 
    $businessID = $_SESSION['businessID'];

    // Begin order list
    echo "<div class='col-lg-9 list-group' style='display:inline-table;max-width:100%;margin-bottom:35px;'>";
    
    // echo date selection dropdown
    if($_SESSION['adminLevel'] == 10) getOrderDates($selectedDate);
    $totalPrice = 0;
    
    //Connect to DB
    $conn = sqlConnect();
    
    //Build SQL query
    $sql = "SELECT id,menu_item_quantities,price,deliv_cost,mode,server,customer_id 
            FROM orders 
            WHERE date = '$selectedDate' AND business = '$businessID';
            ";
    //Get info
    $result = mysqli_query($conn,$sql);
    while($row = mysqli_fetch_assoc($result))
    {
        $id[] = $row['id'];
        $menuItemQuan[] = $row['menu_item_quantities'];
        $price[] = number_format((float)$row['price'],2,'.','');
        $delivPrice[] = number_format((float)$row['deliv_cost'],2,'.','');
        $mode[] = $row['mode'];
        $server[] = $row['server'];
        $customer[] = $row['customer_id'];
    }
    //If previous orders exist
    if(isset($id))
    {
        //Echo orders
        for($i=0;$i<count($id);$i++)
        {
            // New order item
            echo "<div class='list-group-item'>";
            echo "<table style='width:65%'>
                    <tr>
                        <td>
                            <h4>Order Number #$id[$i]</h4>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <h5>Server: $server[$i]</h5>
                        </td>
                        <td>
                            <h5>Location: $mode[$i]</h5>
                        </td>
                    </tr>";
            printPreviousOrderItems($menuItemQuan[$i],$mode[$i]);
            echo "<tr>";
            if(isset($delivPrice[$i])) {
                echo "<td>
                            <h5>Delivery: $$delivPrice[$i]</h5>
                      </td>";
        }

        //Echo order total price
        echo "  <td>
                    <h5>Price: $$price[$i]</h5>
                </td>
            </tr>";

        //Echo customer info
        printPreviousCustomer($customer[$i]);

        // End order item
        echo "</table>";
        echo "</div>";

        //Increase total price
        $totalPrice += $price[$i];
        $totalPrice = number_format((float)$totalPrice,2,'.','');
        }
    }
    // Print day orders total profit
    echo "<div class='list-group-item h4'>Total Sales: $$totalPrice</div>";
    
    // End order list
    echo "</div>";
};

//Print the info for previous customerID
function printPreviousCustomer($id)
{
    //Conect to db
    $conn = sqlConnect();
    //Get info
    $sql = "SELECT name,phone,address,card,table_num FROM customer_info WHERE ID = $id;";
    $result = mysqli_query($conn,$sql);
    while($row = mysqli_fetch_assoc($result))
    {
        $name = $row['name'];
        $phone = $row['phone'];
        $address = $row['address'];
        $card = $row['card'];
        $table = $row['table_num'];
    }
    echo "<tr>
            <td>
                <h5>Customer Info:</h5>
            </td>
         </tr>";
    if($table!=0)
        echo "<tr>
                <td>
                    <div>Table: $table</div>
                </td>
             </tr>";
    if($name!='NULL' && $name!='')
    echo "<tr>
            <td>
                <div>Name: $name</div>
            </td>
         </tr>";
    if($phone!='NULL' && $phone!='')
        echo "<tr>
                <td>
                    <div>Phone: $phone</div>
                </td>
            </tr>";
    if($address!='NULL' && $address!='')
        echo "<tr>
                <td>
                    <div>Address: $address</div>
                </td>
            </tr>";
    if($card!='')
        echo "<tr>
                <td>
                    <div>Card Number: $card</div>
                </td>
            </tr>";
};

//Print the previous items for each order in a list
function printPreviousOrderItems($itemQuan,$mode)
{
    if($mode == "Dine-in") $priceMode = "price_in";
    else $priceMode = "price_out";
    $items = explode(',',$itemQuan);
    $conn = sqlConnect();
    $idQuan = array("key" => "value");
    for($i=0;$i<count($items);$i++)
    {
        $itemID = (int)$items[$i];
        $sql = "SELECT menu_item_id,quantity FROM menu_item_order_quantity WHERE id='$itemID';";
        $result = mysqli_query($conn,$sql);
        while($row = mysqli_fetch_assoc($result))
        {
            $itemID = $row['menu_item_id'];
            $quantity = $row['quantity'];
        }
        $idQuan[$itemID]=$quantity;
    }
    unset($idQuan["key"]);
    foreach($idQuan as $id => $quan)
    {
        $sql = "SELECT name,meat,$priceMode FROM menu_items WHERE id=$id;";
        $result = mysqli_query($conn,$sql);
        while($row = mysqli_fetch_assoc($result))
        {
            $name = $row['name'];
            $meat = $row['meat'];
            $price = $row["$priceMode"];
        }
        $price = number_format((float)$price*$quan,2,'.','');
        if(isset($meat))
            echo "<tr>
                    <td>
                        <div style='margin-bottom:15px;'>
                            $quan x $name - $meat
                            </br>$$price
                        </div>
                    </td>
                </tr>";
        else
            echo "<tr>
                    <td>
                        <div style='margin-bottom:15px;'>
                            $quan x $name
                            </br>$$price
                        </div>
                    </td>
                </tr>";
    }
};

//Return array of dates for existing orders
function getOrderDates($dateValue)
{
    //Set business ID
    $businessID = $_SESSION['businessID'];

    //Connect to DB
    $conn = sqlConnect();

    //Build SQL query
    $sql = "SELECT DISTINCT date 
            FROM orders
            WHERE business = $businessID;
            ";
    
    //Get result
    $result = mysqli_query($conn,$sql);
    while($row = mysqli_fetch_assoc($result)) $date[] = $row['date'];

    // Begin date select list
    echo "<div class='form-group list-group-item' style='margin-top:15px;'>
                <label for='order-date' class='h5'>Order date:&nbsp;</label>
                <select class='form-control' style='width:130px;display:inline;' name='date-control'>";
    
                for($i=0;$i<count($date);$i++)
    {
        if($date[$i]==$dateValue) echo "<option value='$date[$i]' selected>$date[$i]</option>";
        else echo "<option value='$date[$i]'>$date[$i]</option>";
    }

    // End date select
    echo "</select>
        </div>";
};

//Display login/logout link if user logged out/in
function LoginLink()
{
    //If logged in display username and logout option
    if(isset($_SESSION['username'])) {
        $user = $_SESSION['username'];
        echo "<a class='nav-link' href='javascript:void(0)' onClick='logout()'>$user, Logout</a>";
    }
    //If not logged in display login option
    else echo '<a class="nav-link" href="login.php">Login</a>';
};

//Load the selection of meat for items
function loadMeatSelection($name,$meat)
{
    //If item has meat options, diplay meat selection dropdown
    if(isset($meat))
    {
        //Add selection option
        echo "<div class='form-group' style='margin-bottom:0rem;'>
                <label for='meat' style='margin-top:5px;' class='h5'>Options:</label>
                <select name='meat' id='$name' class='form-control'>";
        //Connect to DB
        $conn = sqlConnect();
        //Select all meats for menu item
        $sql="SELECT DISTINCT meat,price_in,price_out FROM menu_items WHERE name=\"$name\";";
        $result = mysqli_query($conn,$sql);
        //While there are more options
        while($row = mysqli_fetch_assoc($result))
        {
            //Add meat as a selection option
            $meat = $row["meat"];
            if($row["price_in"] == 0.00) $meatPrice = "$".$row["price_out"];
            else if($row["price_out"] == 0.00) $meatPrice = "$".$row["price_in"];
            else $meatPrice = "Dine-in $".$row["price_in"]." / Takeaway $".$row["price_out"];
            echo "<option value='$meat'>$meat - $meatPrice</option>";
        }
        echo "  </select>
              </div></br>";
        mysqli_close($conn);
    }
};

//Load item menu
function loadMenu($cat,$business)
{
    echo "<div class='col-lg-9'>
            <div id='carouselExampleIndicators' class='carousel slide my-4' data-ride='carousel'></div>
                <div class='row'>";
    //Get current URL for form use
    $url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    //Connect to DB
    $conn = sqlConnect();
    if($cat != 'other')
    {
        //Select count of columns that equal data to be checked
        $sql = "SELECT id,name,price_in,price_out,meat,img 
                FROM menu_items 
                WHERE category = \"$cat\" AND business = $business
                GROUP BY name;
                ";
        $result = mysqli_query($conn,$sql);
        //Get items and group by name (only distinct names shown)
        if(mysqli_num_rows($result))
        {
            while($row = mysqli_fetch_assoc($result))
            {
                $id[] = $row["id"];
                $name[] = $row["name"];
                $priceIn[] = $row["price_in"];
                $priceOut[] = $row["price_out"];
                $meat[] = $row["meat"];
                $img[] = $row["img"];
            }
            for($i=0;$i<count($id);$i++)
            {
                $itemName = $name[$i];
                $itemMeat = $meat[$i];
                if($img[$i]!=null) $imageSrc = $img[$i];
                else $imageSrc = "http://placehold.it/700x400";
                    // Display Item Name, Image & Price
                    echo
                    "<div class='col-lg-4 col-md-6 mb-4' style='height: max-content;'>
                        <div class='card h-100'>
                            <div class='a'><img class='card-img-top' src='$imageSrc' alt=''></div>
                                <div class='card-body'>
                                    <h4 class='card-title'>
                                        <div class='a'>$itemName</div>
                                    </h4>
                                    <form action='javascript:void(0)' class='add-to-order' id='$id[$i]'>";
                    loadMeatSelection($itemName,$itemMeat);
                    echo "<div class='form-group'>
                            <label for='quantity' style='margin-top:-10px;' class='h5'>Quantity:</label>
                            <input name='quantity' type='text' maxlength='3' size='6' placeholder='0' class='form-control'/>
                        </div>
                    </br>
                    <input name='name' type='text' value='$itemName' style='display:none;' />
                    <input type='submit' class='btn btn-secondary form-control' value='Add to Order'/>
                    </form>
                </div>
              </div>
            </div>";
            }
        }
    }
    else {
        echo "<div class='col-lg-8' style='height: max-content;width:90%;margin-top:4.5rem;'>
                <div class='list-group'>
                    <form action='javascript:void(0)' id='customer-info-form'>
                        <div class='form-group list-group-item'>
                            <label for='customerName' style='margin-top:-10px;' class='h5'>Customer name:</label>
                            <input name='customerName' type='text' maxlength='60' size='60' class='form-control'/>
                        </div>
                        <div class='form-group list-group-item'>
                            <label for='customerPhoneNumber' style='margin-top:-10px;' class='h5'>Customer phone number:</label>
                            <input name='customerPhoneNumber' type='text' maxlength='12' size='60' class='form-control'/>
                        </div>
                        <div class='form-group list-group-item'>
                            <label for='customerAddress' style='margin-top:-10px;' class='h5'>Customer address:</label>
                            <input name='customerAddress' type='text' maxlength='120' size='60' class='form-control'/>
                        </div>
                        <div class='form-group list-group-item'>
                            <label for='cardNumber' style='margin-top:-10px;' class='h5'>Card number:</label>
                            <input name='cardNumber' type='text' maxlength='16' size='60' class='form-control'/>
                        </div>
                        <div class='form-group list-group-item'>
                            <label for='tableNumber' style='margin-top:-10px;' class='h5'>Table number:</label>
                            <input name='tableNumber' type='text' maxlength='3' size='60' class='form-control'/>
                        </div>
                        <div class='form-group list-group-item'>
                            <label for='delivCost' style='margin-top:-10px;' class='h5'>Delivery Cost:</label>
                            <input name='delivCost' type='number' step='0.5' maxlength='6' size='60' class='form-control'/>
                        </div>
                        <div class='list-group-item form-group'>
                          <label for='mode' class='h6'>Eating:</label>
                          <select name='mode' class='form-control'>
                              <option value='Dine-in' selected>Dine-in</option>
                              <option value='Takeaway'>Takeaway</option>
                              <option value='Delivery'>Delivery</option>
                          </select>
                        </div>
                        <input name='url' type='text' value='$url' style='display:none;' />
                        <div class='form-group list-group-item'>
                            <input type='submit' value='Add to Order' class='form-control'/>
                        </div>
                    </form>
                </div>
            </div>";
    }
    echo "</div>
        <!-- /.row -->
        </div>
        <!-- /.col-lg-9 -->";
};

//Create a menu list of the current order
function loadOrderList()
{
    //If an array of orders exists
    if(isset($_SESSION['order']) || isset($_SESSION['mode']))
    {
        //Get correct price in/out
        $priceMode = getPriceMode();

        //Connect to DB
        $conn = sqlConnect();

        //Set the total price
        $priceTotal = 0;

        echo '  <h1 class="my-4">Order</h1>
                    <div class="list-group">
                          <form action="javascript:void(0)" id="current-order-form">';

        $items = $_SESSION['order'];

        //For every item in the order list
        foreach($items as $id => $quantity)
        {
            $sql = "SELECT name,meat,$priceMode FROM menu_items WHERE id=$id;";
            $result = mysqli_query($conn,$sql);
            while($row = mysqli_fetch_assoc($result))
            {
                $name = $row['name'];
                $meat = $row['meat'];
                $price = $row["$priceMode"];
            }
            //Price is to 2 decimal places
            $price = number_format((float)$price*$quantity,2,'.','');
            //increase the total price by item price
            $priceTotal += $price;
            //If item has meat print the item with meat
            if(isset($meat))
                echo "<div class='a list-group-item'>
                        $quantity x $name - $meat
                        </br>$$price
                        <button type='button' id='$id' name='order-item' class='btn btn-secondary' style='position:absolute;right:30px;border: 0px;height: 26px;padding-left: 5px;padding-right: 5px;padding-bottom: 0px;padding-top: 0px;'>Remove</button>
                      </div>";
            else
                echo "<div class='a list-group-item'>
                        $quantity x $name
                        </br>$$price
                        <button type='button' id='$id' name='order-item' class='btn btn-secondary' style='position:absolute;right:30px;border: 0px;height: 26px;padding-left: 5px;padding-right: 5px;padding-bottom: 0px;padding-top: 0px;'>Remove</button>
                      </div>";
        }

        //Price total is to 2 decimal places
        $priceTotal = number_format((float)$priceTotal,2,'.','');

        //Display dining mode
        if(isset($_SESSION["mode"])) {
            $mode = $_SESSION["mode"];
            echo "<div class='a list-group-item h6'>Customer information saved.<br>Customer Dining: $mode</div>";
        }
        else echo "<div class='a list-group-item h6'>Customer Dining Not Set</div>";

        //Display delivery cost
        if(isset($_SESSION['delivCost'])) {
            $delivCost = number_format((float)$_SESSION['delivCost'],2,'.','');
            echo "<div class='a list-group-item h6'>Delivery Cost: $$delivCost</div>";
            $priceTotal = number_format((float)$priceTotal+$delivCost,2,'.','');
        }

        //Print the total order price
        echo "<div class='a list-group-item h6'>Grand Total: $$priceTotal</div>";
        echo "<input type=text value=$priceTotal name=price style='display:none;' />
                <div class='a list-group-item'>
                    <input class='btn btn-secondary form-control' type='submit' value='Send Order'/>
                </div>
             </form>
             </div>";

        mysqli_close($conn);
    }
};
?>
