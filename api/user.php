<?php 
// headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
// allow GET, POST, PUT, DELETE methods
header("Access-Control-Allow-Methods: GET, POST, PATCH, DELETE");

// include database and object files
include_once '../includes/db_connection.php';
include_once '../core/user.php';


// get users
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $user = new User($db);
    $stmt = $user->read();
    $num = $stmt->rowCount();
  
    
    // check if more than 0 record found
    if ($num > 0) {
    $users_arr = array();
    $users_arr["records"] = array();

    // retrieve table contents
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);

        $user_item = array(
            "id" => $id,
            "username" => $username,
            "email" => $email,
            "shipping_address" => $shipping_address
        );

        array_push($users_arr["records"], $user_item);
    }
    http_response_code(200);

    echo json_encode($users_arr);
} else {
    http_response_code(404);
    echo json_encode(
        array("message" => "No users found.")
    );
}
}

// create new user
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user = new User($db);

    $data = json_decode(file_get_contents("php://input"));

    // set user property values
    $user->username = $data->username;
    $user->password = $data->password;
    $user->email = $data->email;
    $user->shipping_address = $data->shipping_address;

    // validate all fields are mandatory
    if (empty($user->username) || empty($user->password) || empty($user->email) || empty($user->shipping_address)) {
        http_response_code(400);


        echo json_encode(array("message" => "Unable to create user. Data is incomplete."));
        return;
      
    }

    if ($user->create()) {
        http_response_code(201);

       
        echo json_encode(array("message" => "User was created."));
    } else {
        http_response_code(500);
        echo json_encode(array("message" => "Unable to create user."));
    }

}


// update user
if ($_SERVER['REQUEST_METHOD'] == 'PATCH') {
    $user = new User($db);


    $data = json_decode(file_get_contents("php://input"));
    
    $user->setUserById($_GET['id']);
    

    if($user->id == null) {
        http_response_code(404);

        echo json_encode(array("message" => "User not found."));
        return;
    }

    if (!empty($data->username)) {
        $user->username = $data->username;
    }
    if (!empty($data->email)) {
        $user->email = $data->email;
    }
    if (!empty($data->shipping_address)) {
        $user->shipping_address = $data->shipping_address;
    }

    if ($user->update()) {
        http_response_code(200);

        echo json_encode(array("message" => "User was updated."));
    } else {
        http_response_code(503);

        echo json_encode(array("message" => "Unable to update user."));
    }
}


// Delete user
if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {

    $user = new User($db);
    $user->setUserById($_GET['id']);

    if($user->id == null) {
        http_response_code(404);
        echo json_encode(array("message" => "User not found."));
        return;
    }

    if ($user->delete()) {
        http_response_code(200);
        echo json_encode(array("message" => "User was deleted."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Unable to delete user."));
    }
}
