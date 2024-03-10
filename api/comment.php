<?php 
// headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
// allow GET, POST, PUT, DELETE methods
header("Access-Control-Allow-Methods: GET, POST, PATCH, DELETE");

// include database files
include_once '../includes/db_connection.php';

include_once '../core/comment.php';


if($_SERVER['REQUEST_METHOD'] == 'GET') {
    $comment = new Comment($db);
    // query comments
    $stmt = $comment->read();
    $num = $stmt->rowCount();

    // check if more than 0 record found
    if ($num > 0) {
        $comments_arr = array();
        $comments_arr["records"] = array();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // extract row
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

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $comment = new Comment($db);
    // get posted data 
    $data = json_decode(file_get_contents("php://input"));

    // set comment property values
    $comment->product_id = $data->product_id;
    $comment->user_id = $data->user_id;
    $comment->rating = $data->rating;
    $comment->image = $data->image;
    $comment->text = $data->text;

    // check mandatory fields
    if (empty($comment->product_id) || empty($comment->user_id) || empty($comment->rating)|| empty($comment->text)) {
        http_response_code(400);
        echo json_encode(array("message" => "Unable to create comment. Data is incomplete."));
        return;
    }

    if($comment->create()) {
        http_response_code(201);
        echo json_encode(array("message" => "Comment was created."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Unable to create comment."));
    }
}