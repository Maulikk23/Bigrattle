<?php
// place_order.php

// Include necessary files and initialize any required variables
require('../common/library.php');
require('../common/constant.php');

// Validate the API_KEY or any other authentication mechanism

// Check if the required parameters are provided
if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
    // Retrieve the product ID and quantity from the form submission
    $product_id = $_POST['product_id'];

    echo "<br>";
    echo $quantity = $_POST['quantity'];

    // Retrieve the cart from the cookie
    $cart = isset($_COOKIE['cart']) ? unserialize($_COOKIE['cart']) : array();

    // Retrieve the product details from the database using the product ID, e.g., from a database
    $product_details = $obj->select("*", "products", "product_id=?", [$product_id]);

    if ($product_details) {

        $product_details[0]['product_qty'];

        // Check if the requested quantity is available in the product inventory
        if ($product_details[0]['product_qty'] >= $quantity) {

            // Decrement the product count in the inventory
            $updated_qty = $quantity;

            // Update the quantity in the cart cookie
            $cart[$product_id] = $updated_qty;
            setcookie('cart', serialize($cart), time() + (86400 * 30), '/');


            // Remove the ordered product from the cart
            // unset($cart[$product_id]);
            // setcookie('cart', serialize($cart), time() + (86400 * 30), '/'); // Modify the expiration time if needed

            // Redirect the user to a success page or display a success message
            header("Location: ../cart.php");
            exit;

        } else {
            // Handle the case where the requested quantity is not available in the inventory
            echo "The product is out of stock.";
            // You can display an error message to the user or redirect to an error page
        }

    } else {
        // Handle the case where the product details are not found
        echo "Product details not found.";
        // You can display an error message to the user or redirect to an error page
    }

} else {
    // Handle the case where the required parameters are not provided
    echo "Invalid request.";
    // You can display an error message to the user or redirect to an error page
}

?>