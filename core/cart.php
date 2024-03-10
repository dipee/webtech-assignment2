<?php
include_once "cart_item.php";

class Cart {
    private $conn;
    private $table_name = "cart";

    public $id;
    public $user_id;

    public $cart_items;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function read() {
        // select all query where user_id = ?   
        $query = "SELECT * FROM " . $this->table_name . " WHERE user_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->user_id);
        $stmt->execute();
        return $stmt;
    }
}
