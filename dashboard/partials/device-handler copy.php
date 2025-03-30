<?php
class DeviceHandler {
    // Function to fetch dropdown options
    private function fetchOptions($table, $idField, $nameField) {
        try {
            $database = new dbConnect();
            $dbh = $database->connect();
            $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $stmt = $dbh->prepare("SELECT $idField, $nameField FROM $table");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error fetching options: " . $e->getMessage();
            return [];
        }
    }

    public function addDevice() {
        // Fetch device types and operating systems
        $deviceTypes = fetchOptions('device_type', 'typeID', 'type_name');
        $osList = fetchOptions('os', 'osID', 'os_name');

        // Check if the form was submitted
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $hostname = $_POST['hostname'];
            $ip_address = $_POST['ip_address'];
            $typeID = $_POST['typeID'];
            $osID = $_POST['osID'];
            $adminID = 1; // Replace with the current admin ID (e.g., fetched from session)

            try {
                $stmt = $pdo->prepare("INSERT INTO device (hostname, ip_address, typeID, osID, adminID) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$hostname, $ip_address, $typeID, $osID, $adminID]);
                echo "Device added successfully!";
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        }
        echo "
        <h1>Add Device</h1>
        <form method='post' action=''>
            <label for='hostname'>Hostname:</label>
            <input type='text' name='hostname' id='hostname' required><br><br>

            <label for='ip_address'>IP Address:</label>
            <input type='text' name='ip_address' id='ip_address' required><br><br>

            <label for='typeID'>Device Type:</label>
            <select name='typeID' id='typeID' required>
                <option value=''>--Select Device Type--</option>";

        foreach ($deviceTypes as $type) {
            echo "<option value='" . htmlspecialchars($type['typeID']) . "'>" . htmlspecialchars($type['type_name']) . "</option>";
        }

        echo "
            </select><br><br>

            <label for='osID'>Operating System:</label>
            <select name='osID' id='osID' required>
                <option value=''>--Select Operating System--</option>";

        foreach ($osList as $os) {
            echo "<option value='" . htmlspecialchars($os['osID']) . "'>" . htmlspecialchars($os['os_name']) . "</option>";
        }

        echo "
            </select><br><br>

            <button type='submit'>Add Device</button>
        </form>";
    }

    public function removeDevice() {
        $message = $message ?? ''; 
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['hostname'])) {
            $hostname = htmlspecialchars($_POST['hostname']);
    
            // Database removal logic
            try {
                $database1 = new dbConnect();
                $dbh1 = $database1->connect();
                $dbh1->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

                $dbh1->beginTransaction();

                // Step 1: Retrieve the deviceID based on the hostname.
                $get_device_id_sql = "SELECT deviceID FROM device WHERE hostname = :hostname";
                $get_device_id_stmt = $dbh1->prepare($get_device_id_sql);
                $get_device_id_stmt->bindParam(':hostname', $hostname, PDO::PARAM_STR);
                $get_device_id_stmt->execute();
                $device_id_result = $get_device_id_stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($device_id_result && isset($device_id_result['deviceID'])) {
                    $deviceID = $device_id_result['deviceID'];
                    
                    // Step 2: Delete from related tables using the deviceID.
                    $delete_device_status_sql = "DELETE FROM device_status WHERE deviceID = :deviceID";
                    $delete_device_status_stmt = $dbh1->prepare($delete_device_status_sql);
                    $delete_device_status_stmt->bindParam(':deviceID', $deviceID, PDO::PARAM_INT);
                    $delete_device_status_stmt->execute();
    
                    // Step 3: Delete from the device table.
                    $delete_device_sql = "DELETE FROM device WHERE deviceID = :deviceID";
                    $delete_device_stmt = $dbh1->prepare($delete_device_sql);
                    $delete_device_stmt->bindParam(':deviceID', $deviceID, PDO::PARAM_INT);
                    $delete_device_stmt->execute();
    
                   
    
                    // Commit transaction.
                    $dbh1->commit();
                    $message = "Device and all associated entries removed successfully.";
                } else {
                    $message = "Error: Hostname not found.";
                    $dbh->rollBack(); // Rollback transaction if deviceID retrieval fails.
                }
            } catch (PDOException $e) {
                $message = "Error: " . $e->getMessage();
            }
        }
    
        // Start output buffering
        ob_start();
        ?>
        <div style="padding:10px; color:red; background:white;">
            <?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?>
        </div>
        <form action="" method="POST">
            <label for="hostname">Hostname to Remove:</label>
            <select id="hostname" name="hostname" required>
                <?php
                try {
                    // Assuming $dbh is your PDO database connection
                    $database2 = new dbConnect();
                    $dbh2 = $database2->connect();
                    $dbh2->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
                    $list_host_sql = "SELECT hostname FROM device";
                    $list_host_stmt = $dbh2->prepare($list_host_sql); 
                    $list_host_stmt->execute();
                    $list_host_results = $list_host_stmt->fetchAll();
                    // Loop through the results and populate the dropdown options
                    foreach ($list_host_results as $row) {
                        echo '<option value="' . htmlspecialchars($row['hostname'], ENT_QUOTES, 'UTF-8') . '">' 
                            . htmlspecialchars($row['hostname'], ENT_QUOTES, 'UTF-8') 
                            . '</option>';
                    }
                } catch (PDOException $e) {
                    echo '<option value="">Error fetching hostnames</option>';
                }
                ?>
            </select><br><br>
    
            <button class="b1" type="submit" name="remove-device">Remove Device</button>
        </form>
        
        <?php
        // Return the buffered content
        return ob_get_clean();
    }
    

    public function renderDeviceForm() {
        echo $this->addDevice();
    }

    public function renderRemoveForm() {
        echo $this->removeDevice();
    }
}

// Usage
//$deviceHandler = new DeviceHandler();
//$deviceHandler->renderDeviceForm();
//$deviceHandler->renderRemoveForm();
?>

