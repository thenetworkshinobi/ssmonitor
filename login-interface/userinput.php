<?php
class userFields
{
    public function signup()
    {
        $errors = isset($_GET['message']) ? $_GET['message'] : '';
        if ($errors) {
            $errorMessage = '
            <div style="padding:10px; color:red; background:white;">
                ' . $errors . '
            </div>
            ';
        } else {
            $errorMessage = "";
        }

        return '
        <form action="login-interface/signup.php" class="col s12" method="POST"> 
                ' . $errorMessage . '
            <div class="row">
                <div class="">
                    <input id="fname" name="fname" type="text" class="validate">
                    <label for="fname">First Name</label>
                </div>
                <div class="">
                    <input id="lname" name="lname" type="text" class="validate">
                    <label for="lname">Last Name</label>
                </div>
                <div class="">
                    <input id="username" name="username" type="text" class="validate">
                    <label for="username">Username</label>
                </div>
                <div class="">
                    <input id="email" name="email" type="email" class="validate">
                    <label for="email">Email</label>
                </div>
            </div>
            <div class="row">
                <div class="">
                    <input id="password" name="password" type="password" class="validate">
                    <label for="password">Password</label>
                </div>
                <div class="">
                    <input id="password2" name="password2" type="password" class="validate">
                    <label for="password2">Repeat Password</label>
                </div>
            </div>
            <div class="row">
                <button class="b1" type="submit" name="signup">
                    Sign Up
                </button>
            </div>
        </form>
        ';
    }


    public function signin()
    {
        $message = isset($_GET['message']) ? $_GET['message'] : '';
        if ($message) {
            $successMessage = '
            <div style="padding:10px; color:green; background:white;">
                ' . $message . '
            </div>
            ';
        } else {
            $successMessage = "";
        }
        return '
        <form action="login-interface/signin.php" class="col s12" method="POST" >
        ' . $successMessage . '
            <div class="row">
                <div class="">
                    <input id="username"  name="username"  type="text" class="validate">
                    <label for="username" >Username</label>
                </div>
                <div class="">
                    <input id="password" name="password" type="password" class="validate">
                    <label for="password" >Password</label>
                </div>
            </div>
            <div class="row">
                <button class="b1" type="submit" name="signin">
                    Sign In
                </button>
            </div>
        </form>
        ';
    }
    public function totp()
    {
        $message = isset($_GET['message']) ? $_GET['message'] : '';
        if ($message) {
            $successMessage = '
            <div style="padding:10px; color:green; background:white;">
                ' . $message . '
            </div>
            ';
        } else {
            $successMessage = "";
        }
        return '
        <form action="login-interface/totp.php" class="" method="POST" >
        ' . $successMessage . '
            <div class="row">
                <div class="">
                    <input id="2fa"  name="2fa"  type="text" class="validate">
                    <label for="2fa" >2FA Code</label>
                </div>               
            </div>
            <div class="row">
                <button class="b1" type="submit" name="totp">
                    Submit
                </button>
            </div>
        </form>
        ';
    }

    public function dashboard()
    {
        $message = isset($_GET['message']) ? $_GET['message'] : '';
        if ($message) {
            $successMessage = '
            <div style="color:green;">
                ' . $message . '
            </div>
            ';
        } else {
            $successMessage = "Welcome to dashboard";
        }
        return '
      <div class="">
        <div class="">
            <div class="">
            <div class="">
                <img src="images/yuna.jpg" alt="" class="circle responsive-img"> <!-- notice the "circle" class -->
            </div>
            <div class="">
                <span >
                        ' . $successMessage . '
                </span>
            </div>
            </div>
        </div>
    </div>
        ';
    }
    public function default()
    {
        return '
        <div class="container">
        <h2>Welcome !</h2>
        
        </div>';
    }

}
