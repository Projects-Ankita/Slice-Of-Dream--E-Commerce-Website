<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
}

if(isset($_POST['update']))
{
   $id = $_POST['id'];
   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $type = $_POST['type'];
   $type = filter_var($type, FILTER_SANITIZE_STRING);

   $select_categories = $conn->prepare("SELECT * FROM `categories` WHERE name = ?");
   $select_categories->execute([$name]);
   $row = $select_categories->fetch(PDO::FETCH_ASSOC);

   if($row['id'] != $id && $select_categories->rowCount() > 0)
   {
      $message[] = 'category name already exists!';
   }
   else
   {
        $update_category = $conn->prepare("UPDATE `categories` SET name = ?, type = ? WHERE id = ?");
        $update_category->execute([$name, $type, $id]);

        $message[] = 'category updated successfully!';

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
                $update_image = $conn->prepare("UPDATE `categories` SET image = ? WHERE id = ?");
                $update_image->execute([$image, $id]);
                move_uploaded_file($image_tmp_name, $image_folder);
                unlink('../uploaded_img/'.$old_image);
                $message[] = 'image updated successfully!';
            }
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
   <title>update category</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="update-category">

   <h1 class="heading">update category</h1>

   <?php
      $update_id = $_GET['update'];
      $select_categories = $conn->prepare("SELECT * FROM `categories` WHERE id = ?");
      $select_categories->execute([$update_id]);
      if($select_categories->rowCount() > 0){
         while($fetch_categories = $select_categories->fetch(PDO::FETCH_ASSOC)){ 
   ?>
   <form action="" method="post" enctype="multipart/form-data">
      <input type="hidden" name="id" value="<?= $fetch_categories['id']; ?>">
      <input type="hidden" name="old_image" value="<?= $fetch_categories['image']; ?>">
      <div class="image-container">
         <div class="main-image">
            <img src="../uploaded_img/<?= $fetch_categories['image']; ?>" alt="">
         </div>
      </div>

      <span>update name</span>
      <input type="text" name="name" required class="box" maxlength="100" placeholder="enter category name" value="<?= $fetch_categories['name']; ?>">
      <span>update type</span>
      <input type="text" name="type" required class="box" maxlength="100" placeholder="enter category type" value="<?= $fetch_categories['type']; ?>">
      
      <span>update image</span>
      <input type="file" name="image" accept="image/jpg, image/jpeg, image/png, image/webp" class="box">
      <div class="flex-btn">
         <input type="submit" name="update" class="btn" value="update">
         <a href="categories.php" class="option-btn">go back</a>
      </div>
   </form>
   
   <?php
         }
      }else{
         echo '<p class="empty">no category found!</p>';
      }
   ?>

</section>




<script src="../js/admin_script.js"></script>
   
</body>
</html>