<?php 
require('database.php');
session_start();

if(isset($_GET['email']) && isset($_GET['code'])){
    $_SESSION['email'] = $_GET['email'];
    $code = $_GET['code'];

    // Check against the database to see if correct link
    $query = $pdo->prepare("SELECT * FROM reset WHERE email = ?");
    $query->execute([$_SESSION['email']]);
    $from_reset = $query->fetch();

    //echo $from_reset['code'];

    if($code != $from_reset['code']){
        $expired = 'Sorry, your link is invalid or has expired!';
    }

}

if(isset($_POST['reset'])){
    $pass1 = $_POST['pass1'];
    $pass2 = $_POST['pass2'];

    if($pass1 == $pass2){
        $hashed_password = password_hash($pass1, PASSWORD_DEFAULT);

    } else {
        $error = 'Passwords do not match!';
    }
    
    $email = $_SESSION['email'];

    // Update password
    if(empty($error)){
        $query = $pdo->prepare("UPDATE users SET password = ? WHERE email = ?");
        $query ->execute([$hashed_password, $email]);

        $msg = 'Successfully updated your password! <a class="btn btn-success" href="index.php"> >>Log In</a>';

        session_destroy();
    }

}


?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <title>Reset Password</title>
  </head>
  <body>

<div class="container mt-5 mb-5" style="margin: 0 auto; width: 60%;">

    <!-- Error Notification -->
    <?php if(isset($error)){echo '<p class="alert-danger rounded p-3">'.$error.'</p>';}?>
    <?php if(isset($expired)){echo '<p class="alert-danger rounded p-3">'.$expired.'</p>';}?>

    <?php if(isset($msg)){echo '<p class="alert-success rounded p-3">'.$msg.'</p>';}?>

    <!-- Reset Form -->

    <?php 
    if(!isset($expired) && isset($_GET['code'])){
        echo '
        <form action="reset_password.php" method="post">
            <h4>Please enter your new password:</h4>
            <input type="text" name="pass1" class="form-control" placeholder="New password..." required>
            <input type="text" name="pass2" class="form-control mt-2" placeholder="Repeat new password..." required>
            <input type="submit" name="reset" value="Reset Password" class="form-control btn btn-warning mt-2">
        </form>
        ';
    }
  
    ?>

</div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>