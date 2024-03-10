<?php 
// headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
// allow GET, POST, PUT, DELETE methods
header("Access-Control-Allow-Methods: GET, POST");

// include database files
include_once '../includes/db_connection.php';

include_once '../core/order.php';

if($_SERVER['REQUEST_METHOD'] == 'GET') {
    $order = new Order($db);
    $order->user_id = $_GET['user_id']; 
    $stmt = $order->read();
    $num = $stmt->rowCount();
    if($num > 0) {
        $orders_arr = array();
        $orders_arr["records"] = array();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $order_item = array(
                "id" => $id,
                "user_id" => $user_id,
                "total_price" => $total_price,
                "shipping_address" => $shipping_address
            );
            array_push($orders_arr["records"], $order_item);
        }
        http_response_code(200);
        echo json_encode($orders_arr);
    } else {
        http_response_code(404);
        echo json_encode(
            array("message" => "No orders found.")
        );
    }

}


if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $order = new Order($db);
    $data = json_decode(file_get_contents("php://input"));

    // check mandaroty values
    if(empty($data->user_id) || empty($data->total_price) || empty($data->shipping_address)) {
        http_response_code(400);
        echo json_encode(array("message" => "Unable to create order. Data is incomplete."));
        return;
    }

    $order->user_id = $data->user_id;
    $order->total_price = $data->total_price;
    $order->shipping_address = $data->shipping_address;
    if($order->create()) {
        http_response_code(201);
        echo json_encode(array("message" => "Order was created."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Unable to create order."));
    }
}