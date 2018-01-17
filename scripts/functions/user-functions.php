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
    function makeUser($name,$password)
    {
        //Connect to DB
        $conn = sqlConnect();
        //Get new unique ID to give user
        $id = getMaxId('users');
        //One way salted hash for password
        $password = hashPass($password);
        //Insert post data into DB
        $sql="INSERT INTO users VALUES($id,'$name','$password',0);";
        if(!mysqli_query($conn,$sql)) echo("Database Error. Couldn't make new user.");
        mysqli_close($conn);
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
        $_SESSION['username']="$user";
        //Get admin level
        $conn = sqlConnect();
        $sql = "SELECT admin_level FROM users WHERE name = \"$user\";";
        $result = mysqli_query($conn,$sql);
        while($row = mysqli_fetch_assoc($result)) $level = $row['admin_level'];
        mysqli_close($conn);
        $_SESSION['adminLevel'] = $level;
    };
?>
