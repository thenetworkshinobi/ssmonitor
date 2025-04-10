<?php
    require_once("../GoogleAuthenticator/PHPGangsta/GoogleAuthenticator.php");
    include_once("../config/variables.php");
    include_once("../config/db-connect.php");
    require_once("../partials/header.php");

    //if(isset($_SESSION['UN'])){
        //$username = $_SESSION['UN'];
        $username= 'testuser3';
        try{
            /*
            $database = new dbConnect();
            $dbh = $database->connect();
            $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
            $username_finding_sql = "SELECT * FROM adminuser WHERE username=:username";
            $username_finding_stmt = $dbh->prepare($username_finding_sql);
            $username_finding_stmt->bindParam(":username", $username);
            $username_finding_stmt->execute();
            $username_result = $username_finding_stmt->fetch();
            */
            if (empty($username_result)) {
                $message[] = "An user is not registered";
                header("Location: " . $host . "/index.php?action=signup&message=" . $message[0]);
                exit();
            }else {
                $ga = new PHPGangsta_GoogleAuthenticator();
                $secret = $ga->createSecret();

                //echo "Secret is: ". $secret ."\n\n";

                $qrCodeUrl = $ga->getQRCodeGoogleUrl('Blog', $secret);
                //echo "Google Charts URL for the QR-Code: ".$qrCodeUrl."\n\n";
                
                echo "<img src='" . $qrCodeUrl . "'>";

                $oneCode = $ga->getCode($secret);
                //echo "Checking Code ". $oneCode . " and Secret ". $secret .":\n";

                $checkResult = $ga->verifyCode($secret, $oneCode, 2);    // 2 = 2*30sec clock tolerance
                if ($checkResult) {
                    echo 'OK';
                } else {
                    echo 'FAILED';
                }
                $secret_sql = "UPDATE adminuser SET secret = :secret WHERE username = :username";
                $secret_stmt = $dbh->prepare($secret_sql);
                $secret_stmt->bindParam(':secret', $secret);
                $secret_stmt->bindParam(':username', $username);
                $secret_stmt->execute();


                $message[] = "User has registered successfully";
                header("Location: " . $host . "/index.php?action=signin&message=" . $message[0]);
                exit();

                    // IF THERE THE EMAIL IS NOT REGISTED 
                    // WE WILL DO THE REGISER 
            } 
        } catch (PDOException $e) {
            // Catch and display database errors
            echo "Database error: " . $e->getMessage();
        }   
        
    //}


    
?>