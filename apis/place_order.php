<?php
// Include necessary files and initialize any required variables
require('../common/library.php');
require('../common/constant.php');

// Retrieve the order details from the POST data
$user_id = $_POST['user_id'];

// Get the cart data from the cookie or session, assuming it is stored as an array of products
$cart = isset($_COOKIE['cart']) ? unserialize($_COOKIE['cart']) : [];

// Check if the cart is not empty
if (!empty($cart)) {
    // Create an empty array to store the order IDs
    $order_ids = [];

    // Loop through each product in the cart
    foreach ($cart as $product_id => $ordered_qty) {
        // Retrieve the product details from the database
        $product_details = $obj->select("*", "products", "product_id=?", [$product_id]);

        if ($product_details) {
            $available_qty = $product_details[0]['product_qty'];

            // Check if the requested quantity is available in the product inventory
            if ($available_qty >= $ordered_qty) {
                // Generate a unique order ID
                $order_id = "ORD" . rand(1111, 9999);

                $ordered_amount = $product_details[0]['product_price'] * $ordered_qty;

                $place_order = $obj->insert("orders",
                "`orders_id`, `users_id`, `products_id`, `ordered_qty`, `ordered_amount` ",
                "$order_id, $user_id, $product_id, $ordered_qty, $ordered_amount");

                if ($place_order) {
                    // Update the product quantity in the inventory
                    $updated_qty = $available_qty - $ordered_qty;
                    $obj->execute("UPDATE products SET `product_qty` = $updated_qty WHERE `product_id` = $product_id");

                    // Add the order ID to the array
                    $order_ids[] = $order_id;
                } else {
                    // Handle the case if the order insertion fails
                    $data['response'] = "n";
                    $data['error'] = true;
                    $data['message'] = "Failed to place the order for product with ID $product_id";
                    break; // Stop processing further orders
                }
            } else {
                // Handle the case if the requested quantity is not available in the inventory
                $data['response'] = "n";
                $data['error'] = true;
                $data['message'] = "Product with ID $product_id is out of stock";
                break; // Stop processing further orders
            }
        } else {
            // Handle the case if the product details are not found
            $data['response'] = "n";
            $data['error'] = true;
            $data['message'] = "Product with ID $product_id not found";
            break; // Stop processing further orders
        }
    }

    // Check if all the orders were placed successfully
    if (count($order_ids) == count($cart)) {
        // Remove the ordered products from the cart (assuming you have implemented this functionality)

        // Redirect the user to a success page or display a success message
        $data['response'] = "y";
        $data['error'] = false;
        $data['message'] = "Orders placed successfully";
    } else {
        // If there was an error during order placement, rollback the changes and display an error message
        foreach ($order_ids as $order_id) {
            $obj->delete("orders", "order_id=?", [$order_id]);
        }

        $data['response'] = "n";
        $data['error'] = true;
        $data['message'] = "Failed to place all the orders";
    }
} else {
    // Handle the case if the cart is empty
    $data['response'] = "n";
    $data['error'] = true;
    $data['message'] = "Cart is empty";
}

// Send the response back to the client
echo json_encode($data);
?>