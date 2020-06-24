<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="css/style.min.css">
</head>
<?php
if ($_SERVER['REQUEST_METHOD']=='POST') {
    // Importing sanitizeInput function to valiat input data
    require('sanitizeInput.php');

    $email=sanitizeInput($_POST['email']);
    if (empty($errors)) {
        // Importing database Connection File
        require_once('database/connection.php');
        if (empty($e)) {
            $sql='SELECT * FROM users WHERE email=:email';
            $statement=$connection->prepare($sql);
            $statement->execute([':email'=>$email ]);
            $users=$statement->fetch(PDO::FETCH_OBJ);
            if (!empty($users)) {
                session_start();
                $_SESSION['user_id']=$users->user_id;
                $_SESSION['username']=$users->name;
                $_SESSION['email']=$users->email;
                header('location:reset_password.php');
                exit();
            } else {
                $errors['notfound']='no account associated with '.$email;
            }
        }
    }
}
?>
<body>
<div class="container card">
	
<h2 class="lead-text">Reset Password</h2>
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>">
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
		<input type="submit" class="btn" name="chng_pwd" Value="Next"   />
	</form>
</div>	
</body>
</html>
