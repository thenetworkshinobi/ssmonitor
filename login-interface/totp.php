<?php
include_once("../config/variables.php");
include_once("../config/db-connect.php");

session_start();
$id = $_SESSION['id'];

if(isset($_POST['totp'])){
    $code = htmlspecialchars($_POST['2fa']);   
    
    $message = array();


    if (empty($code)){
        $message[] = "Please enter 2FA Code";
        print_r($message);
        header("Location: " . $host . "/index.php?action=totp&message=". $message[0]);
        exit();
    } 
    else if (strlen($code) != 6){
        $message[] = "Make sure your 2FA Code has 6 charaters";
        header("Location: " . $host . "/index.php?action=totp&message=" . $message[0]);
        exit();

    } else {
        $database = new dbConnect();
        $dbh = $database->connect();
        $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
        $id_secret_finding_sql = "SELECT id_secret FROM admins WHERE id = :id";
        $id_secret_finding_stmt = $dbh->prepare($id_secret_finding_sql);
        $id_secret_finding_stmt->bindParam(':id', $id);
        $id_secret_finding_stmt->execute();
        $id_secret_finding_result = $id_secret_finding_stmt->fetch();

        //echo "id_secret: " . $id_secret_finding_result['id_secret'];
        if (empty($id_secret_finding_result)){
            $message[] = "No 2FA Code" . $id_secret_finding_result_secret ;
            header("Location: " . $host . "/index.php?action=totp&message=" . $message[0]);
            exit();
        }
        $_SESSION['id_secret']= true;

        header("Location: " . $host . "/dashboard/dashboard-home.php");
        exit();
    }
    

    
}

