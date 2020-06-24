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
            $password=password_hash($pwd, PASSWORD_DEFAULT);;
        
            $sql='UPDATE users SET password=:password WHERE user_id=:user_id';
            $statement=$connection->prepare($sql);
            if ($statement->execute([':password'=>$password,':user_id'=>$user_id])) {
                $message='Your password is <strong style="background-color:#ff0;"> '.$pwd.'</strong> <br> Don\'t Try Copy Paste , Type manually.	';
            }
        }
    }
} else {
    $error='Please enter answer to go further.';
}