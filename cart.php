<?php

require('common/library.php');
require('common/constant.php');

// $_POST['source'] = "web";
$_POST['API_KEY'] = "sXZ7tdYP7hy2qZKD9cL";
// $_POST['API_KEY'] = APP_API_KEY;
require('common/api.php');

if (!isset($_COOKIE['user_id'])) {
    header("Location: login.php");
    exit;
}

// Retrieve the cart from the cookie
$cart = isset($_COOKIE['cart']) ? unserialize($_COOKIE['cart']) : array();

// Retrieve the product details for each product in the cart using the product ID
$product_array = array();
foreach ($cart as $product_id => $quantity) {
    // Retrieve the product details using the product ID, e.g., from a database

    $products_fetching = $obj->select("*", "products", "product_id=?", [$product_id]);
    if (is_array($products_fetching)) {

        $product_id = $products_fetching[0]['product_id'];
        $product_name = $products_fetching[0]['product_name'];
        $product_price = $products_fetching[0]['product_price'];
        $product_img = $products_fetching[0]['product_img'];
        $product_qty = $products_fetching[0]['product_qty'];
        $product_desc = $products_fetching[0]['product_desc'];

        // Replace the code below with your actual logic to fetch the product details
        $product_details = array(
            'product_id' => $product_id,
            'product_name' => $product_name,
            'product_price' => $product_price,
            'product_img' => $product_img,
            'ordered_quantity' => $quantity,
            'product_desc' => $product_desc,
            // Add other relevant product details here
        );
    }


    $product_array[] = $product_details;
}

// Debug statement to check the product array
?>
<!DOCTYPE html>
<html lang="en">
<!-- Basic -->

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- Mobile Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Site Metas -->
    <title>ThewayShop - Ecommerce Bootstrap 4 HTML Template</title>
    <meta name="keywords" content="">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Site Icons -->
    <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
    <link rel="apple-touch-icon" href="images/apple-touch-icon.png">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <!-- Site CSS -->
    <link rel="stylesheet" href="css/style.css">
    <!-- Responsive CSS -->
    <link rel="stylesheet" href="css/responsive.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/custom.css">

    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>


    <?php include("header.php") ?>

    <!-- Start Top Search -->
    <div class="top-search">
        <div class="container">
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-search"></i></span>
                <input type="text" class="form-control" placeholder="Search">
                <span class="input-group-addon close-search"><i class="fa fa-times"></i></span>
            </div>
        </div>
    </div>
    <!-- End Top Search -->

    <!-- Start All Title Box -->
    <div class="all-title-box">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h2>Cart</h2>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Shop</a></li>
                        <li class="breadcrumb-item active">Cart</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- End All Title Box -->

    <!-- Start Cart  -->
    <div class="cart-box-main">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="table-main table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Images</th>
                                    <th>Product Name</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                    <th>Remove</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $totalPrice = 0;
                                foreach ($product_array as $product) {
                                    $subtotal = $product['product_price'] * $product['ordered_quantity'];
                                    $totalPrice += $subtotal;
                                    ?>
                                    <tr>
                                        <td class="thumbnail-img">
                                            <a href="#">
                                                <img class="img-fluid" src="<?= $product['product_img'] ?>" alt="" />
                                            </a>
                                        </td>
                                        <td class="name-pr">
                                            <a href="#">
                                                <input type="hidden" id="pro_id" value="<?= $product['product_id']; ?>">
                                                <input type="hidden" id="user_id" value="<?= $_COOKIE['user_id']; ?>">
                                                <?= $product['product_name'] ?>
                                            </a>
                                        </td>
                                        <td class="price-pr">
                                            <p>
                                                <?= $product['product_price'] ?>
                                            </p>
                                        </td>
                                        <td class="quantity-box">
                                            <form action="apis/update_quantity_cart.php" method="POST">
                                                <input type="hidden" name="API_KEY" value="sXZ7tdYP7hy2qZKD9cL" />
                                                <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>" />
                                                <input type="number" name="quantity" id="order_quantity"
                                                    value="<?= $product['ordered_quantity'] ?>" size="4" min="0" step="1"style="width:60%;">
                                                <button style="cursor:pointer;" type="submit">Update</button>
                                            </form>
                                        </td>
                                        <td class="total-pr">
                                            <p>
                                                <?= $subtotal ?>
                                            </p>
                                        </td>
                                        <td class="remove-pr">
                                            <form action="apis/remove_from_cart.php" method="POST">
                                                <input type="hidden" name="API_KEY" value="sXZ7tdYP7hy2qZKD9cL" />
                                                <input type="hidden" name="product_id"
                                                    value="<?= $product['product_id'] ?>" />
                                                <button type="submit"><i class="fas fa-times"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                                <tr>
                                    <td colspan="4"></td>
                                    <td class="total-price">
                                        <p>Total:
                                            <?= $totalPrice ?>
                                        </p>
                                        <input type="hidden" name="total_amount" id="total_amount"
                                            value="<?= $totalPrice ?>">
                                    </td>
                                    <td></td>
                                </tr>
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
            <div class="col-12 d-flex shopping-box"><a href="shop.php" class="ml-auto btn hvr-hover">Add More
                    Products</a></div> <br>
            <!-- Move the form inside the table -->
            <button id="place_order" class="ml-auto btn hvr-hover" onclick="placeOrder()">Place Order</button>

            <script>

                function placeOrder() {
                    // Get the necessary values from the page
                    var productID = $('#pro_id').val();
                    var orderedQuantity = $('#order_quantity').val();
                    var orderedAmount = $('#total_amount').val();
                    var userID = $('#user_id').val();

                    // Perform any necessary validation on the values

                    // Send the data to the server using AJAX or any other method
                    $.ajax({
                        url: 'apis/place_order.php',
                        method: 'POST',
                        data: {
                            product_id: productID,
                            ordered_qty: orderedQuantity,
                            ordered_amount: orderedAmount,
                            user_id: userID,
                            API_KEY: 'sXZ7tdYP7hy2qZKD9cL'
                        },
                        success: function (response) {
                            // Handle the success response from the server
                            console.log(response);
                            // window.location.href = "apis/place_order.php";
                        },
                        error: function (xhr, status, error) {
                            // Handle any error that occurs during the request
                            console.log(error);
                            // Display an error message to the user
                        }
                    });
                }
            </script>
        </div>

    </div>
    </div>
    <!-- End Cart -->


    <?php include("footer.php") ?>

    <!-- Start copyright  -->
    <div class="footer-copyright">
        <p class="footer-company">All Rights Reserved. &copy; 2018 <a href="#">ThewayShop</a> Design By :
            <a href="https://html.design/">html design</a>
        </p>
    </div>
    <!-- End copyright  -->

    <a href="#" id="back-to-top" title="Back to top" style="display: none;">&uarr;</a>

    <!-- ALL JS FILES -->
    <script src="js/jquery-3.2.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <!-- ALL PLUGINS -->
    <script src="js/jquery.superslides.min.js"></script>
    <script src="js/bootstrap-select.js"></script>
    <script src="js/inewsticker.js"></script>
    <script src="js/bootsnav.js."></script>
    <script src="js/images-loded.min.js"></script>
    <script src="js/isotope.min.js"></script>
    <script src="js/owl.carousel.min.js"></script>
    <script src="js/baguetteBox.min.js"></script>
    <script src="js/form-validator.min.js"></script>
    <script src="js/contact-form-script.js"></script>
    <script src="js/custom.js"></script>
</body>

</html>