<?php

include '../components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

if(isset($_POST['add_to_cart']))
{
    if(isset($_SESSION['user_id'])==false)
    {
        $error_message[] = "Please login first";
    }

    $pid = $_POST['pid'];
    $pid = filter_var($pid, FILTER_SANITIZE_STRING);
    $p_name = $_POST['p_name'];
    $p_name = filter_var($p_name, FILTER_SANITIZE_STRING);
    $price = $_POST['price'];
    $price = filter_var($price, FILTER_SANITIZE_STRING);
    $p_image = $_POST['p_image'];
    $p_image = filter_var($p_image, FILTER_SANITIZE_STRING);
    
    $weight = "500gm";

    $check_cart_numbers = $conn->prepare("SELECT * FROM `cart` WHERE name = ? AND user_id = ? AND weight = ?");
    $check_cart_numbers->execute([$p_name, $user_id, $weight]);

    if($check_cart_numbers->rowCount() > 0){
        $message[] = 'already added to cart!';
    }
    else
    {
        $insert_cart = $conn->prepare("INSERT INTO `cart`(user_id, pid, name, price, weight, image) VALUES(?,?,?,?,?,?)");
        $insert_cart->execute([$user_id, $pid, $p_name, $price, $weight, $p_image]);
        $message[] = 'added to cart!';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Search</title>
   
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="style.css">
   <link rel="stylesheet" href="../Home/home_style.css">

</head>
<body>
   
<?php include '../components/header.php'; ?>

<section class="search-form">
   <form action="" method="post">
      <input type="text" name="search_box" placeholder="search here..." maxlength="100" class="box" required>
      <button type="submit" class="fas fa-search" name="search_btn"></button>
   </form>
</section>


<section class="products">
    <?php
        if(isset($_POST['search_box']) OR isset($_POST['search_btn'])){
            $search_box = $_POST['search_box'];
            $select_products = $conn->prepare("SELECT * FROM `products` WHERE name LIKE '%{$search_box}%'"); 
            $select_products->execute();
            if($select_products->rowCount() > 0){
                while($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)){
    ?>
    
        <div class="box">
            <form method="POST">
            <div class="icons">
                <a href="../Products/product.php?pid=<?= $fetch_product['id']; ?>" class="fas fa-eye"></a>
                <button class="fas fa-shopping-cart" type="submit" name="add_to_cart"></button>
            </div>
                <input type="hidden" name="pid" value="<?= $fetch_product['id']; ?>">
                <input type="hidden" name="p_name" value="<?= $fetch_product['name']; ?>">
                <input type="hidden" name="price" value="<?= $fetch_product['price1']; ?>">
                <input type="hidden" name="p_image" value="<?= $fetch_product['image']; ?>">
            </form>

            <img src="../uploaded_img/<?= $fetch_product['image']; ?>" alt="">

            <div class="content">
                <h3><?= $fetch_product['name']; ?></h3>
                <div class="price">&#8377 <?= $fetch_product['price1']; ?></div>
            </div>
        </div>
    
    <?php
        }
    }
    else{
        echo '<p class="empty">no products found!</p>';
    }}
    ?>
    </section>



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
    <script>
        window.addEventListener('load',function(){
            swal({
                title: "<?php foreach($error_message as $em){ echo $em; } ?>",
                icon: "warning",
                buttons: true,
                })
                .then((willDelete) => {
                if (willDelete) {
                    window.location.href = "../Sign In & Sign Up/signin_signup.php";
                } 
                else{
                    
                }
            });
        });
    </script>

    <!-- custom js file link -->
    <script src="../Home/main.js"></script>
</body>
</html>