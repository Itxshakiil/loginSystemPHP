<?php
if ($_SERVER['REQUEST_METHOD']=='POST') {
    // Importing sanitizeInput function to validate input data
    require('sanitizeInput.php');

    $errors = array();
    if (empty($_POST['email'])) {
        $errors['email']='Please Enter email';
    } else {
        $email=sanitizeInput($_POST['email']);
    }
    if (empty($_POST['password'])) {
        $errors['password']='Please Enter Your Password';
    } else {
        $password=sanitizeInput(($_POST['password']));
    }
    if (empty($errors)) {
        // Importing database Connection File
        require_once('database/connection.php');
        if (empty($e)) {
            $sql='SELECT * FROM users WHERE email=:email';
            $statement=$connection->prepare($sql);
            $statement->execute([':email'=>$email ]);
            $users=$statement->fetch(PDO::FETCH_OBJ);
            if (!empty($users)) {
                if ($email==$users->email && password_verify($password, $users->password)) {
                    session_start();
                    $_SESSION['user_id']=$users->user_id;
                    $_SESSION['name']=$users->name;
                    if (!empty($_SESSION['url'])) {
                        $url=$_SESSION['url'];
                    } else {
                        $url='index.php';
                    }
                    header('location:'.$url);
                    $_SESSION['url']='';
                } else {
                    $errors['invalid']="Invalid Username/Password";
                }
            } else {
                $errors['not-found']="No user Found Please Try Again or Contact Admin.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
    <link rel="stylesheet" href="css/style.min.css">
</head>
<body>
    <div class=" container center">
        <div class="card">
        <h2 class="lead-text">Log In</h2>
        <form action="" method="post">
        <?php
            if (!empty($message)):
                    ?>
                    <div class="text-blue mb-2"> <?=$message; ?></div>
            <?php endif;?>
            <?php
            if (isset($errors)):
            foreach ($errors as $error):
            ?>
            <div class="text-red mb-2"><?=$error; ?></div>
            <?php endforeach ?>
            <?php endif ?>
            <div class="w-full mb-2">
                <label for="email">Email address</label>
                <input type="email" name="email" id="email"  placeholder="Enter Your Email"  required>
            </div>
            <div class="w-full mb-2">
                <label for="password">Password</label>
                <input type="password"  name="password" id="password" aria-describedby="passwordHelp" placeholder="Password" required  >
                <small id="passwordhelp" class="form-text text-muted">Password is case-sensitive.</small>
            </div>
            <input type="submit" class="btn btn-primary" value="Log In">
        </form>
        <p class="text-center mt-2 text-blue">Dont Have an account <a href="./welcome.php" class="btn">Register!</a></p>
        </div>
    </div>
</body>
</html>