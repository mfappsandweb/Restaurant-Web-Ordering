<?php
    session_start();
    require_once 'scripts/functions.php';
    extract($_GET);

    //Check admin level
    if($_SESSION["adminLevel"] != 10) headerLocation('index.php?error=admin_level');

    //Check for errors
    if(isset($error)) {
        switch ($error) {
            case "make-admin-error":
                printError("Error. Couldn't make account admin.");
                break;
            case "add-item":
                printError("Error. Couldn't add item to database.");
                break;
            case "add-option":
                printError("Error. Couldn't add option to menu item.");
                break;
        }
    }
    loadHead();
    loadNav();
    beginContent();

    //Get business ID
    $businessID = $_SESSION['businessID'];

    $conn = sqlConnect();

    //Get menu items for autofill
    $sql = "SELECT DISTINCT name 
            FROM menu_items
            WHERE business = $businessID;";
    $result = mysqli_query($conn,$sql);
    while($row = mysqli_fetch_assoc($result)) $names[] = $row['name'];

    //Get business user accounts for autofill
    $sql = "SELECT DISTINCT name 
            FROM users
            WHERE business = $businessID;";
    $result = mysqli_query($conn,$sql);
    while($row = mysqli_fetch_assoc($result)) $usernames[] = $row['name'];

    //Close DB connection
    mysqli_close($conn);

    //Echo page UI
    echo "<div class='col-lg-12' style='height: max-content;width:90%;margin-top:4.5rem;margin-bottom:4.5rem;'>

            <div class='list-group'>
                <h3>Add New Employee</h3>
                <form action='javascript:void(0)' id='add-employee-form'>
                    <div class='form-group list-group-item'>
                        <label class='control-label' for='regUser'>Username</label>
                        <input type='text' name='regUser' class='form-control' id='regUser'>
                    </div>
                    <div class='form-group list-group-item'>
                        <label for='regPassword'>Pin</label>
                        <input type='password' name='regPassword' class='form-control' id='regPassword'>
                    </div>
                    <div class='text-center list-group-item'>
                        <button type='submit' class='btn btn-primary'><i class='fa fa-user-plus'></i> Register</button>
                    </div>
                </form>
            </div>
            <!-- Autocomplete script for items -->
            <script>
            $( function() {
                var tags = [";
                for($i=0;$i<count($usernames);$i++) {
                    $tag = $usernames[$i];
                    if($i<count($usernames)-1) echo "\"$tag\",";
                    else echo "\"$tag\"";
                }
                echo "];
                $('#new-admin').autocomplete({
                    source: tags
                });
            });
            </script>

            <div class='list-group'>
            <br>
                <h3>Make User Admin</h3>
                <form action='javascript:void(0)' id='make-admin'>
                    <div class='form-group list-group-item'>
                        <label for='accountName' style='margin-top:-10px;' class='h5'>Make Account Admin:</label>
                        <input name='accountName' id='new-admin' type='text' maxlength='60' size='60' class='form-control'/>
                    </div>
                    <div class='text-center list-group-item'>
                        <button type='submit' class='btn btn-primary'><i class='fa fa-key'></i> Make User Admin</button>
                    </div>
                </form>
            </div>

            <div class='list-group'>
                <br>
                <h3>Add New Item</h3>
                <form action='javascript:void(0)' id='add-item'>
                    <div class='form-group list-group-item'>
                        <label for='itemName' style='margin-top:-10px;' class='h5'>Item Name:</label>
                        <input name='itemName' id='itemName' type='text' maxlength='60' size='60' class='form-control' placeholder='Pad Thai'/>
                    </div>
                    <div class='form-group list-group-item'>
                        <label for='itemOption' style='margin-top:-10px;' class='h5'>Item Options:</label>
                        <input name='itemOption' id='itemOption' type='text' maxlength='60' size='60' class='form-control' placeholder='Chicken'/>
                    </div>
                    <div class='form-group list-group-item'>
                        <label for='priceIn' style='margin-top:-10px;' class='h5'>Dine-in Price:</label>
                        <input name='priceIn' id='priceIn' type='number' maxlength='60' size='60' class='form-control' step='0.5' placeholder='14.50'/>
                    </div>
                    <div class='form-group list-group-item'>
                        <label for='priceOut' style='margin-top:-10px;' class='h5'>Takeaway Price:</label>
                        <input name='priceOut' id='priceOut' type='number' maxlength='60' size='60' class='form-control' step='0.5' placeholder='13.50'/>
                    </div>
                    <div class='form-group list-group-item'>
                        <label for='category' style='margin-top:-10px;' class='h5'>Category:</label>
                        <input name='category' id='category' type='text' maxlength='60' size='60' class='form-control' placeholder='special'/>
                    </div>
                    <div class='text-center list-group-item'>
                        <button type='submit' class='btn btn-primary'><i class='fa fa-list'></i> Add New Item</button>
                    </div>
                </form>
            </div>

            <!-- Autocomplete script for items -->
            <script>
            $( function() {
                var tags = [";
            for($i=0;$i<count($names);$i++) {
                $tag = $names[$i];
                if($i<count($names)-1) echo "\"$tag\",";
                else echo "\"$tag\"";
            }
            echo "];
                $('#newItemName').autocomplete({
                    source: tags
                });
                $('#removeItemName').autocomplete({
                    source: tags
                });
            });
            </script>

            <div class='list-group'>
                <br>
                <h3>Add New Option</h3>
                <form action='javascript:void(0)' id='update-item'>
                    <div class='form-group list-group-item'>
                        <label for='newItemName' style='margin-top:-10px;' class='h5'>Item Name:</label>
                        <input id='newItemName' type='text' maxlength='60' size='60' class='form-control' placeholder='Pad Thai'/>
                    </div>
                    <div class='form-group list-group-item'>
                        <label for='newItemOption' style='margin-top:-10px;' class='h5'>Item Options:</label>
                        <input id='newItemOption' type='text' maxlength='60' size='60' class='form-control' placeholder='Chicken'/>
                    </div>
                    <div class='form-group list-group-item'>
                        <label for='newPriceIn' style='margin-top:-10px;' class='h5'>Dine-in Price:</label>
                        <input id='newPriceIn' type='number' maxlength='60' size='60' class='form-control' step='0.5' placeholder='14.50'/>
                    </div>
                    <div class='form-group list-group-item'>
                        <label for='newPriceOut' style='margin-top:-10px;' class='h5'>Takeaway Price:</label>
                        <input id='newPriceOut' type='number' maxlength='60' size='60' class='form-control' step='0.5' placeholder='13.50'/>
                    </div>
                    <div class='text-center list-group-item'>
                        <button type='submit' class='btn btn-primary'><i class='fa fa-plus-square'></i> Add New Option</button>
                    </div>
                </form>
            </div>

            <div class='list-group'>
                <br>
                <h3>Remove Item</h3>
                <form action='javascript:void(0)' id='remove-item'>
                    <div class='form-group list-group-item'>
                        <label for='removeItemName' style='margin-top:-10px;' class='h5'>Item Name:</label>
                        <input id='removeItemName' type='text' maxlength='60' size='60' class='form-control' placeholder='Pad Thai'/>
                    </div>
                    <div class='text-center list-group-item'>
                        <button type='submit' class='btn btn-primary'><i class='fa fa-outdent'></i> Remove Item From Menu</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /.row -->
    </div>
    <!-- /.col-lg-9 -->";
    loadFoot();
?>
