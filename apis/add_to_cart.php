<?php

require('../common/library.php');
require('../common/constant.php');

if (!isset($_COOKIE['user_id'])) {
    header("Location: login.php");
    exit;
}

// Retrieve the existing cart from the cookie, if it exists
$cart = isset($_COOKIE['cart']) ? unserialize($_COOKIE['cart']) : array();

// Loop through the product_id and quantity arrays to add each product to the cart
if (isset($_POST['product_id']) && isset($_POST['quantity'])) {

    $product_ids = $_POST['product_id'];
    $quantities = $_POST['quantity'];

    foreach ($product_ids as $index => $product_id) {
        $quantity = $quantities[$index];

        // Add the product and quantity to the cart
        $cart[$product_id] = $quantity;
    }
}

// Store the updated cart in a cookie
setcookie('cart', serialize($cart), time() + 3600, '/');

// Retrieve the product array from the form
$product_array = $_POST['product_array'];

// Debug statement to check the product array
echo "<pre>";
print_r($product_array);
echo "</pre>";

// Convert the product array to JSON and encode it for the URL
$product_array_json = urlencode(json_encode($product_array));

// Redirect to the cart page with the encoded product array as a URL parameter
header("Location: ../cart.php?products=$product_array_json");
exit;
?>