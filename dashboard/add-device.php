<?php
    require_once("../config/variables.php");
    require_once("../partials/header.php");
    require_once("partials/login-verify.php");
    require_once("../config/db-connect.php");
?>

<?php
    if(isset($_POST['add-device'])){
        // Retrieve form data
        $hostname = htmlspecialchars($_POST['hostname']);
        $device_type = htmlspecialchars($_POST['device_type']);
        $os = htmlspecialchars($_POST['os']);
        $message = array();
        $id = $_SESSION['id'];

        // Resolve IP address
        $ip_address = gethostbyname($hostname);
        if ($ip_address == $hostname) {
            $message[] = "Unable to resolve the IP address for the given hostname.";
            header("Location: " . $host . "/dashboard/dashboard-add.php?action=add-device&message=" . urlencode($message[0]));
            exit();
        }else {

            $database = new dbConnect();
            $dbh = $database->connect();
            $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

            //Check for Existing Entry
            $host_find_sql = "SELECT * FROM device WHERE ip_address=:ip_address";
            $host_find_stmt = $dbh->prepare($host_find_sql);
            $host_find_stmt->bindParam("ip_address", $ip_address);
            $host_find_stmt->execute();
            $host_find_result = $host_find_stmt->fetch();

            if (empty($host_find_result)){
                // Insert data into MySQL table
                $add_device_sql = "INSERT INTO device (hostname, ip_address, typeID, osID, adminID) 
                    VALUES (:hostname, :ip_address, :typeID, :osID, :id)";
                $add_device_sql_stmt = $dbh->prepare($add_device_sql);
                $add_device_sql_stmt->bindParam("hostname", $hostname);
                $add_device_sql_stmt->bindParam("ip_address", $ip_address);
                $add_device_sql_stmt->bindParam("typeID", $device_type);
                $add_device_sql_stmt->bindParam("osID", $os);
                $add_device_sql_stmt->bindParam("id", $id);
                $add_device_sql_stmt->execute();

                $message[] = "New device registered successfully";
                header("Location: " .$host . "/dashboard/dashboard-add.php?action=add-device&message=" . $message[0]);
                exit();
            }
            else {
                $message[] = "Host already registered";
                header("Location: " .$host . "/dashboard/dashboard-add.php?action=add-device&message=" . $message[0]);
                exit();
            } 
        }
        // Close connection
        //$conn->close();
    }
    
    return $errors;
?>
