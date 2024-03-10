<?php
// headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
// allow GET, POST, PUT, DELETE methods
header("Access-Control-Allow-Methods: GET, POST, PATCH, DELETE");

// include database files
include_once '../includes/db_connection.php';

include_once '../core/cart.php';


if($_SERVER['REQUEST_METHOD'] == 'GET') {
    $cart = new Cart($db);
    $cart->user_id = $_GET['user_id'];
    
    $stmt = $cart->read();
    $num = $stmt->rowCount();

    if($num > 0) {
        $cart_arr = array();
        $cart_arr["records"] = array();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $cart_item = array(
                "id" => $id,
                "user_id" => $user_id,
                "product_id" => $product_id,
                "quantity" => $quantity
            );
            array_push($cart_arr["records"], $cart_item);
        }
        http_response_code(200);
        echo json_encode($cart_arr);
    } else {
        http_response_code(404);
        echo json_encode(
            array("message" => "No cart found.")
        );
    }
    
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cart = new Cart($db);
    // get posted data 
    $data = json_decode(file_get_contents("php://input"));

    // check mandatory fields 
    if(empty($data->user_id) || empty($data->product_id) || empty($data->quantity)) {
        http_response_code(400);
        echo json_encode(array("message" => "Unable to create cart. Data is incomplete."));
       return;
    }

    // set cart property values
    $cart->user_id = $data->user_id;
    $cart->product_id = $data->product_id;
    $cart->quantity = $data->quantity;

    

    if($cart->createOrUpdate()) {
        http_response_code(201);
        echo json_encode(array("message" => "Cart was created."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Unable to create cart."));
    }
}

if($_SERVER['REQUEST_METHOD'] == 'PATCH') {
    $cart = new Cart($db);
    // get posted data 
    $data = json_decode(file_get_contents("php://input"));

    $cart->user_id = $data->user_id;
    $cart->product_id = $data->product_id;

    // ceck mandatory fields
    if(empty($data->user_id) || empty($data->product_id)) {
        http_response_code(400);
        echo json_encode(array("message" => "Unable to update cart. Data is incomplete."));
        return;
    }

    // set cart property values if exists
    if(isset($data->user_id)) {
        $cart->user_id = $data->user_id;
    }
    if(isset($data->product_id)) {
        $cart->product_id = $data->product_id;
    }
    if(isset($data->quantity)) {
        $cart->quantity = $data->quantity;
    }
     if($cart->update()) {
        http_response_code(200);
        echo json_encode(array("message" => "Cart was updated."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Unable to update cart."));
    }
}


if($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    $cart = new Cart($db);
    // get posted data 
    $data = json_decode(file_get_contents("php://input"));

    $cart->user_id = $_GET['user_id'];
    $cart->product_id = $_GET['product_id'];

    if($cart->delete()) {
        http_response_code(200);
        echo json_encode(array("message" => "Cart was deleted."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Unable to delete cart."));
    }
}