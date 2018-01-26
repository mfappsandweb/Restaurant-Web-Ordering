<?php
    function headerLocation($location)
    {
        printJS("window.location.replace('$location');");
    };

    // Print new JavaScript
    function printJS($js) {

        echo "<script type='text/javascript'>$js</script>";
    };

    // Print any errors as Javascript alert
    function printError($error)
    {
        printJS("printError('$error')");
    };

    // Return todays date as yyyy-mm-dd
    function getDateNow()
    {
        //Get Date
        date_default_timezone_set('Australia/Brisbane');
        $date = date('Y-m-d', time());
        return $date;
    };

    // Check date is a valid order date
    function checkValidDate($date)
    {
        $conn = sqlConnect();
        $sql = "SELECT DISTINCT date FROM orders;";
        $result = mysqli_query($conn,$sql);
        while($row = mysqli_fetch_assoc($result)) $dates[] = $row['date'];
        if(isset($dates)) {
            if(!in_array("$date",$dates))
            {
                $max = max(array_map('strtotime', $dates));
                $url = "view-orders.php?date=".date('Y-m-d', $max);
                headerLocation($url);
                exit;
            }
        }
        else {
            $url = "view-orders.php?date=0000-00-00";
            headerLocation($url);
            exit;
        }
        return true;
    };
    // Mail to admin
    function mailMessage($message, $subject = NULL, $to = "mfappsandweb@gmail.com")
    {
        $from = "webmaster@mfappsandweb.nygmarose.com";
    
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=ISO-8859-1' . "\r\n";
        $headers .= 'From: webmaster@mfappsandweb.nygmarose.com'. "\r\n".
                    'X-Mailer: PHP/' . phpversion();
    
        if(mail($to,$subject,$message,$headers)) return true;
        else {
            error_log("Couldn't send Email!\nTo: $to\nSubject: $subject\nMessage: $message");
            return false;
        }
    };
?>
