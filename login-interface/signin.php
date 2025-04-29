<?php
include_once("../config/variables.php");
include_once('../config/db-connect.php');


if (isset($_POST['signin'])) {
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);

    // DECLARING EMPTY ARRAY OF ERRORS
    $message = [];

    // CHECK FOR ALL INPUT FIELDS ARE FILL
    if (empty($username) && empty($password)) {
        $message[] = "Please enter all the fields";
        print_r($message);
        // echo "No input";
        header("Location: " . $host . "/index.php?action=signin&message=" . $message[0]);
        exit();
        
    }
    // CHECK FOR PASSWORD LENGTH
    if (strlen($password) < 6) {
        $message[] = "Make sure your password has atleast 6 letter";
        header("Location: " . $host . "/index.php?action=signin&message=" . $message[0]);
        exit();
    } 

    // CHECK FOR THE USERNAME THAT ALREADY REGISTED OR NOT
    $database = new dbConnect();
    $dbh = $database->connect();
    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
    
    $username_password_finding_sql =  "SELECT adminID, fname, username, password FROM adminuser WHERE username = :username"; 
    $username_password_finding_stmt = $dbh->prepare($username_password_finding_sql);
    $username_password_finding_stmt->bindParam(":username", $username);
    $username_password_finding_stmt->execute();
    $username_password_result = $username_password_finding_stmt->fetch();

    // IF USER INPUT WRONG username OR PASSWORD
    if (empty($username_password_result)) {
        $message[] = "Incorrect or username";
        header("Location: " . $host . "/index.php?action=signin&message=" . $message[0]);
        exit();
    }

    $dbPassword = $username_password_result->password;

    if (password_verify($password, $dbPassword)) {
        session_start();
        $_SESSION['id'] = $username_password_result->adminID;
        $_SESSION['fname'] = $username_password_result->fname;
        $_SESSION['username'] = $username_password_result->username;
        $_SESSION['totp'] = false;
        header("Location: " . $host . "/index.php?action=totp");            
        exit();
    }else{
        $message[] = "Incorrect username or password";
        header("Location: " . $host . "/index.php?action=signin&message=" . urlencode($message[0]));
        exit();
    }
    
    

}
