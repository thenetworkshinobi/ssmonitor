<!DOCTYPE html>
<html lang="en">
<?php 
// session_status() === PHP_SESSION_ACTIVE ?: session_start();

// if(!isset($_SESSION['email'])){
//     header("Location: index.php");
// }
$base = dirname(dirname(__FILE__));
?>


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width", initial-scale="1.0">
    <?php include($base .'/config/variables.php'); ?>
    <title>Shinobi Dashboard</title>
    <link rel="stylesheet" href="<?php echo $host; ?>css/style.css">
    <script src="https://kit.fontawesome.com/8525e48e15.js" crossorigin="anonymous"></script>
    <link rel="icon" href="img/icon.svg" type="image/svg" sizes="16x16">    
</head>

<body>
    <nav>
        <input type="checkbox" id="check">
        <label for="check" class="checkbtn">
            <i class="fas fa-bars"></i>
        </label>
        <label class="logo"><a href="<?php echo $host; ?>/?action=index.php">Shinobi Dashboard</a></label>
        <ul>
            
                <?php
                    session_start();
                    if (isset($_SESSION['username'])) {
                ?>
                    <?php if (isset($_SESSION['id_secret']) && ($_SESSION['id_secret']) == true) { ?>
                        <li><a href="<?php echo $host; ?>/?action=dashboard">Dashboard</a></li>
                    <?php }?>

                    <li><a href="<?php echo $host; ?>/login-interface/signout.php">Signout</a></li>
                <?php
                    } else {
                ?>
                    <li><a href="<?php echo $host; ?>/?action=index">Home</a></li>
                    <li><a href="<?php echo $host; ?>/?action=signin">Signin</a></li>
                    <li><a href="<?php echo $host; ?>/?action=signup">Signup</a></li>
                <?php
                }
            ?>
        </ul>
        
    </nav>        
<main>


        