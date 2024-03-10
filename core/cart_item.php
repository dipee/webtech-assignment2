<?php
class CartItem {
    private $conn;
    private $table_name = "cart_item";

    public $id;
    public $cart_id;
    public $product_id;
    public $quantity;

    public function __construct($db) {
        $this->conn = $db;
    }

//    read cart items of a cart 
    public function read() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE cart_id=:cart_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":cart_id", $this->cart_id);
        $stmt->execute();
        return $stmt;
    }
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " SET cart_id=:cart_id, product_id=:product_id, quantity=:quantity";
        $stmt = $this->conn->prepare($query);

        $this->cart_id = htmlspecialchars(strip_tags($this->cart_id));
        $this->product_id = htmlspecialchars(strip_tags($this->product_id));
        $this->quantity = htmlspecialchars(strip_tags($this->quantity));
        $stmt->bindParam(":cart_id", $this->cart_id);
        $stmt->bindParam(":product_id", $this->product_id);
        $stmt->bindParam(":quantity", $this->quantity);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . " SET cart_id=:cart_id, product_id=:product_id, quantity=:quantity WHERE id=:id";
        $stmt = $this->conn->prepare($query);
        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->cart_id = htmlspecialchars(strip_tags($this->cart_id));
        $this->product_id = htmlspecialchars(strip_tags($this->product_id));
        $this->quantity = htmlspecialchars(strip_tags($this->quantity));
        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":cart_id", $this->cart_id);
        $stmt->bindParam(":product_id", $this->product_id);
        $stmt->bindParam(":quantity", $this->quantity);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id=:id";
        $stmt = $this->conn->prepare($query);
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(":id", $this->id);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}