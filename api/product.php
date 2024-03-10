<?php 
// headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
// allow GET, POST, PUT, DELETE methods
header("Access-Control-Allow-Methods: GET, POST, PATCH, DELETE");

// include database files
include_once '../includes/db_connection.php';

include_once '../core/product.php';


if($_SERVER['REQUEST_METHOD'] == 'GET') {
    $product = new Product($db);
    // query products
    $stmt = $product->read();
    $num = $stmt->rowCount();

    // check if more than 0 record found
    if ($num > 0) {
        $products_arr = array();
        $products_arr["records"] = array();

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

        http_response_code(200);
        echo json_encode($products_arr);
    } else {
        http_response_code(404);
        echo json_encode(
            array("message" => "No products found.")
        );
    }
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product = new Product($db);
    // get posted data 
    $data = json_decode(file_get_contents("php://input"));

    // set product property values
    $product->name = $data->name;
    $product->price = $data->price;
    $product->description = $data->description;
    $product->shipping_cost = $data->shipping_cost;
    $product->image = $data->image;

    // validate all fields are mandatory
    if (empty($product->name) || empty($product->price) || empty($product->description) || empty($product->shipping_cost) || empty($product->image)) {
        http_response_code(400);
        echo json_encode(array("message" => "All fields are mandatory."));
        return;
       
    }

    // create the product
    if($product->create()) {
        http_response_code(201);

        echo json_encode(array("message" => "Product was created."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Unable to create product."));
    }
}

if($_SERVER['REQUEST_METHOD'] == 'PATCH') {
    $product = new Product($db);
    $data = json_decode(file_get_contents("php://input"));
    
    $product->setProductById($_GET['id']);
    

    if($product->id == null) {

        http_response_code(404);

        echo json_encode(array("message" => "product not found."));
        return;
    }

    // set product property values if they are set
    if (!empty($data->name)) {
        $product->name = $data->name;
    }
    if (!empty($data->image)) {
        $product->image = $data->image;
    }
    if (!empty($data->price)) {
        $product->price = $data->price;
    }
    if (!empty($data->description)) {
        $product->description = $data->description;
    }
    if (!empty($data->shipping_cost)) {
        $product->shipping_cost = $data->shipping_cost;
    }

    // update the product
    if ($product->update()) {
        http_response_code(200);

        echo json_encode(array("message" => "Product was updated."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Unable to update Product."));
    }
}


