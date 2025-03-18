<?php
    require_once("../config/variables.php");
    require_once("../partials/header.php");
    require_once("partials/login-verify.php");
    require_once('../config/db-connect.php');
    require_once("partials/device-handler.php");
    
?>

<h1> Remove Hosts </h1>
<?php /*
class DeviceHandler {
    
    public function removeDevice() {
        $message = '';
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['hostname'])) {
            $hostname = htmlspecialchars($_POST['hostname']);
    
            // Database removal logic
            try {
                $database1 = new dbConnect();
                $dbh1 = $database1->connect();
                $dbh1->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
                $delete_host_sql = "DELETE FROM device_list WHERE hostname = :hostname";
                $delete_host_stmt = $dbh1->prepare($delete_host_sql);
                $delete_host_stmt->bindParam(':hostname', $hostname);
    
                if ($delete_host_stmt->execute()) {
                    $message = "Device removed successfully.";
                } else {
                    $message = "Failed to remove device.";
                }
            } catch (PDOException $e) {
                $message = "Error: " . $e->getMessage();
            }
        }
    
        // Start output buffering
        ob_start();
        ?>
        <form action="" method="POST">
            <label for="hostname">Hostname to Remove:</label>
            <select id="hostname" name="hostname" required>
                <?php
                try {
                    // Assuming $dbh is your PDO database connection
                    $database2 = new dbConnect();
                    $dbh2 = $database2->connect();
                    $dbh2->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
                    $list_host_sql = "SELECT hostname FROM device_list";
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
        <div style="padding:10px; color:black; background:white;">
            <?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?>
        </div>
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
}*/
    $deviceHandler = new DeviceHandler();
    $deviceHandler->renderRemoveForm();
?>

<?php require_once("../partials/footer.php"); ?> 