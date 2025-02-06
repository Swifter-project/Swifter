<?php
session_start();
require_once './functions.php';
require_once dirname(__DIR__) . '/static/classes/Admin.php';

if(validate_form(['username', 'password'])){
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    $admin = new Admin();
    $admin->username = $username;
    $admin->password = $password;
    
    if($admin->login()){
        $_SESSION['admin'] = $username;
        redirect('./index');
    }else{
        $error = '<div class="alert alert-danger">Invalid username or password</div>';
    }
}
;?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../static/css/bootstrap.min.css">
    <link rel="stylesheet" href="../static/css/admin.css">
    <title>DELI | ADMIN</title>
</head>

<body>
    <header><?php require './header.php';?></header>
    <main>
        <?php
            if(isset($error)){
                echo $error;
            }
        ?>
        <div class="admin-login">
            <form action="login.php" method="post">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" name="username" id="username" class="form-control" placeholder="Enter Username">
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" class="form-control" placeholder="Enter Password">
                </div>
                <div class="form-group">
                    <input type="submit" value="Login" class="btn btn-primary">
                </div>
            </form>
        </div>
    </main>
    <footer><?php require './footer.php';?></footer>
    <script src="../static/js/jquery.min.js"></script>
    <script src="../static/js/bootstrap.min.js"></script>
    <script src="../static/js/script.js"></script>
</body>

</html>