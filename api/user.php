<?php 
// headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
// allow GET, POST, PUT, DELETE methods
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");

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

?>