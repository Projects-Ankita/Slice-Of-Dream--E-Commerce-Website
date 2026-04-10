<?php

include '../components/connect.php';


session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}
else{
   $user_id = '';
};

if (isset($_POST['remove'])){
    $delete_id = $_GET['id'];
    $delete_cart_item = $conn->prepare("DELETE FROM `cart` WHERE id = ?");
    $delete_cart_item->execute([$delete_id]);
    $message[] = "Product has been removed!";     
}

?>

<!doctype html>
<html lang="en">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cart</title>

    <link href="https://fonts.googleapis.com/css2?family=Niconne&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Courgette&display=swap" rel="stylesheet">

    <!-- font awesome cdn link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <link rel="stylesheet" href="style.css"> 
    <link rel="stylesheet" href="../Home/home_style.css">

    <!-- Bootstrap CDN -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

</head>
<body class="body-bg">
    <?php include '../components/header.php'; ?>


    <div class="container-fluid">
        <h1 class="title1">My Cart</h1>
        <hr>
        
        <div class="row px-5">
            <div class="col-md-7">
                <div class="shopping-cart">
                    <?php

                        $total = 0;
                        $count = 0;
                        $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
                        $select_cart->execute([$user_id]);
                        if($select_cart->rowCount() > 0){
                            while($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)){ 
                            ?>
                                <form action="cart.php?action=remove&id= <?= $fetch_cart['id'] ?>&pid= <?= $fetch_cart['pid'] ?>" method="post" class="cart-items">
                                    <div class="border rounded">
                                        <div class="row bg-white">
                                            <div class="col-md-4 pl-0">
                                                <img src="../uploaded_img/<?= $fetch_cart['image'] ?>" class="img-fluid">
                                            </div>
                                            <div class="col-md-8">
                                                <h2 class="pt-2"><?= $fetch_cart['name'] ?> (<?= $fetch_cart['weight'] ?>)</h2>
                                                
                                                <h3 class="pt-2">&#8377 <?= $fetch_cart['price'] ?></h3>
                                                
                                                <a href="../Products/product.php?pid=<?= $fetch_cart['pid']; ?>" class="view-btn">
                                                    View Product
                                                </a>
                                                
                                                <button type="submit" class="remove-btn" name="remove">Remove</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            <?php
                                $total = $total + $fetch_cart['price'];
                                $count = $count + 1;
                            }
                        }
                        else{
                            echo '<p class="empty">your cart is empty!</p>';
                        }
                    ?>

                </div>
            </div>
            <div class="col-md-4 offset-md-1 border rounded mt-4 bg-white h-25">
                        
                <div class="pt-4">
                    <h6 class="title1" style="margin-left:0rem; padding-left:0rem; font-size:2rem;">PRICE DETAILS</h6>
                    <hr>
                    <div class="row price-details">
                        <div class="col-md-6">
                            <?php
                                echo "<h6>Price ($count items)</h6>";
                            ?>
                            <h6>Delivery Charges</h6>
                            <hr>
                            <h6>Amount Payable</h6>
                        </div>
                        <div class="col-md-6">
                            <h6>&#8377 <?php echo $total; ?></h6>
                            <h6 class="text-success">FREE</h6>
                            <hr>
                            <h6>&#8377 <?php
                                echo $total;
                                ?></h6>
                        </div>
                    </div>
                    <div class="checkout-container">
                        <a href="checkout.php" class="btn <?= ($total > 1)?'':'disabled'; ?>">
                            <button class="checkout-btn" name="checkout">Proceed to Checkout</button>
                        </a>
                    </div>
                    <br>
                </div>
            </div>   
        </div>
    </div>



    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

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

    <script src="../Home/main.js"></script>

    <?php include '../components/footer.php'; ?>

</body>
</html>
