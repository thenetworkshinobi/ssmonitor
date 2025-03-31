<?php
    require_once("../config/variables.php");
    require_once("../partials/header.php");
    require_once("partials/login-verify.php");
    include_once('../config/db-connect.php');
    
?>

<h1> Add Hosts </h1>

<?php
    // Connect to the database
    $database = new dbConnect();
    $dbh = $database->connect();
    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
    $message = isset($_GET['message']) ? [$_GET['message']] : [];

    
    // Fetch options for dropdown
    function fetchOptions($dbh, $table, $idField, $nameField) {
        try {
            $allowedTables = ['device_type', 'os']; // Whitelist tables
            if (!in_array($table, $allowedTables)) {
                throw new Exception("Invalid table name");
            }
            
            $stmt = $dbh->prepare("SELECT $idField, $nameField FROM $table");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error fetching options: " . $e->getMessage();
            return [];
        }
    }
    
    // Add a device
    function addDevice($dbh, $hostname, $ip_address, $typeID, $osID, $adminID) {
        try {
            $stmt = $dbh->prepare("INSERT INTO device (hostname, ip_address, typeID, osID, adminID) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$hostname, $ip_address, $typeID, $osID, $adminID]);
            echo "Device added successfully!";
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
    
    // Render the form
    function renderAddDeviceForm($deviceTypes, $osList, $message = []) {
        if (!empty($message)) {
            echo '<div style="padding:10px; color:green; background:white;">' 
            . htmlspecialchars(implode('<br>', $message), ENT_QUOTES, 'UTF-8') . 
            '</div>';
        }
        
        ?>
        <form method="post" action="">
            <label for="hostname">Hostname:</label>
            <input type="text" name="hostname" id="hostname"><br><br>
    
            <label for="ip_address">IP Address:</label>
            <input type="text" name="ip_address" id="ip_address" ><br><br>
    
            <label for="typeID">Device Type:</label>
            <select name="typeID" id="typeID" required>
                <option value="">--Select Device Type--</option>
                <?php foreach ($deviceTypes as $type): ?>
                    <option value="<?= htmlspecialchars($type['typeID'], ENT_QUOTES, 'UTF-8') ?>">
                        <?= htmlspecialchars($type['type_name'], ENT_QUOTES, 'UTF-8') ?>
                    </option>
                <?php endforeach; ?>
            </select><br><br>
    
            <label for="osID">Operating System:</label>
            <select name="osID" id="osID" required>
                <option value="">--Select Operating System--</option>
                <?php foreach ($osList as $os): ?>
                    <option value="<?= htmlspecialchars($os['osID'], ENT_QUOTES, 'UTF-8') ?>">
                        <?= htmlspecialchars($os['os_name'], ENT_QUOTES, 'UTF-8') ?>
                    </option>
                <?php endforeach; ?>
            </select><br><br>
    
            <button class="b1" type="submit">Add Device</button>
        </form>
        <?php
    }
    
    // Main Logic
    
    $deviceTypes = fetchOptions($dbh, "device_type", "typeID", "type_name");
    $osList = fetchOptions($dbh, "os", "osID", "os_name");
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $hostname = $_POST['hostname'] ?? '';
        $ip_address= $_POST['ip_address'] ?? '';
        $typeID = $_POST['typeID'] ?? '';
        $osID = $_POST['osID'] ?? '';
        $adminID = $_SESSION['id'] ?? 1;

           
        
        if (empty($ip_address)) {
            $ip_address = gethostbyname($hostname);
        }
                
        if (!filter_var($ip_address, FILTER_VALIDATE_IP)) {
            $message[] = "Unable to resolve the IP address for the given hostname.";
            header("Location: " . $host . "/dashboard/dashboard-add.php?action=add-device&message=" . urlencode($message[0]));
            exit();
        }
        else{
            
            try{
                $host_find_sql = "SELECT * FROM device WHERE ip_address=:ip_address";
                $host_find_stmt = $dbh->prepare($host_find_sql);
                $host_find_stmt->bindParam("ip_address", $ip_address);
                $host_find_stmt->execute();
                $host_find_result = $host_find_stmt->fetch();
            } catch (PDOException $e) {
                $message[] = "Error checking existing device: " . $e->getMessage();
                header("Location: " . $host . "/dashboard/dashboard-add.php?action=add-device&message=" . urlencode($message[0]));
                exit();
            }
            
            
            if (empty($host_find_result)){
                // Insert data into MySQL table
                addDevice($dbh, $hostname, $ip_address, $typeID, $osID, $adminID);

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
        
        
        
    
        
    }
    
    renderAddDeviceForm($deviceTypes, $osList, $message);
    
    
    
    
?>


<?php require_once("../partials/footer.php"); ?>