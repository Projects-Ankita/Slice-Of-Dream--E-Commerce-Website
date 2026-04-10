<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Header</title>

    <link href="https://fonts.googleapis.com/css2?family=Niconne&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Courgette&display=swap" rel="stylesheet">

    <!-- font awesome cdn link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../Home/home_style.css">
</head>
<body>
    <header class="header">
          <div class="logo"> <img src="../Home/cake_icon.png"> Piece of Cake </div>
          <nav class="navbar">
                <a href="../Home/index.php">Home</a>
                <a href="../Home/index.php#cate">Category</a>
                <a href="../About Us/about.php">About</a>
                <a href="../Home/index.php#con">Contact</a>
                <a href="../Enquiry/enquiry.php">Enquiry</a>
          </nav>

            <div class="icons">
                  <div id="menu-btn" class="fas fa-bars"></div>
                  <a href="../Search/search.php" title="Search">
                        <div id="search-btn" class="fas fa-search"></div>
                  </a>
                  <a href="../Cart & Checkout/cart.php" title="Cart">
                        <div id="cart-btn" class="fas fa-shopping-cart"></div>
                  </a>
                  <div class="dropdown">
                        <button id="login-btn" class="fas fa-user"></button>
                        <div class="dropdown-content">
                              <a href="../Sign In & Sign Up/signin_signup.php">Sign In/Sign Up</a>
                              <a href="../components/user_logout.php">Signout</a>
                        </div>
                  </div>
            </div>
    </header>
    <br><br>
</body>
</html>