<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reset Password</title>
    <link rel="stylesheet" href="css/style.min.css">
<?php
session_start();
$email=$_SESSION['email'];
$userid=$_SESSION['user_id'];
$username=$_SESSION['username'];
require_once('database/connection.php');
require_once('sanitizeInput.php');
$user_id=sanitizeInput($_SESSION['user_id']);
?>
</head>
<body>
<?php
require_once("./change_password.php");
?>
<div class="container card">
<?php
if (!empty($message)) {
    echo '<form method="POST" action="login.php">';
} else {
    echo '
	<form method="POST" action="'.htmlspecialchars($_SERVER["PHP_SELF"]).'"> ';
}
?>

	<?php
             $sql='SELECT * FROM quest WHERE user_id=:user_id';
             $statement=$connection->prepare($sql);
             $statement->execute([':user_id'=>$user_id]);
             $users=$statement->fetch(PDO::FETCH_OBJ);
             if (!empty($users)) {
                 $question=$users->question;
             } else {
                 $error='no security question found associated with '.$email;
             }
             ?>
	<?php
            if (!empty($message)):
                    ?>
                    <div class="text-blue mb-2"> <?=$message; ?></div>
            <?php endif;?>
            <?php
            if (isset($error)):
            ?>
            <div class="text-red mb-2"><?=$error; ?></div>
            <?php endif
    ?>
	<?php
    if (!empty($message)):?>
		<div class="w-full mb-2">
                <label for="email">Email address</label>
                <input type="email" class="form-control" name="email" id="email"  placeholder="Enter Your Email"  required>
            </div>
            <div class="w-full mb-2">
                <label for="password">Password</label>
                <input type="password" class="form-control"  name="password" id="password" aria-describedby="passwordHelp" placeholder="Password" required  >
                <small id="passwordhelp" class="form-text text-muted">Password is case-sensitive.</small>
            </div>
            <input type="submit" class="btn btn-primary" value="Log In">
	<?php
        else:?>
		<div class="w-full mb-2">
			<label for="question">Secrity Question</label>
			<input type="text" class="form-control bold" name="question" id="question"  value="<?=$question; ?> "  required>
		</div>
		<div class="w-full mb-2">
			<label for="answer">Answer</label>
			<input type="text" class="form-control"  name="answer" id="answer"  placeholder="answer" required autofocus >
		</div>
			<input type="submit" class="btn" name="reset_pwd" Value="Reset Password"   />
	<?php
    endif;
    ?>
	
	</form>

		<a class="register" href="register.php" title="Sign up" >Sign up</a>
		</div>
        
</div>
</body>
</html>