<?php
require('../common/library.php');
require('../common/constant.php');

require('../common/api.php');

if (!isset($_COOKIE['user_id'])) {
    header("Location: login.php");
    exit;
}

// Retrieve the cart from the cookie
$cart = isset($_COOKIE['cart']) ? unserialize($_COOKIE['cart']) : array();

// Remove the product from the cart based on the product ID
if (isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];
    if (array_key_exists($product_id, $cart)) {
        unset($cart[$product_id]);
    }
}

// Store the updated cart in a cookie
setcookie('cart', serialize($cart), time() + 3600, '/');

// Redirect back to the cart page
header("Location: ../cart.php");
exit;
?>