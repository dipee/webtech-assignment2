<?php 
// headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
// allow GET, POST, PUT, DELETE methods
header("Access-Control-Allow-Methods: GET, POST, PATCH, DELETE");

// include database and object files
include_once '../includes/db_connection.php';

// instantiate user object
include_once '../core/product.php';
$product = new Product($db);

if($_SERVER['REQUEST_METHOD'] == 'GET') {
    // query products
    $stmt = $product->read();
    $num = $stmt->rowCount();

    // check if more than 0 record found
    if ($num > 0) {
        // products array
        $products_arr = array();
        $products_arr["records"] = array();

        // retrieve table contents
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // extract row
            extract($row);

            $product_item = array(
                "id" => $id,
                "name" => $name,
                "image" => $image,
                "price" => $price,
                "description" => $description,
                "shipping_cost" => $shipping_cost
            );

            array_push($products_arr["records"], $product_item);
        }

        // set response code - 200 OK
        http_response_code(200);

        // show products data in json format
        echo json_encode($products_arr);
    } else {
        // set response code - 404 Not found
        http_response_code(404);

        // tell the user no products found
        echo json_encode(
            array("message" => "No products found.")
        );
    }
}

