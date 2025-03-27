<?php
class DeviceHandler {
    public function addDevice() {
        $message = isset($_GET['message']) ? $_GET['message'] : '';
        if ($message) {
            $successMessage = '
            <div style="padding:10px; color:black; background:white;">
                ' . htmlspecialchars($message, ENT_QUOTES, 'UTF-8') . '
            </div>';
        } else {
            $successMessage = "";
        }
        
        return '
            <form action="add-device.php" method="POST">
                ' . $successMessage . '
                <label for="hostname">Hostname or IP Address:</label>
                <input type="text" id="hostname" name="hostname" required><br><br>

                <label for="device_type">Device Type:</label>
                <select id="device_type" name="device_type" onchange="updateOSOptions()">
                    <option value="PC">PC</option>
                    <option value="Web Server">Web Server</option>
                    <option value="Switch">Switch</option>
                    <option value="Router">Router</option>
                </select><br><br>

                <label for="os">Operating System:</label>
                <select id="os" name="os">
                    <option value="N/A">N/A</option>
                </select><br><br>

                <button class="b1" type="submit" name="add-device">Submit</button>
            </form>';
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

