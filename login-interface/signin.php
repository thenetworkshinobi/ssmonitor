<?php
include_once("../config/variables.php");
include_once('../config/db-connect.php');


if (isset($_POST['signin'])) {
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);




    // DECLARING EMPTY ARRAY OF ERRORS
    $message = array();


    // CHECK FOR ALL INPUT FIELDS ARE FILL
    if (empty($username) && empty($password)) {
        $message[] = "Please fill all the fields";
        print_r($message);
        // echo "No input";
        header("Location: " . $host . "/index.php?action=signin&message=" . $message[0]);
        exit();
        // CHECK FOR PASSWORD LENGTH
    } else if (strlen($password) < 6) {
        $message[] = "Make sure your password has atleast 6 letter";
        header("Location: " . $host . "/index.php?action=signin&message=" . $message[0]);
        exit();
        // CHECK FOR A VALID username
        // https://www.php.net/manual/en/filter.filters.validate.php
        // Validates whether the value is a valid e-mail address.
    /*
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Something went wrong with email";
        $message[] = "This is not valid email";
        header("Location: " . $host . "/index.php?action=signin&message=" . $message[0]);
        exit(); */

        // IF ALL THE IF ELSE STATEMENT HAS FAILED THEN IT SAVE USER
    } else {
        // IF EVERYTHING WENT RIGHT
        // CHECK FOR THE USERNAME THAT ALREADY REGISTED OR NOT
        $database = new dbConnect();
        $dbh = $database->connect();
        $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
        $hash_password = md5($password);
        $username_password_finding_sql =  "SELECT adminID, fname, username FROM adminuser WHERE username=:username AND password=:password";    // SOME PROBLEM WITH SQL QUERY
        $username_password_finding_stmt = $dbh->prepare($username_password_finding_sql);
        $username_password_finding_stmt->bindParam("username", $username);
        // $hash_password = md5($password);
        $username_password_finding_stmt->bindParam("password", $hash_password);
        $username_password_finding_stmt->execute();
        $username_password_result = $username_password_finding_stmt->fetch();




        // IF USER INPUT WRONG username OR PASSWORD
        if (empty($username_password_result)) {
            // print_r("no user");
            // die();
            $message[] = "Incorrect password or username";
            header("Location: " . $host . "/index.php?action=signin&message=" . $message[0]);
            exit();
        } else {
            // IF USER INPUT RIGHT username AND PASSWORD
            // echo $username_password_result->full_name;
            // die();
            // session_start() creates a session or resumes the current one based on a session identifier passed via a GET or POST request, or passed via a cookie.
            session_start();
            $_SESSION['id'] = $username_password_result->adminID;
            $_SESSION['fname'] = $username_password_result->fname;
            $_SESSION['username'] = $username_password_result->username;
            $_SESSION['id_secret'] = false;
            
            //$message[] = "Enter 2FA Code";
            //header("Location: " . $host . "/index.php?action=dashboard&message=" . $message[0]);
            header("Location: " . $host . "/index.php?action=totp&message=" /*. $message[0]*/);
            
            exit();
        }
    }
    // return $errors;

}
