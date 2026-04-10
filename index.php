<?php

include '../components/connect.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Piece of Cake</title>

    <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Niconne&display=swap" rel="stylesheet">
    
    <!-- font awesome cdn link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- custom css file link -->
    <link rel="stylesheet" href="home_style.css">
</head>
<body>

    <?php include '../components/header.php'; ?>

    <!-- home slider start -->
    <section class="home">
        <div class="swiper home-slider">
            <div class="swiper-wrapper">
                <div class="swiper-slide slide">
                    <div class="image">
                        <img src="homepage_pic.png" alt="">
                    </div> 
                </div>
                <div class="swiper-slide slide">
                    <div class="image">
                        <img src="assortment-pieces-cake1.jpg" alt="">
                    </div> 
                </div>
                <div class="swiper-slide slide">
                    <div class="image">
                        <img src="Cake-Microsite-Banner.png" alt="">
                    </div> 
                </div>
                <div class="swiper-slide slide">
                    <div class="image">
                        <img src="delicious-cake-with-strawberry.jpg" alt="">
                    </div> 
                </div>
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </section>


    <section class="banner">
        <div class="container">
            <img id="img1" src="eggless.jpg" alt="">
            <img id="img2" src="home delivery.jpg" alt="">
            <img id="img3" src="5-star-google-rated.png" alt=""> 
        </div>
    </section>


    <!-- category start  -->

    <section class="category" id="cate">    
        <h1 class="title"> categories</h1>
        <?php
            $select_types = $conn->prepare("SELECT DISTINCT type FROM categories");
            $select_types->execute();
            while($fetch_types = $select_types->fetch(PDO::FETCH_ASSOC))
            {
        ?>
                <div class="sub-category">
                    <?php
                        $select_categories = $conn->prepare("SELECT * FROM categories WHERE type = ?");
                        $select_categories->execute([$fetch_types['type']]);
                    ?>
                    <h2 class="sub-title">cakes by <?= $fetch_types['type']; ?></h2>
                    <div class="box-container">
                        <?php
                            while($fetch_categories = $select_categories->fetch(PDO::FETCH_ASSOC))
                            {
                        ?>
                                <a href="../Categories/categories.php?cid=<?= $fetch_categories['id'] ?>" class="box">
                                    <img src="../uploaded_img/<?= $fetch_categories['image']; ?>" alt="">
                                    <h3><?= $fetch_categories['name']; ?></h3>
                                </a>
                        <?php    
                            }
                        ?>
                    </div> <br>
                </div> <br>
        <?php
            }
        ?>
    </section>

    <br><br>
    <div class="review-slider">
        <h1 class="title">Customer Reviews</h1>
        <script defer async src='https://cdn.trustindex.io/loader.js?8f26163814a86606c55443e58c'></script>
    </div>

    <br><br>

    <section class="contact" id="con">
        <h1 class="title">Contact Us</h1>
        <div class="column">
            <div class="icons-container">
                <div class="row1">
                    <div class="icons">
                        <i class="fas fa-phone"></i>
                        <h3>our number</h3>
                        <p>+91 9861055254</p>
                        <p>+91 8984841058</p>
                    </div>
                    <div class="icons">
                        <i class="fas fa-envelope"></i>
                        <h3>our email</h3>
                        <p>pieceofcakectc@gmail.com</p>
                        <p>trdhanya12@gmail.com</p>
                    </div>
                </div> 
                <div class="row2">
                    <div class="icons">
                        <i class="fas fa-map-marker-alt"></i>
                        <h3>our address</h3>
                        <p>Professor Para, Cuttack - 753003</p>
                        <p>Near Bishakha Hospital</p>
                    </div>
                    <div class="icons">
                        <img src="piece_of_cake_cuttack_qr.png" alt="">
                    </div>
                </div>
            </div>
            <iframe class="map" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3738.074565207415!2d85.88858521403316!3d20.46213038630802!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3a190df8322e3d49%3A0xdde5f62101d45cac!2sPiece%20of%20Cake!5e0!3m2!1sen!2sin!4v1654683953207!5m2!1sen!2sin" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
        </div>
    </section>
    <br><br><br>







    <!-- custom js file link -->
    <script src="main.js"></script>

    <script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>

    <script>
        var swiper = new Swiper(".home-slider", {
        loop:true,
        speed: 600,
        spaceBetween: 20,
        autoplay: {
            delay: 3000,
        },
        disableOnInteraction: true,
        pagination: {
            el: ".swiper-pagination",
            clickable:true,
            },
        });
    </script>

    <?php include '../components/footer.php'; ?>

</body>
</html>