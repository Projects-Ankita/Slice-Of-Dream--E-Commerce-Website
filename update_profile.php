<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
}

if(isset($_POST['submit'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);

   $pid = $_POST['pid'];
   $pid = filter_var($pid, FILTER_SANITIZE_STRING);

   $update_profile_name = $conn->prepare("UPDATE `admins` SET name = ? WHERE id = ?");
   $update_profile_name->execute([$name, $admin_id]);

   $empty_pass = 'da39a3ee5e6b4b0d3255bfef95601890afd80709';
   $prev_pass = $_POST['prev_pass'];
   $old_pass = $_POST['old_pass'];
   $old_pass = filter_var($old_pass, FILTER_SANITIZE_STRING);
   $new_pass = $_POST['new_pass'];
   $new_pass = filter_var($new_pass, FILTER_SANITIZE_STRING);
   $confirm_pass = $_POST['confirm_pass'];
   $confirm_pass = filter_var($confirm_pass, FILTER_SANITIZE_STRING);

   if($old_pass == $empty_pass){
      $message[] = 'please enter old password!';
   }elseif($old_pass != $prev_pass){
      $message[] = 'old password not matched!';
   }elseif($new_pass != $confirm_pass){
      $message[] = 'confirm password not matched!';
   }else{
        if($new_pass != $empty_pass){
            $update_admin_pass = $conn->prepare("UPDATE `admins` SET password = ? WHERE id = ?");
            $update_admin_pass->execute([$confirm_pass, $admin_id]);
            $message[] = 'password updated successfully!';
            $old_image = $_POST['old_image'];
            $image = $_FILES['image']['name'];
            $image = filter_var($image, FILTER_SANITIZE_STRING);
            $image_size = $_FILES['image']['size'];
            $image_tmp_name = $_FILES['image']['tmp_name'];
            $image_folder = '../uploaded_img/'.$image;

            if(!empty($image))
            {
                if($image_size > 2000000000){
                $message[] = 'image size is too large!';
                }
                else{
                $update_image = $conn->prepare("UPDATE `admins` SET image = ? WHERE id = ?");
                $update_image->execute([$image, $pid]);
                move_uploaded_file($image_tmp_name, $image_folder);
                unlink('../uploaded_img/'.$old_image);
                $message[] = 'image updated successfully!';
                }
            }
        }else{
         $message[] = 'please enter a new password!';
      }
   }
   
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>update profile</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="form-container">
<?php
      $update_id = $_GET['update'];
      $select_accounts = $conn->prepare("SELECT * FROM `admins` WHERE id = ?");
      $select_accounts->execute([$update_id]);
      if($select_accounts->rowCount() > 0){
         while($fetch_accounts = $select_accounts->fetch(PDO::FETCH_ASSOC)){            
   ?>
    <form action="" method="post" enctype="multipart/form-data">
        <h3>update profile</h3>
        <input type="hidden" name="pid" value="<?= $fetch_accounts['id']; ?>">
        <input type="hidden" name="old_image" value="<?= $fetch_accounts['image']; ?>">
        <input type="hidden" name="prev_pass" value="<?= $fetch_profile['password']; ?>">
        <input type="text" name="name" value="<?= $fetch_profile['name']; ?>" required placeholder="enter your username" maxlength="20"  class="box" oninput="this.value = this.value.replace(/\s/g, '')">
        <input type="password" name="old_pass" placeholder="enter old password" maxlength="20"  class="box" oninput="this.value = this.value.replace(/\s/g, '')">
        <input type="password" name="new_pass" placeholder="enter new password" maxlength="20"  class="box" oninput="this.value = this.value.replace(/\s/g, '')">
        <input type="password" name="confirm_pass" placeholder="confirm new password" maxlength="20"  class="box" oninput="this.value = this.value.replace(/\s/g, '')">
        <input type="file" name="image" accept="image/jpg, image/jpeg, image/png, image/webp" class="box">
        <div class="flex-btn">
            <input type="submit" value="update now" class="btn" name="submit">
            <a href="admin_accounts.php" class="option-btn">go back</a>
        </div>

   </form>
   <?php 
         }
        }
    ?>

</section>












<script src="../js/admin_script.js"></script>
   
</body>
</html>