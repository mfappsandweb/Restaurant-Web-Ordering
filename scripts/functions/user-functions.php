<?php
    //Password hash function thanks to https://pbeblog.wordpress.com/2008/02/12/secure-hashes-in-php-using-salt/
    //Hash password and return hash
    function hashPass($pass)
    {
        //Create password hash
        $salt = sha1(md5($pass));
        $hashPassword = md5($pass.$salt);
        return $hashPassword;
    };

    //Get Name & Password to insert into SQL DB
    function makeUser($name,$password,$business = NULL)
    {
        //Connect to DB
        $conn = sqlConnect();

        //Get business ID
        if(isset($business)) $businessID = makeBusiness($business);
        else $businessID = $_SESSION['bussinessID'];

        //Check business ID exists
        if($businessID != false && $businessID != "0") {
            //Get new unique ID to give user
            $id = getMaxId('users');

            //One way salted hash for password
            $password = hashPass($password);

            //Insert post data into DB
            $sql = "INSERT INTO users(ID, name, password, business, admin_level, enabled)
                    VALUES($id, '$name', '$password', $businessID, 10, 0);
                    ";

            //Check SQL query
            if(!mysqli_query($conn,$sql)) {
                echo("Database Error. Couldn't make new user.");
                return false;
            }
            mysqli_close($conn);
            $message = "<html>
                            <body>
                                <p>
                                    New user account created.<br>
                                    Username $name.<br>
                                    Click <a href='https://mfappsandweb.nygmarose.com/restaurant/scripts/enable-account.php?id=$id'>here</a> to enable account.
                                </p>
                            </body>
                        </html>";
            mailMessage($message,"New Restaurant Order App User");
            return true;
        }
        else {
            echo "Business already exists.";
        }
    };

    //Register new business
    function makeBusiness($business)
    {
        if( sqlExists($business,"name","business") ) return false;
        else {
            $conn = sqlConnect();
            $id = getMaxId("business");
            $sql = "INSERT INTO business(id, name)
                    VALUES($id, '$business');
                    ";
            if(mysqli_query($conn,$sql)) {
                mysqli_close($conn);
                return $id;
            }
            else {
                echo "Couldn't add business to DB.";
                mysqli_close($conn);
            }
        }
        return false;
    };

    //Set account enabled 
    function enableAccount($id)
    {
        //Connect to DB 
        $conn = sqlConnect();
        //Build SQL statement
        $sql = "UPDATE users 
                SET enabled = 1 
                WHERE ID = $id;
                ";
        if(mysqli_query($conn,$sql)) {
            mysqli_close($conn);
            return true;
        }
        mysqli_close($conn);
        return false;
    };

    //Check if user/password combination exist
    function checkUser($pass,$user)
    {
        //Connect to DB
        $conn = sqlConnect();
        //Get password hash
        $pass = hashPass($pass);
        //Select count of columns that equal data to be checked
        $sql="SELECT count(id) FROM users WHERE password = \"$pass\" AND name = \"$user\";";
        $result = mysqli_query($conn,$sql);
        //Check if User/Password combo exists
        if(mysqli_num_rows($result)>0)
        {
            while($row = mysqli_fetch_assoc($result))
            {
                if($row["count(id)"] > 0) {
                    mysqli_close($conn);
                    return true;
                }
            }
        }
        mysqli_close($conn);
        return false;
    };

    //Save username to users session for future use & proof of login
    function setLoggedIn($user)
    {
        //Set logged in and save username for session use
        $_SESSION['username'] = "$user";

        //Get admin level
        $conn = sqlConnect();
        $sql = "SELECT admin_level,enabled,business 
                FROM users 
                WHERE name = \"$user\";
                ";
        $result = mysqli_query($conn,$sql);
        while($row = mysqli_fetch_assoc($result)) {
            $level = $row['admin_level'];
            $enabled = $row['enabled'];
            $business = $row['business'];
        }
        mysqli_close($conn);

        //Save admin level and enabled status
        $_SESSION['userEnabled'] = $enabled;
        $_SESSION['businessID'] = $business;
        $_SESSION['adminLevel'] = $level;
    };
?>
