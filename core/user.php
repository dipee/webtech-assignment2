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

    public function read() {
        // select all query
        $query = "SELECT * FROM " . $this->table_name;

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();

        return $stmt;
    }

    public function create() {
        // query to insert record
        $query = "INSERT INTO " . $this->table_name . " SET username=:username, password=:password, email=:email, shipping_address=:shipping_address";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->username = htmlspecialchars(strip_tags($this->username));
        $this->password = md5(htmlspecialchars(strip_tags($this->password)));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->shipping_address = htmlspecialchars(strip_tags($this->shipping_address));

        // bind values
        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":shipping_address", $this->shipping_address);

        // execute query
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
}

?>