<?php
include_once "../config/variables.php";
include_once "../config/db-connect.php";
require_once __DIR__ . '/../vendor/autoload.php';

session_start();
$id = $_SESSION['id'];

if(isset($_POST['totp'])){
    $userCode = htmlspecialchars($_POST['2fa']);   
    
    $message = [];


    if (empty($userCode)){
        $message[] = "Please enter 2FA Code";
        print_r($message);
        header("Location: " . $host . "/index.php?action=totp&message=". $message[0]);
        exit();
    } 
    else if (strlen($userCode) != 6){
        $message[] = "Make sure your 2FA Code has 6 numbers";
        header("Location: " . $host . "/index.php?action=totp&message=" . $message[0]);
        exit();

    } else {
        $g = new PHPGangsta_GoogleAuthenticator();
        $database = new dbConnect();
        $dbh = $database->connect();
        $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
        $id_secret_finding_sql = "SELECT id_secret FROM adminuser WHERE adminID = :adminID";
        $id_secret_finding_stmt = $dbh->prepare($id_secret_finding_sql);
        $id_secret_finding_stmt->bindParam(':adminID', $id);
        $id_secret_finding_stmt->execute();
        $id_secret_finding_result = $id_secret_finding_stmt->fetch();
        $id_secret = $id_secret_finding_result->id_secret;

        if ($g->verifyCode($id_secret, $userCode, 2)) {
            $_SESSION['id_secret']= true;

            header("Location: " . $host . "/dashboard/dashboard-home.php");
            exit();
        
        }else {
            $message[] = "Try again";
            header("Location: " . $host . "/index.php?action=totp&message=" . $message[0]);
            exit();
        }        
    }    
}

