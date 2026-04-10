<?php
require('connection.inc.php');
require('functions.inc.php');
$msg='';
$result='';$uname='';$pass='';$email='';$username='';$password='';
if(isset($_POST['submit'])){
	$username=get_safe_value($con,$_POST['username']);
	$password=get_safe_value($con,$_POST['password']);
	$sql="SELECT * FROM admins where name='$username' and password='$password'";
    $sql3="SELECT * FROM users where name='$username' and password='$password'";
	$res=mysqli_query($con,$sql);
    $res3=mysqli_query($con,$sql3);
	$count=mysqli_num_rows($res);
    $count3=mysqli_num_rows($res3);
	if($count>0){
    $row=mysqli_fetch_assoc($res);
    $_SESSION['admin_id'] = $row['id'];
		$_SESSION['ADMIN_LOGIN']='yes';
		$_SESSION['ADMIN_USERNAME']=$username;
		header('location:../admin/dashboard.php');// Add the Location
		die();
	}
    elseif($count3>0){
      $row3=mysqli_fetch_assoc($res3);
      $_SESSION['user_id'] = $row3['id'];
      $_SESSION['USER_LOGIN']='yes';
      $_SESSION['USER_NAME']=$username;
      header('location:../home/index.php');//Add the Location
      die();
        
    }
    else{
		$msg="Please enter correct login details";	
	}}
if(isset($_POST['enter']))
    {
    $uname= get_safe_value($con,$_POST['uname']);
    $pass= get_safe_value($con,$_POST['pass']);
    $email= get_safe_value($con,$_POST['email']);
    $sql2="INSERT INTO users (name, password, email) VALUES ('$uname','$pass','$email')";
    $result=mysqli_query($con,$sql2);
    if ($result){
        echo"Record Insertion Successful";
     }
     else {
        echo"Error".$sql2;
     }
    }

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script
      src="https://kit.fontawesome.com/64d58efce2.js"
      crossorigin="anonymous"
    ></script>
    <link rel="stylesheet" href="style.css" />
    <title>Sign in & Sign up Form</title>
  </head>
  <body>
    <div class="container">
      <div class="forms-container">
        <div class="signin-signup">
          <form action="#" class="sign-in-form" method="post">
            <h2 class="title">Sign In</h2>
            <div class="input-field">
              <i class="fas fa-user"></i>
              <input type="text" name="username"placeholder="Username" required/>
            </div>
            <div class="input-field">
              <i class="fas fa-lock"></i>
              <input type="password" name="password"placeholder="Password" required/>
            </div>
            <input type="submit" name="submit" value="Login" class="btn solid" />
          </form>
          <form action="#" class="sign-up-form" method="post">
            <h2 class="title">Sign Up</h2>
            <div class="input-field">
              <i class="fas fa-user"></i>
              <input type="text" name="uname" placeholder="Username" required/>
            </div>
            <div class="input-field">
              <i class="fas fa-envelope"></i>
              <input type="email" name="email" placeholder="Email" required />
            </div>
            <div class="input-field">
              <i class="fas fa-lock"></i>
              <input type="password" name="pass" placeholder="Password" required/>
            </div>
            <input type="submit" name="enter"class="btn" value="Sign up" />
          </form>
        </div>
      </div>

      <div class="panels-container">
        <div class="panel left-panel">
          <div class="content">
            <h3>Hello!</h3>
            <p>
              Enter your personal details and start your journey with us
            </p>
            <button class="btn transparent" id="sign-up-btn">
              Sign up
            </button>
          </div>
        </div>
        <div class="panel right-panel">
          <div class="content">
            <h3>Welcome Back!</h3>
            <p>
                To keep connected with us please login with your personal info
            </p>
            <button class="btn transparent" id="sign-in-btn">
              Sign in
            </button>
          </div>
        </div>
      </div>
    </div>
   <script src="app.js"></script>
  </body>
</html>