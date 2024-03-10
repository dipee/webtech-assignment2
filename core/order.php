<?php
class Order{
    // recording of sale

    private $conn;
    private $table_name = "user_order";

    public $id;
    public $user_id;
    public $created_at;

    public $total_price;
    public $shipping_address;
    public $status;


    public function __construct($db) {
        $this->conn = $db;
    }

    public function read() {
        // read order of a user by user_id
        $query = "SELECT * FROM " . $this->table_name . " WHERE user_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->user_id);
        $stmt->execute();
        return $stmt;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " SET user_id=:user_id, total_price=:total_price, shipping_address=:shipping_address, status=:status, created_at=:created_at";
        $stmt = $this->conn->prepare($query);

        // set properties
        $this->user_id = htmlspecialchars(strip_tags($this->user_id));
        $this->total_price = htmlspecialchars(strip_tags($this->total_price));
        $this->shipping_address = htmlspecialchars(strip_tags($this->shipping_address));
        $this->status = "created";
        $created_at = date('Y-m-d H:i:s');
        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->bindParam(":total_price", $this->total_price);
        $stmt->bindParam(":shipping_address", $this->shipping_address);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":created_at", $created_at);
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}