<?php

include '../components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us</title>

    <link href="https://fonts.googleapis.com/css2?family=Niconne&display=swap" rel="stylesheet">

    <!-- font awesome cdn link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- custom css file link -->
    <link rel="stylesheet" href="../Home/home_style.css">
    <link rel="stylesheet" href="style.css">

</head>
<body>
    <!-- about section start -->
    <div class="heading">
        <h1>About us</h1>
    </div>
    
    <section class="about">
        <div class="img">
            <img src="img/about-img.jpg" alt="">
        </div>
    
        <div class="content">
            <h3>Why choose us?</h3>
            <p>
                Piece of Cake offers a wide variety of scrumptious cakes in Cuttack. Whether it’s a birthday around the corner or the date of the anniversary is nearby, all that you need is a lip-smacking cake to make the day more delightful. Our flavorsome cakes convey the feelings, reach deepest in the hearts, and bring a smile on the face of your beloveds. We have eggless cakes and thus the strict vegetarians would also have a great time at your party. Keep looking for your favorite cakes and have a lovely celebration.
            </p>
        </div>
    </section>
    
    <div class="customer-review">
        <h1 class="review-heading">What customers say about us?</h1>
        <script defer async src='https://cdn.trustindex.io/loader.js?2d3817881ce7664bd658d4b1bb'></script>
    </div>

     <!-- custom js file link -->
     <script src="../Home/main.js"></script>

    <?php include '../components/footer.php'; ?>

    </body>
    </html>