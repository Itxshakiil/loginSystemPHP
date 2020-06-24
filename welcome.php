<?php
if ($_SERVER['REQUEST_METHOD']=='POST') {
    // Importing database Connection File
    require_once('database/connection.php');
    // Importing sanitizeInput function to valiat input data
    require('sanitizeInput.php');
    $errors = array();
 
    // Procesing name
    if (empty($_POST['first-name'])) {
        $errors['name']='Please Enter Your name';
    } else {
        $name=sanitizeInput(($_POST['first-name']));
    }
    if (!empty($_POST['last-name'])) {
        $lastName=sanitizeInput(($_POST['last-name']));
        $name = $name.' '.$lastName;
    }
    if (empty($_POST['email'])) {
        $errors['email']='Please Enter Your Email';
    } else {
        $email=sanitizeInput(($_POST['email']));
    }

    if (empty($_POST['password'])) {
        $errors['password']='Please Enter Your Password';
    } else {
        $password=sanitizeInput(($_POST['password']));
        // Hashing password
        $password =  password_hash($password, PASSWORD_DEFAULT);;
    }

    if (empty($_POST['phone-number'])) {
        $errors['phoneNumber']='Please Enter Your phone-number';
    } else {
        $phoneNumber=sanitizeInput(($_POST['phone-number']));
    }

    if (empty($_POST['date-of-birth'])) {
        $errors['dob']='Please Enter Your date-of-birth';
    } else {
        $dob=$_POST['date-of-birth'];
    }

    if (empty($errors)) {
        if (empty($e)) {
            // Check if Email is already registered for any account
    
            $sql="SELECT * FROM users WHERE email=:email";
            $statement=$connection->prepare($sql);
            if ($statement->execute([':email'=>$email])) {
                if ($count=$statement->rowCount()>0) {
                    $errors['exist']='Email already Registered try <a href="login.php">Log In</a>';
                } else {
                    $sql='INSERT INTO users(name, email , password , phone_number, dob) VALUES(:name, :email, :password, :phone_number, :dob)';
                    $statement=$connection->prepare($sql);
                    if ($statement->execute([':name'=> $name ,':email'=>$email , ':password'=>$password , ':phone_number'=>$phoneNumber , ':dob'=>$dob])) {
                        session_start();
                        $_SESSION['email']=$email;
                        header('location:step2.php');
                    } else {
                        $errors['query']='Error';
                    }
                }
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
    <title>Sign up</title>
    <!-- <link rel="stylesheet" href="css/bootstrap.css"> -->
    <link rel="stylesheet" href="css/style.min.css">
</head>
<body>
    <div class="container">
        <h2 class="lead-text">Sign up</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
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
            <div class="form-row">
                <div class="form-group col-md-6">
                <label for="first-name">First Name</label>
                <input type="text" class="form-control" id="first-name" name="first-name"
                placeholder="First Name"  required >
                </div>
                <div class="form-group col-md-6">
                <label for="last-name">Last name</label>
                <input type="text" class="form-control" id="last-name" name="last-name" placeholder="Last name">
                </div>
            </div>
            <div class="form-group">
                <label for="email">Email address</label>
                <input type="email" class="form-control" name="email" id="email" aria-describedby="emailHelp" placeholder="Enter Your Email"  >
                <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control"  name="password" id="password" placeholder="Password" required  >
            </div>  
            <div class="form-group">
                <label for="phone-number">Phone Number</label>
                <input type="number" class="form-control" id="phone-number" name="phone-number" title="Enter Valid Phone Number" aria-describedby="phone-help" placeholder="Phone Number" required >
                <small id="phone-help" class="form-text text-muted">We'll never share your phone number with anyone else.</small>
            </div>
            <div class="form-group">
                <label for="date-of-birth">Date of Birth</label>
                <input type="date" class="form-control" name="date-of-birth" id="date-of-birth" required   >
            </div>  
            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="exampleCheck1" required>
                <label class="form-check-label" for="exampleCheck1">Check me out</label>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                <input type="submit" class="form-control btn btn-primary" value="Sign Up">
                </div>
                <div class="form-group col-md-6">
                <a href="login.php" class="form-control btn btn-primary">Log In</a>
                </div>
            </div>
        </form>
    </div>
</body>
</html>