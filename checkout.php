<?php

include '../components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

if(isset($_POST['order'])){

      $name = $_POST['name'];
      $name = filter_var($name, FILTER_SANITIZE_STRING);
      $snumber = $_POST['snumber'];
      $snumber = filter_var($snumber, FILTER_SANITIZE_STRING);
      $rnumber = $_POST['rnumber'];
      $rnumber = filter_var($rnumber, FILTER_SANITIZE_STRING);
      $method = $_POST['method'];
      $method = filter_var($method, FILTER_SANITIZE_STRING);
      $address = $_POST['address'];
      $address = filter_var($address, FILTER_SANITIZE_STRING);
      $deldt = $_POST['deldt'];
      $deldt = filter_var($deldt, FILTER_SANITIZE_STRING);
      $deltm = $_POST['deltm'];
      $deltm = filter_var($deltm, FILTER_SANITIZE_STRING);
      $msg = $_POST['msg'];
      $msg = filter_var($msg, FILTER_SANITIZE_STRING);

      $total_products = $_POST['total_products'];
      $total_price = $_POST['total_price'];

      $insert_order = $conn->prepare("INSERT INTO `orders`(user_id, name, sender_number, receiver_number, method, address, delivery_date, delivery_time, message, total_products, total_price) VALUES(?,?,?,?,?,?,?,?,?,?,?)");
      $insert_order->execute([$user_id, $name, $snumber, $rnumber, $method, $address, $deldt, $deltm, $msg, $total_products, $total_price]);

      $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
      $delete_cart->execute([$user_id]);

      $message[] = 'Order placed successfully!';

}

?>

<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Checkout</title>

    <link href="https://fonts.googleapis.com/css2?family=Niconne&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Courgette&display=swap" rel="stylesheet">

    <!-- font awesome cdn link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

      <!-- Bootstrap CDN -->
      <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="../Home/home_style.css">
    
</head>
<body class="checkout-body">
      <?php include '../components/header.php'; ?>


      <?php
         $total_price = 0;
         $cart_items[] = '';
         $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
         $select_cart->execute([$user_id]);
         if($select_cart->rowCount() > 0){
            while($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)){
               $cart_items[] = $fetch_cart['name'].'('.$fetch_cart['weight'].')'.' , ';
               $total_products = implode($cart_items);
               $total_price += $fetch_cart['price'];
            }
      ?>
      
            <section class="checkout-orders">    
                  <div class="container-fluid">
                  <h6 class="title1">Enter Checkout Details</h6>
                  <hr>
                  <div class="row px-5">
                        <div class="col-md-7">
                        <div class="checkout">
                        <div class="border rounded">
                        <div class="row bg-white">
                              <form action="" method="POST" class="col-md-12">
                                    <div class="inputBox">
                                          <label>name : </label>
                                          <input type="text" name="name" placeholder="Enter your name" class="box" maxlength="20" required>
                                    </div>
                                    <div class="inputBox">
                                          <label>contact number : </label>
                                          <input type="number" name="snumber" placeholder="Enter your number" class="box" required>
                                    </div>

                                    <div class="inputBox">
                                          <label>delivery contact number : </label>
                                          <input type="number" name="rnumber" placeholder="Enter the number" class="box" required>
                                    </div>
                                    <div class="inputBox">
                                          <label>delivery address : </label>
                                          <textarea name="address" placeholder="Enter the address" class="box" rows="4" cols="20" required></textarea>
                                    </div>

                                    <div class="inputBox">
                                          <label>Delivery Date : </label>
                                          <input type="date" name="deldt" id="dt" class="box" required>
                                    </div>

                                    <div class="inputBox">
                                          <label>Delivery Time Range : </label>
                                          <select name="deltm" class="box" required>
                                          <option class="optn" value="8am to 12pm">8am to 12pm</option>
                                          <option class="optn" value="12pm to 3pm">12pm to 3pm</option>
                                          <option class="optn" value="3pm to 6pm">3pm to 6pm</option>
                                          <option class="optn" value="6pm to 9pm">6pm to 9pm</option>
                                          </select>
                                    </div>

                                    <div class="inputBox">
                                          <label>message on the cake : </label>
                                          <input type="text" name="msg" placeholder="Enter the message" class="box" maxlength="50">
                                    </div>

                                    <div class="inputBox">
                                          <label>payment method : </label>
                                          <select name="method" class="box" required>
                                          <option class="optn" value="cash on delivery" selected>Cash on delivery</option>
                                          </select>
                                    </div>
                                    
                                    <input type="hidden" name="total_price" value="<?= $total_price ?>" >
                                    <input type="hidden" name="total_products" value="<?= $total_products ?>" >

                                    <div class="order-btn">
                                          <input type="submit" name="order" class="checkout-btn <?= ($total_price > 1)?'':'disabled'; ?>" value="place order">
                                    </div>
                              </form>
                        </div></div></div></div>

                        <div class="col-md-4 offset-md-1 border rounded mt-4 bg-white h-25">          
                        <div class="pt-4">
                              <h6 class="title1" style="margin-left:0rem; padding-left:0rem; font-size:2rem;">PRICE DETAILS</h6>
                              <hr>
                              <div class="row price-details">
                                    <div class="col-md-6">
                                          <?php
                                                echo "<h6>Price</h6>";
                                          ?>
                                          <h6>Delivery Charges</h6>
                                          <hr>
                                          <h6>Amount Payable</h6>
                                    </div>
                                    <div class="col-md-6">
                                          <h6>&#8377 <?= $total_price ?></h6>
                                          <h6 class="text-success">FREE</h6>
                                          <hr>
                                          <h6>&#8377 <?= $total_price ?></h6>
                                    </div>
                              </div>
                        
                        </div>
                        </div>   
                  </div>
                  </div>
            </section>
      <?php
         }
         else{
            echo '<p class="empty">your cart is empty!
                        <div class="contn-shop-container">
                        <a href="../Home/index.php" class="btn">
                            <button class="contn-shop-btn" name="contn-shop">Continue Shopping</button>
                        </a>
                        </div>
                  </p>';
         }
      ?>
      
      <script>
            var date = new Date();
            var d = date.getDate();
            var m = date.getMonth() + 1;
            var y = date.getUTCFullYear();
           
            if(d < 10)
            {
                  d = '0' + d;
            }
            if(m < 10)
            {
                  m = '0' + m;
            }

            var mindate = y + "-" + m + "-" + d;
            document.getElementById("dt").setAttribute('min', mindate);
            console.log(mindate);
      </script>

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