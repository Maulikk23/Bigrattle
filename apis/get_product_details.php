<?php 

require('../common/library.php');
require('../common/constant.php');

// $_POST['source'] = "web";
// $_POST['API_KEY'] = base64_encode(APP_API_KEY);
// $_POST['API_KEY'] = APP_API_KEY;
require('../common/api.php');

$_POST['source'] = "web";
require('../common/api.php');

$products = $obj->select("*","products","product_status=?", [1] );
$products_array = array();
if (is_array($products))
{
    foreach($products as $key => $value){

        $product_id = $value['product_id'];
        $product_name = $value['product_name'];
        $product_price = $value['product_price'];
        $product_img = $value['product_img'];
        $product_qty = $value['product_qty'];
        $product_desc = $value['product_desc'];
        
        array_push($products_array,array(
            'product_id' => $product_id ,
            'product_name' => $product_name,
            'product_price' => $product_price,
            'product_img' => $product_img,
            'product_qty' => $product_qty,
            'product_desc' => $product_desc,
        ));
        $key++;
    }
    $data['response'] = "y";
    $data['error'] = false;
    $data['message'] = "Success";
    $data['length'] = $key;
    $data['result_array'] = $products_array;
    echo json_encode($data);

}

?>