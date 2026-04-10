<?php

include '../components/connect.php';
session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}
else{
   $user_id = '';
};


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Product View</title>

    <link href="https://fonts.googleapis.com/css2?family=Niconne&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Courgette&display=swap" rel="stylesheet">
    <!-- font awesome cdn link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <link rel="stylesheet" href="product.css">
    <link rel="stylesheet" href="../Home/home_style.css">
    
</head>
<body>
    <?php include '../components/header.php'; ?>

    
    <section class="product-details">
        <?php
            $pid = $_GET['pid'];
            $select_products = $conn->prepare("SELECT * FROM `products` WHERE id = ?"); 
            $select_products->execute([$pid]);
            $fetch_product = $select_products->fetch(PDO::FETCH_ASSOC);
        ?>
        
        <div class="image">
            <img src="../uploaded_img/<?= $fetch_product['image']; ?>" alt="">
        </div>
        <div class="details">
            <h2 class="product-name"><?= $fetch_product['name']; ?></h2>
            <div id="product-price">&#8377 <?= $fetch_product['price1']; ?></div>
            <p class="product-sub-heading">select cake weight</p>

            <form method="POST">

                <input type="radio" name="weight" value="500gm" onclick="fp(<?= $fetch_product['price1']; ?>)" checked hidden id="500grams">
                <label for="500grams" class="weight-radio-btn" >500gm</label>

                <input type="radio" name="weight" value="750gm" onclick="fp(<?= $fetch_product['price2']; ?>)" hidden id="750grams">
                <label for="750grams" class="weight-radio-btn">750gm</label>

                <input type="radio" name="weight" value="1kg" onclick="fp(<?= $fetch_product['price3']; ?>)" hidden id="1kg">
                <label for="1kg" class="weight-radio-btn">1 kg</label>
                <br>

                <input type="hidden" name="pid" value="<?= $fetch_product['id']; ?>">
                <input type="hidden" name="p_name" value="<?= $fetch_product['name']; ?>">
                <input type="hidden" name="p_image" value="<?= $fetch_product['image']; ?>">
                
                <input type="submit" value="add to cart" class="btn cart-btn" name="add_to_cart">
            </form>
        </div>
        <?php
        if(isset($_POST['add_to_cart']))
        {
            if(isset($_SESSION['user_id'])==false)
            {
                $error_message[] = "Please login first";
            }

            if(isset($_POST['weight']))
            {
                $weight = $_POST['weight'];
                
                if($weight == "500gm")
                    $price = $fetch_product['price1'];

                else if($weight == "750gm")
                    $price = $fetch_product['price2']; 

                else if($weight == "1kg")
                    $price = $fetch_product['price3'];
            }
            $pid = $_POST['pid'];
            $pid = filter_var($pid, FILTER_SANITIZE_STRING);
            $p_name = $_POST['p_name'];
            $p_name = filter_var($p_name, FILTER_SANITIZE_STRING);
            
            $p_image = $_POST['p_image'];
            $p_image = filter_var($p_image, FILTER_SANITIZE_STRING);
            
        
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

        
    </section>

    <section class="description">
        <h2 class="heading">description</h2>
        <p class="desc"><?= $fetch_product['details']; ?></p>

        <h2 class="heading">delivery details</h2>
        <ul class="desc">
            <li> The delicious cake is hand-delivered by our delivery boy in a good quality cardboard box.</li>
            <li>Candle and knife will be delivered as per the availability.</li>

        </ul>
        <h2 class="heading">care instructions</h2>   
        <ul class="desc">  
            <li>Store cake in a refrigerator.</li>
            <li>Consume cake within 24 hours.</li>
        </ul> 
    </section>
    

    <script>
        function fp(p)
        {
            document.getElementById("product-price").innerHTML = "<span>&#8377 </span>" + p;
            
        }
    </script>
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
    <script src="../Home/main.js"></script>

    <?php include '../components/footer.php'; ?>
</body>
</html>