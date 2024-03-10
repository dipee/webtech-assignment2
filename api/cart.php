<?php
// headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
// allow GET, POST, PUT, DELETE methods
header("Access-Control-Allow-Methods: GET, POST, PATCH, DELETE");

// include database files
include_once '../includes/db_connection.php';

include_once '../core/cart.php';
include_once '../core/cart_item.php';


if($_SERVER['REQUEST_METHOD'] == 'GET') {
    $cart = new Cart($db);
    $cart->user_id = $_GET['user_id'];
    
    $stmt = $cart->read();
    $num = $stmt->rowCount();

    if ($num > 0) {
        $cart_arr = array();
        $cart_arr["records"] = array();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $cart_item_record = array(
                "id" => $id,
                "user_id" => $user_id,
                
            );
            // get all cart_items
            $cart_item = new CartItem($db);   
            $cart_item->cart_id = $id;
            $cart_item_stmt = $cart_item->read();
            $cart_item_num = $cart_item_stmt->rowCount();
            $cart_item_arr = array();
           
            while ($cart_item_row = $cart_item_stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($cart_item_row);
                $cart_item_item = array(
                    "id" => $id,
                    "product_id" => $product_id,
                    "quantity" => $quantity,
                    "cart_id" => $cart_id
                );
                array_push($cart_item_arr, $cart_item_item);
            }

            $cart_item_record["cart_items"] = $cart_item_arr;
            

            array_push($cart_arr["records"], $cart_item_record);
        }

        http_response_code(200);
        echo json_encode($cart_arr);
    } else {
        http_response_code(404);
        echo json_encode(
            array("message" => "No products found.")
        );
    }
    
}