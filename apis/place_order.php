<?php
// Include necessary files and initialize any required variables
require('../common/library.php');
require('../common/constant.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Retrieve the cart items array from the request
    $cartItems = $_POST['cart_items'];

    // User ID
    $userID = $_COOKIE['user_id'];

    // Generate a unique order ID
    do {
        $orderID = "ORD" . rand(1111, 9999);
        $orderIDExists = $obj->select("*", "orders", "orders_id=?", [$orderID]);
    } while (is_array($orderIDExists));

    // Fetching Products ID and Products Quantity Separately from CartItems
    $productIdArray = array();
    $quantityArray = array();

    foreach ($cartItems as $item) {
        $productIdArray[] = $item['product_id'];
        $quantityArray[] = $item['ordered_qty'];
    }

    foreach ($productIdArray as $key => $value) {
        // Perform operations using $productId
        $product_id = $productIdArray[$key];
        $quantity = $quantityArray[$key];

        // Insert the order details into the database
        $placeOrder = $obj->insert(
            "orders",
            "`orders_id`, `users_id`, `products_id`, `ordered_qty`",
            "'$orderID', '$userID', '$product_id', '$quantity'"
        );

        if (!$placeOrder) {
            // Handle the case if the order insertion fails
            $data['response'] = "n";
            $data['error'] = true;
            $data['message'] = "Failed to place the order for product with ID $product_id";
            echo json_encode($data);
            exit; // Stop processing further orders
        }
    }

    // Update the product quantity in the inventory
    foreach ($productIdArray as $key => $value) {

        $product_id = $productIdArray[$key];
        $quantity = $quantityArray[$key];

        $availableQty = $obj->select("product_qty", "products", "product_id=?", [$product_id]);
        $availableQty = $availableQty[0]['product_qty']; // Extract the quantity value from the array
        $updatedQty = $availableQty - $quantity;

        $obj->execute("UPDATE products SET `product_qty` = $updatedQty WHERE `product_id` = '$product_id'");
    }

    // Handle the case if all orders were successfully placed
    $data['response'] = "y";
    $data['error'] = false;
    $data['message'] = "Order Placed Successfully";
    echo json_encode($data);
    exit; // Stop processing further orders
}
?>
