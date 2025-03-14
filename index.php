<?php
require_once("./config/variables.php");
require_once("./partials/header.php");
?>

<main>
    <div class="container">
        <?php
            //session_start();
            require_once('login-interface/userinput.php');
            $action = isset($_GET['action']) ? $_GET['action'] : '';
            $userInterface = new userFields();
            switch ($action) {
                case 'signup':
                    if (isset($_SESSION['username'])) {
                        header("Location: " . $host . "/?action=dashboard");
                        exit();
                    }
                    echo $userInterface->signup();
                    break;
                case 'signin':
                    if (isset($_SESSION['username'])) {
                        header("Location: " . $host . "/?action=dashboard");
                        exit();
                    }
                    echo $userInterface->signin();
                    break;
                case 'dashboard':
                    if (!isset($_SESSION['username'])) {
                        header("Location: " . $host . "/?action=signin");
                        exit();
                    }
                    else if($_SESSION['id_secret'] == true){
                        header("Location: " . $host . "/dashboard/dashboard-home.php");
                        
                    }
                    else {
                        echo $userInterface->default();
                        
                    }
                    
                    break;

                default:
                    if (isset($_SESSION['username'])) {
                        if (isset($_SESSION['id_secret']) && $_SESSION['id_secret'] == true){
                            header("Location: " . $host . "/?action=dashboard");
                            exit();
                        }
                        else{
                            echo $userInterface->totp();
                            
                        }                        
                    } else {
                        echo $userInterface->default();
                        
                    }
                    //break;
            }
        ?>
    </div>
  
</main>

<?php require_once("./partials/footer.php"); ?>