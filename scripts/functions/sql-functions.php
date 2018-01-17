<?php
    // Create a new SQL Connection (Don't forget to close in function!)
    function sqlConnect()
    {
        //Connect to DB
        $conn = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB);
        //Set charset for query
        mysqli_set_charset($conn, "utf8");
        //Alert if connection failed
        if (!$conn) die(printError("Database Connection Failed"));
        return $conn;
    };
    
    // Get Max ID for Specified SQL Table
    function getMaxId($table)
    {
        //Connect to DB
        $conn = sqlConnect();
        //Set ID 0
        $id=0;
        $sql="SELECT MAX(ID) FROM $table;";
        $result = mysqli_query($conn,$sql);
        if(mysqli_num_rows($result)>0) while($row = mysqli_fetch_assoc($result)) $id = $row["MAX(ID)"];
        mysqli_close($conn);
        //Whether or not result was returned, ID +1
        return $id + 1;
    };

    //Check if specified data exists in column from given table
    function sqlExists($data,$column,$table)
    {
        //Connect to DB
        $conn = sqlConnect();
        //Select count of columns that equal data to be checked
        $sql="SELECT count(id) FROM $table WHERE $column = \"$data\";";
        $result = mysqli_query($conn,$sql);
        //Check if column exists with data given
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
?>
