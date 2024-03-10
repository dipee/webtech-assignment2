<?php
// headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");

// include database files
include_once '../includes/db_connection.php';

include_once '../core/product.php';

if($_SERVER['REQUEST_METHOD'] == 'GET') {
    $product = new Product($db);
    $product->setProductById($_GET['product_id']);
    $stmt = $product->getComments();
    $num = $stmt->rowCount();
    if($num > 0) {
        $comments_arr = array();
        $comments_arr["records"] = array();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $comment_item = array(
                "id" => $id,
                "product_id" => $product_id,
                "user_id" => $user_id,
                "rating" => $rating,
                "image" => $image,
                "text" => $text
            );
            array_push($comments_arr["records"], $comment_item);
        }
        http_response_code(200);
        echo json_encode($comments_arr);
    } else {
        http_response_code(404);
        echo json_encode(
            array("message" => "No comments found.")
        );
    }

}
