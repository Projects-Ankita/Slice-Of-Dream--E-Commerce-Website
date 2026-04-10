<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
};

if(isset($_POST['add_category']))
{

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $type = $_POST['type'];
   $type = filter_var($type, FILTER_SANITIZE_STRING);

   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = '../uploaded_img/'.$image;


   $select_categories = $conn->prepare("SELECT * FROM `categories` WHERE name = ?");
   $select_categories->execute([$name]);

   if($select_categories->rowCount() > 0)
   {
      $message[] = 'category name already exists!';
   }
   else
   {
        $insert_categories = $conn->prepare("INSERT INTO `categories`(name, type, image) VALUES(?,?,?)");
        $insert_categories->execute([$name, $type, $image]);

        if($insert_categories)
        {
            if($image_size > 2000000000)
            {
                $message[] = 'image size is too large!';
            }
            else
            {
                move_uploaded_file($image_tmp_name, $image_folder);
                $message[] = 'new category added!';
            }
        }
    }  

};

if(isset($_GET['delete'])){

   $delete_id = $_GET['delete'];

   $delete_products_image = $conn->prepare("SELECT * FROM `products` WHERE cid = ?");
   $delete_products_image->execute([$delete_id]);
   if($delete_products_image->rowCount() > 0)
   {
   $fetch_delete_image = $delete_products_image->fetch(PDO::FETCH_ASSOC);
   unlink('../uploaded_img/'.$fetch_delete_image['image']);
   }

   $delete_products = $conn->prepare("DELETE FROM `products` WHERE id = ?");
   $delete_products->execute([$delete_id]);

   $delete_categories_image = $conn->prepare("SELECT * FROM `categories` WHERE id = ?");
   $delete_categories_image->execute([$delete_id]);
   $fetch_delete_image = $delete_categories_image->fetch(PDO::FETCH_ASSOC);
   unlink('../uploaded_img/'.$fetch_delete_image['image']);

   $delete_categories = $conn->prepare("DELETE FROM `categories` WHERE id = ?");
   $delete_categories->execute([$delete_id]);

   $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE pid = ?");
   $delete_cart->execute([$delete_id]);

   $message[] = 'category deleted!';
   
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>categories</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="add-categories">

   <h1 class="heading">add category</h1>

   <form action="" method="post" enctype="multipart/form-data">
        <div class="flex">
            <div class="inputBox">
                <span>category name (required)</span>
                <input type="text" class="box" required maxlength="100" placeholder="enter category name" name="name">
            </div>
            <div class="inputBox">
                <span>category type (required)</span>
                <input type="text" class="box" required maxlength="100" placeholder="enter category type" name="type">
            </div>
            <div class="inputBox">
                <span>image (required)</span>
                <input type="file" name="image" accept="image/jpg, image/jpeg, image/png, image/webp" class="box" required>
            </div>   
        </div>
        <input type="submit" value="add category" class="btn" name="add_category">
   </form>

</section>

<section class="show-categories">

   <h1 class="heading">categories added</h1>

   <div class="box-container">

   <?php
      $select_categories = $conn->prepare("SELECT * FROM `categories`");
      $select_categories->execute();
      if($select_categories->rowCount() > 0){
         while($fetch_categories = $select_categories->fetch(PDO::FETCH_ASSOC)){ 
   ?>
   <div class="box">
      <img src="../uploaded_img/<?= $fetch_categories['image']; ?>" alt="">
      <div class="name"><?= $fetch_categories['name']; ?></div>
      <div class="flex-btn">
         <a href="update_category.php?update=<?= $fetch_categories['id']; ?>" class="option-btn">update</a>
         <a href="categories.php?delete=<?= $fetch_categories['id']; ?>" class="delete-btn" onclick="return confirm('delete this category?');">delete</a>
      </div>
   </div>
   <?php
         }
      }else{
         echo '<p class="empty">no categories added yet!</p>';
      }
   ?>
   
   </div>

</section>








<script src="../js/admin_script.js"></script>
   
</body>
</html>