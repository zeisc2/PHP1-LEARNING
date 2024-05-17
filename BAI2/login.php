<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="stylesheet" href="style.css">
    <title>Login</title>
    <style>
        .red {
            color: red;
        }
    </style>
</head>
<body > 
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
include_once('./provider.php');
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $errors = [];
    if (empty($_POST['username'])) {
        $errors['username'] = "Username is required";
    }
    if (empty($_POST['password'])) {
        $errors['password'] = "Password is required";
    } elseif (strlen($_POST['password']) < 6) {
        $errors['password'] = "Password must be at least 6 characters long";
    }

    if (isset($conn) && count($errors) == 0) {
        $query = "SELECT * FROM user WHERE username = :username AND password = :password";
        $statement = $conn->prepare($query);
        $statement->execute([
            'username' => $_POST['username'],
            'password' => $_POST['password'],
        ]);
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            $_SESSION["username"] = $_POST["username"];
            if ($_POST["username"] === "admin") {
                echo "<script>alert('Đăng nhập thành công với tư cách admin');</script>";
                header("Location: ./crud.php");
                 
            } else {
                echo "<script>alert('Đăng nhập thành công với tư cách user');</script>";
                header("Location: user.html");
                 
            }
        } else {
            echo "<script>alert('Sai username hoặc password');</script>";
        }
    }
}

?>

    <div class="container">
        <div class="text-center">
         
        </div>
        <form class="user" method="POST" action="login.php">
               <h1>Login</h1>
            <div class="form-group">
                <input name="username" type="text" id="username"  placeholder="Enter username...">
            </div>
            <div class="red">
                <?php
                if (isset($errors['username'])) {
                    echo $errors['username'];
                }
                ?>
            </div>
            <div class="form-group">
                <input name="password" type="password" id="password" placeholder="Password">
            </div>
            <div class="red">
                <?php
                if (isset($errors['password'])) {
                    echo $errors['password'];
                }
                ?>
            </div>
            <button class="btn btn-primary btn-user btn-block" type="submit">Login</button>
        </form>
    </div>
    
</body>
</html>
