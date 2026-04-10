<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
};

if(isset($_POST['add_product']))
{

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $price1 = $_POST['price1'];
   $price1 = filter_var($price1, FILTER_SANITIZE_STRING);
   $price2 = $_POST['price2'];
   $price2 = filter_var($price2, FILTER_SANITIZE_STRING);
   $price3 = $_POST['price3'];
   $price3 = filter_var($price3, FILTER_SANITIZE_STRING);

   $details = $_POST['details'];
   $details = filter_var($details, FILTER_SANITIZE_STRING);
   $category = $_POST['cname'];
   $category = filter_var($category, FILTER_SANITIZE_STRING);

   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = '../uploaded_img/'.$image;

   

   $select_products = $conn->prepare("SELECT * FROM `products` WHERE name = ?");
   $select_products->execute([$name]);

   if($select_products->rowCount() > 0)
   {
      $message[] = 'product name already exists!';
   }
   else
   {
        $stmt = $conn->prepare("SELECT * FROM categories WHERE name = ?");
        $stmt->execute([$category]);

        if($row=$stmt->fetch())
        {
            $cid = $row[0];
            $insert_products = $conn->prepare("INSERT INTO `products`(cid, name, details, price1, price2, price3, image) VALUES(?,?,?,?,?,?,?)");
            $insert_products->execute([$cid, $name, $details, $price1, $price2, $price3, $image]);

            if($insert_products)
            {
                if($image_size > 2000000000)
                {
                    $message[] = 'image size is too large!';
                }
                else
                {
                    move_uploaded_file($image_tmp_name, $image_folder);
                    $message[] = 'new product added!';
                }
            }
        }
        else
        {
            $message[] = 'category does not exist!';
        }
    }  

};

if(isset($_GET['delete'])){

   $delete_id = $_GET['delete'];
   $delete_product_image = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
   $delete_product_image->execute([$delete_id]);
   if($delete_product_image->rowCount() > 0)
   {
        $fetch_delete_image = $delete_product_image->fetch(PDO::FETCH_ASSOC);
        unlink('../uploaded_img/'.$fetch_delete_image['image']);
   }
   $delete_product = $conn->prepare("DELETE FROM `products` WHERE id = ?");
   $delete_product->execute([$delete_id]);
   $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE pid = ?");
   $delete_cart->execute([$delete_id]);
   $message[] = 'product deleted!';
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>products</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="add-products">

   <h1 class="heading">add product</h1>

   <form action="" method="post" enctype="multipart/form-data">
      <div class="flex">
         <div class="inputBox">
            <span>category name (required)</span>
            <input type="text" class="box" required maxlength="100" placeholder="enter category name" name="cname">
         </div>
         <div class="inputBox">
            <span>product name (required)</span>
            <input type="text" class="box" required maxlength="100" placeholder="enter product name" name="name">
         </div>

         <div class="inputBox">
            <span>product price for 0.5 kg (required)</span>
            <input type="number" min="0" class="box" required max="9999999999" placeholder="enter product price for 0.5 kg" onkeypress="if(this.value.length == 10) return false;" name="price1">
         </div>
         <div class="inputBox">
            <span>product price for 0.75 kg (required)</span>
            <input type="number" min="0" class="box" required max="9999999999" placeholder="enter product price for 0.75 kg" onkeypress="if(this.value.length == 10) return false;" name="price2">
         </div>
         <div class="inputBox">
            <span>product price for 1 kg (required)</span>
            <input type="number" min="0" class="box" required max="9999999999" placeholder="enter product price for 1 kg" onkeypress="if(this.value.length == 10) return false;" name="price3">
         </div>

        <div class="inputBox">
            <span>image (required)</span>
            <input type="file" name="image" accept="image/jpg, image/jpeg, image/png, image/webp" class="box" required>
        </div>
         <div class="inputBox">
            <span>product details (required)</span>
            <textarea name="details" placeholder="enter product details" class="box" required maxlength="500" cols="30" rows="10"></textarea>
         </div>
         
      </div>
      
      <input type="submit" value="add product" class="btn" name="add_product">
   </form>

</section>

<section class="show-products">

   <h1 class="heading">products added</h1>

   <div class="box-container">

   <?php
      $select_products = $conn->prepare("SELECT * FROM `products`");
      $select_products->execute();
      if($select_products->rowCount() > 0){
         while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){ 
   ?>
   <div class="box">
      <img src="../uploaded_img/<?= $fetch_products['image']; ?>" alt="">
      <div class="name"><?= $fetch_products['name']; ?></div>

      <div class="price">&#8377<span><?= $fetch_products['price1']; ?></span>/- for 0.5 kg</div>
      <div class="price">&#8377<span><?= $fetch_products['price2']; ?></span>/- for 0.75 kg</div>
      <div class="price">&#8377<span><?= $fetch_products['price3']; ?></span>/- for 1 kg</div>

      

      <div class="flex-btn">
         <a href="update_product.php?update=<?= $fetch_products['id']; ?>" class="option-btn">update</a>
         <a href="products.php?delete=<?= $fetch_products['id']; ?>" class="delete-btn" onclick="return confirm('delete this product?');">delete</a>
      </div>
   </div>
   <?php
         }
      }else{
         echo '<p class="empty">no products added yet!</p>';
      }
   ?>
   
   </div>

</section>








<script src="../js/admin_script.js"></script>
   
</body>
</html>