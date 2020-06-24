<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/main.css">
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
if ($_SERVER['REQUEST_METHOD']=='POST') {
    if (isset($_POST['answer'])) {
        $answer=sanitizeInput($_POST['answer']);
        $sql='SELECT * FROM quest WHERE user_id=:user_id';
        $statement=$connection->prepare($sql);
        $statement->execute([':user_id'=>$user_id]);
        $users=$statement->fetch(PDO::FETCH_OBJ);
        if (!empty($users)) {
            $ans=$users->answer;
        }
        if ($answer==$ans) {
            $pwd=substr(hash('md5', rand()), 0, 8);
            $password=md5($pwd);
        
            $sql='UPDATE users SET password=:password WHERE user_id=:user_id';
            $statement=$connection->prepare($sql);
            if ($statement->execute([':password'=>$password,':user_id'=>$user_id])) {
                echo $password;
                $message='Your password is <strong style="background-color:#ff0;"> '.$pwd.'</strong> <br> Don\'t Try Copy Paste , Type manually.	';
            }
        }
    }
} else {
        $errors['no_answer']='Please enter answer to go further.';
    }
?>
<div class="container">
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
                    <div class="bg-info  mb-3"> <?=$message; ?></div>
            <?php endif;?>
            <?php
            if (isset($errors)):
            ?>
            <div class="bg-danger mb-2"><?=$error; ?></div>
            <?php endif
    ?>
	<?php
    if (!empty($message)):?>
		<div class="form-group">
                <label for="email">Email address</label>
                <input type="email" class="form-control" name="email" id="email"  placeholder="Enter Your Email"  required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control"  name="password" id="password" aria-describedby="passwordHelp" placeholder="Password" required  >
                <small id="passwordhelp" class="form-text text-muted">Password is case-sensitive.</small>
            </div>
            <input type="submit" class="btn btn-primary" value="Log In">
	<?php
        else:?>
		<div class="form-group">
			<label for="question">Secrity Question</label>
			<input type="text" class="form-control bold" name="question" id="question"  value="<?=$question; ?> "  required>
		</div>
		<div class="form-group">
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