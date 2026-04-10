<?php

include '../components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};



if(isset($_POST['send'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $number = $_POST['number'];
   $number = filter_var($number, FILTER_SANITIZE_STRING);
   $msg = $_POST['msg'];
   $msg = filter_var($msg, FILTER_SANITIZE_STRING);

   $select_message = $conn->prepare("SELECT * FROM `messages` WHERE name = ? AND email = ? AND number = ? AND message = ?");
   $select_message->execute([$name, $email, $number, $msg]);

   if($select_message->rowCount() > 0){
      $message[] = 'already sent message!';
   }
   else{
      $insert_message = $conn->prepare("INSERT INTO `messages`(user_id, name, email, number, message) VALUES(?,?,?,?,?)");
      $insert_message->execute([$user_id, $name, $email, $number, $msg]);

      $message[] = 'message sent successfully!';
   }
?>
            <script src="https://unpkg.com/sweetalert@2.1.2/dist/sweetalert.min.js"></script>
            <script>
                window.addEventListener('load',function(){
                    swal({
                        title: "<?php foreach($message as $m){ echo $m; } ?>",
                        icon: "success",
                        button: "Ok",
                    });
                });
            </script>
<?php
    
    
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Enquiry</title>

    <link href="https://fonts.googleapis.com/css2?family=Niconne&display=swap" rel="stylesheet">
   
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="style.css">
   <link rel="stylesheet" href="../Home/home_style.css">

</head>
<body>
   <!-- cotact section start -->
   <div class="heading">
        <h1>Enquiry</h1>
    </div>
    
    <section class="contact">
        <form method="post">
            <h3>get in touch</h3>
            <input type="text" name="name" placeholder="enter your name" required maxlength="20" class="box">
            <input type="email" name="email" placeholder="enter your email" required maxlength="50" class="box">
            <input type="number" name="number" min="0" max="9999999999" placeholder="enter your number" required onkeypress="if(this.value.length == 10) return false;" class="box">
            <textarea name="msg" class="box" placeholder="enter your message" cols="30" rows="10"></textarea>
            <input type="submit" value="send message" name="send" class="btn">
        </form>
    </section>

    <!-- custom js file link -->
    <script src="../Home/main.js"></script>

    <?php include '../components/footer.php'; ?>
</body>
</html>

