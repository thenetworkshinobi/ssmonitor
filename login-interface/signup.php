<?php
include_once("../config/variables.php");
include_once("../config/db-connect.php");



if (isset($_POST['signup'])) {
    // echo $_POST['fname'];
    $fname = htmlspecialchars($_POST['fname']);
    $lname = htmlspecialchars($_POST['lname']);
    $username = htmlspecialchars($_POST['username']);
    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']);
    $password2 = htmlspecialchars($_POST['password2']);

    // echo $host ; 
    // echo "Password 2: " . $password2;


    // DECLARING EMPTY ARRAY OF ERRORS
    $message = array();


    // CHECK FOR ALL INPUT FIELDS ARE FILL
    if (empty($fname) && empty($lname) && empty($username) && empty($email) && empty($password) && empty($password2)) {
        $message[] = "Please fill all the fields";
        print_r($message);
        // echo "No input";
        header("Location: " . $host . "/index.php?action=signup&message=" . $message[0]);
        exit();


        // CHECK FOR PASSWORD
    } else if (strlen($password) < 6) {
        $message[] = "Make sure your password has at least 6 letter";
        header("Location: " . $host . "/index.php?action=signup&message=" . $message[0]);
        exit();
    } else if ($password !== $password2) {
        $message[] = "Password didn't match";
        header("Location: " . $host . "/index.php?action=signup&message=" . $message[0]);
        exit();


        // CHECK FOR A VALID EMAIL
        // https://www.php.net/manual/en/filter.filters.validate.php
        // Validates whether the value is a valid e-mail address.
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message[] = "This is not valid email address";
        header("Location: " . $host . "/index.php?action=signup&message=" . $message[0]);
        exit();

        // IF ALL THE IF ELSE STATEMENT HAS FAILED THEN IT SAVE USER
    } else {
        // IF EVERYTHING WENT RIGHT
       
        $database1 = new dbConnect();
        $dbh1 = $database1->connect();
        $dbh1->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
         // CHECK FOR THE USERNAME THAT ALREADY REGISTED OR NOT
        $username_finding_sql = "SELECT * FROM adminuser WHERE username=:username";
        $username_finding_stmt = $dbh1->prepare($username_finding_sql);
        $username_finding_stmt->bindParam("username", $username);
        $username_finding_stmt->execute();
        $username_result = $username_finding_stmt->fetch();
        
        $database2 = new dbConnect();
        $dbh2 = $database2->connect();
        $dbh2->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
        // CHECK FOR THE EMAIL THAT ALREADY REGISTED OR NOT
        $email_finding_sql = "SELECT * FROM adminuser WHERE email=:email";
        $email_finding_stmt = $dbh2->prepare($email_finding_sql);
        $email_finding_stmt->bindParam("email", $email);
        $email_finding_stmt->execute();
        $email_result = $email_finding_stmt->fetch();
        


        // IF THERE THE EMAIL IS REGISTED 
        // WE WILL NOT THE REGISTERED
        if (empty($email_result) && empty($username_result)) {
            //print_r($email_result);
            //die();
            $signup_sql = "INSERT INTO adminuser(fname, lname, username, email, password) VALUES (:fname, :lname, :username, :email, :password)";
            $signup_stmt = $dbh1->prepare($signup_sql);
            $signup_stmt->bindParam("fname", $fname);
            $signup_stmt->bindParam("lname", $lname);
            $signup_stmt->bindParam("username", $username);
            $signup_stmt->bindParam("email", $email);
            $signup_stmt->bindParam("password", md5($password));
            $signup_stmt->execute();
            session_start();
            $_SESSION['UN'] = $username;
            $message[] = "User has registered successfully";
            header("Location: " . $host . "/login-interface/2fa-generate.php");
            exit();

            // IF THERE THE EMAIL IS NOT REGISTED 
            // WE WILL DO THE REGISER 
        } else {
            $message[] = "An user is already registered with this email or username";
            header("Location: " . $host . "/index.php?action=signup&message=" . $message[0]);
            exit();
        }
    }

    return $errors;

}
