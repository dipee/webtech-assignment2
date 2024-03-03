<?php 
class User {
    // databse connection and table name
    private $conn;
    private $table_name = "user";

    // user properties
    public $id;
    public $username;
    public $password;
    public $email;
    public $shipping_address;

    // constructor with $db as database connection
    public function __construct($db) {
        $this->conn = $db;
    }
}

?>