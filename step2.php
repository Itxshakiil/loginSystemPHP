<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/main.css">
<?php
    session_start();
    
$email=$_SESSION['email'];
echo $_SESSION['user_id'];
$user_id=$_SESSION['user_id'];
    
require_once('database/connection.php');

$sql='SELECT * FROM users WHERE email=:email';
         $statement=$connection->prepare($sql);
         $statement->execute([':email'=>$email]);
         $users=$statement->fetch(PDO::FETCH_OBJ);
         if (!empty($users)) {
             $user_id=$users->user_id;
             $username=$users->name;
         }
?>
<?php
    if ($_SERVER['REQUEST_METHOD']=='POST') {// Importing database Connection File
        require_once('database/connection.php');
        // Importing sanitizeInput function to valiat input data
        require('sanitizeInput.php');
    
        $errors = array();
        if (empty($_POST['question'])) {
            $errors['question']='Enter Your Security Question';
        } else {
            $question=sanitizeInput($_POST['question']);
        }
        if (empty($_POST['answer'])) {
            $errors['answer']='Enter Your Security Answer.';
        } else {
            $answer=sanitizeInput($_POST['answer']);
        }
        if (empty($errors)) {
            if (empty($e)) {

                // check if already security question created or not

                //if not then
                $sql='INSERT INTO quest(user_id, question, answer ) VALUES(:user_id,:question, :answer)';
                $statement=$connection->prepare($sql);
                if ($statement->execute([':user_id'=> $user_id ,':question'=> $question ,':answer'=>$answer ])) {
                    $message='Complete';
                    header('location:index.php');
                } else {
                    $errors['query']='Error';
                }
            }
        }
    }
    
    
    ?>
</head>
<body>
<div class="container">
<div class="panel">
<div class="panel-heading">Welcome <?=$username ?></div>
</div>
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>">
	<?php
        if (!empty($message)):
                ?>
                <div class="bg-info  mb-3"> <?=$message; ?></div>
        <?php endif;?>
        <?php
        if (isset($errors)):
        foreach ($errors as $error):
        ?>
        <div class="bg-danger mb-2"><?=$error; ?></div>
        <?php endforeach ?>
        <?php endif ?>
            <div class="form-group">
                <label for="question">Secrity Question</label>
                <input type="text" class="form-control" name="question" id="question"  placeholder="Enter Your Security Question"  required>
            </div>
            <div class="form-group">
                <label for="answer">Answer</label>
                <input type="text" class="form-control"  name="answer" id="answer" aria-describedby="answerHelp" placeholder="answer" required  >
                <small id="answerhelp" class="form-text text-muted">This will be used to recover/reset password and can't be changed.</small>
            </div>
            <input type="submit" class="btn btn-primary" value="Complete">
	</form>
</div>
</body>
</html>
