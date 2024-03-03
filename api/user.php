<?php 
// headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
// allow GET, POST, PUT, DELETE methods
header("Access-Control-Allow-Methods: GET, POST, PATCH, DELETE");

// include database and object files
include_once '../includes/db_connection.php';

// instantiate user object
include_once '../core/user.php';
$user = new User($db);


// get request method
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
   
// query users
$stmt = $user->read();
$num = $stmt->rowCount();

// check if more than 0 record found
if ($num > 0) {
    // users array
    $users_arr = array();
    $users_arr["records"] = array();

    // retrieve table contents
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // extract row
        extract($row);

        $user_item = array(
            "id" => $id,
            "username" => $username,
            "email" => $email,
            "shipping_address" => $shipping_address
        );

        array_push($users_arr["records"], $user_item);
    }

    // set response code - 200 OK
    http_response_code(200);

    // show users data in json format
    echo json_encode($users_arr);
} else {
    // set response code - 404 Not found
    http_response_code(404);

    // tell the user no users found
    echo json_encode(
        array("message" => "No users found.")
    );
}
}
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    // get posted data from _POST
    $data = json_decode(file_get_contents("php://input"));

    // set user property values
    $user->username = $data->username;
    $user->password = $data->password;
    $user->email = $data->email;
    $user->shipping_address = $data->shipping_address;

    // validate all fields are mandatory
    if (empty($user->username) || empty($user->password) || empty($user->email) || empty($user->shipping_address)) {
        // set response code - 400 bad request
        http_response_code(400);

        // tell the user
        echo json_encode(array("message" => "Unable to create user. Data is incomplete."));
        return;
    }

    // create the user
    if ($user->create()) {
        // set response code - 201 created
        http_response_code(201);

        // tell the user
        echo json_encode(array("message" => "User was created."));
    } else {
        // set response code - 500 internal server error
        http_response_code(500);

        // tell the user
        echo json_encode(array("message" => "Unable to create user."));
    }
    
    
    
    // close the database connection
    $db = null;

}

if ($_SERVER['REQUEST_METHOD'] == 'PATCH') {
    // get user id
    parse_str(file_get_contents("php://input"), $_PATCH);

    $data = json_decode(file_get_contents("php://input"));
    
    $user->setUserById($_GET['id']);
    

    if($user->id == null) {
        // set response code - 404 Not found
        http_response_code(404);

        // tell the user
        echo json_encode(array("message" => "User not found."));
        return;
    }

    // set user property values if they are set
    if (!empty($data->username)) {
        $user->username = $data->username;
    }
    if (!empty($data->email)) {
        $user->email = $data->email;
    }
    if (!empty($data->shipping_address)) {
        $user->shipping_address = $data->shipping_address;
    }

    // update the user
    if ($user->update()) {
        // set response code - 200 OK
        http_response_code(200);

        // tell the user
        echo json_encode(array("message" => "User was updated."));
    } else {
        // set response code - 503 service unavailable
        http_response_code(503);

        // tell the user
        echo json_encode(array("message" => "Unable to update user."));
    }
}
?>