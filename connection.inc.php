<?php
session_start();
$con=mysqli_connect("localhost","root","","cakeshop_db");
define('SERVER_PATH',$_SERVER['DOCUMENT_ROOT'].'php/cakeshop_db/');
define('SITE_PATH','http://localhost/phpmyadmin/index.php?route=/database/structure&server=1&db=cakeshop_db');

define('PRODUCT_IMAGE_SERVER_PATH',SERVER_PATH.'media/product/');
define('PRODUCT_IMAGE_SITE_PATH',SITE_PATH.'media/product/');
?>
