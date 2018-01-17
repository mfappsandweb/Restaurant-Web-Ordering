<?php
    session_start();
    require_once 'scripts/functions.php';
    extract($_GET);
    if($_SESSION["adminLevel"] != 10) headerLocation('index.php?error=admin_level');
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

    //Load admin page
    $conn = sqlConnect();
    $sql = "SELECT DISTINCT name FROM menu_items;";
    $result = mysqli_query($conn,$sql);
    while($row = mysqli_fetch_assoc($result)) $names[] = $row['name'];
    $sql = "SELECT DISTINCT name FROM users;";
    $result = mysqli_query($conn,$sql);
    while($row = mysqli_fetch_assoc($result)) $usernames[] = $row['name'];
    mysqli_close($conn);
    //Echo page UI
    echo "<div class='col-lg-8' style='height: max-content;width:90%;margin-top:4.5rem;margin-bottom:4.5rem;'>

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
                <form action='make-admin.php' method='post'>
                    <div class='form-group list-group-item'>
                        <label for='accountName' style='margin-top:-10px;' class='h5'>Make Account Admin:</label>
                        <input name='accountName' id='new-admin' type='text' maxlength='60' size='60' class='form-control'/>
                    </div>
                    <div class='form-group list-group-item'>
                        <input type='submit' value='Send' class='form-control'/>
                    </div>
                </form>
            </div>

            <div class='list-group'>
                <br>
                <h3>Add New Item</h3>
                <form action='add-item.php' method='post'>
                    <div class='form-group list-group-item'>
                        <label for='itemName' style='margin-top:-10px;' class='h5'>Item Name:</label>
                        <input name='itemName' type='text' maxlength='60' size='60' class='form-control' placeholder='Pad Thai'/>
                    </div>
                    <div class='form-group list-group-item'>
                        <label for='itemOption' style='margin-top:-10px;' class='h5'>Item Option 1:</label>
                        <input name='itemOption' type='text' maxlength='60' size='60' class='form-control' placeholder='Chicken'/>
                    </div>
                    <div class='form-group list-group-item'>
                        <label for='priceIn' style='margin-top:-10px;' class='h5'>Dine-in Price:</label>
                        <input name='priceIn' type='number' maxlength='60' size='60' class='form-control' step='0.5' placeholder='14.50'/>
                    </div>
                    <div class='form-group list-group-item'>
                        <label for='priceOut' style='margin-top:-10px;' class='h5'>Takeaway Price:</label>
                        <input name='priceOut' type='number' maxlength='60' size='60' class='form-control' step='0.5' placeholder='13.50'/>
                    </div>
                    <div class='form-group list-group-item'>
                        <label for='category' style='margin-top:-10px;' class='h5'>Category:</label>
                        <input name='category' type='text' maxlength='60' size='60' class='form-control' placeholder='special'/>
                    </div>
                    <div class='form-group list-group-item'>
                        <input type='submit' value='Add New Item' class='form-control'/>
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
                $('#update-item').autocomplete({
                    source: tags
                });
            });
            </script>

            <div class='list-group'>
                <br>
                <h3>Add New Option</h3>
                <form action='update-item.php' method='post'>
                    <div class='form-group list-group-item'>
                        <label for='itemName' style='margin-top:-10px;' class='h5'>Item Name:</label>
                        <input name='itemName' id='update-item' type='text' maxlength='60' size='60' class='form-control' placeholder='Pad Thai'/>
                    </div>
                    <div class='form-group list-group-item'>
                        <label for='itemOption' style='margin-top:-10px;' class='h5'>Item Option 1:</label>
                        <input name='itemOption' type='text' maxlength='60' size='60' class='form-control' placeholder='Chicken'/>
                    </div>
                    <div class='form-group list-group-item'>
                        <label for='priceIn' style='margin-top:-10px;' class='h5'>Dine-in Price:</label>
                        <input name='priceIn' type='number' maxlength='60' size='60' class='form-control' step='0.5' placeholder='14.50'/>
                    </div>
                    <div class='form-group list-group-item'>
                        <label for='priceOut' style='margin-top:-10px;' class='h5'>Takeaway Price:</label>
                        <input name='priceOut' type='number' maxlength='60' size='60' class='form-control' step='0.5' placeholder='13.50'/>
                    </div>
                    <div class='form-group list-group-item'>
                        <input type='submit' value='Add New Option' class='form-control'/>
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
