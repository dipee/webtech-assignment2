<?php
class Cart {
    private $conn;
    private $table_name = "cart";

    public $id;
    public $user_id;

    public $quantity;

    public $product_id;



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

    public function createOrUpdate() {
        // check if product_id for user_id exists
        $query = "SELECT * FROM " . $this->table_name . " WHERE user_id=:user_id AND product_id=:product_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->bindParam(":product_id", $this->product_id);
        $stmt->execute();


        // if product_id for user_id exists, update quantity
        $num = $stmt->rowCount();
        $new_quantity = 0;
        if ($num > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $new_quantity = $row['quantity'];
            $new_quantity += $this->quantity;
            $this->quantity = $new_quantity;
            return $this->update();
          
        }
        else {
            return $this->create();
        }



    }

    public function create(){
        $query = "INSERT INTO " . $this->table_name . " SET user_id=:user_id, product_id=:product_id, quantity=:quantity";
        $stmt = $this->conn->prepare($query);
        $this->user_id = htmlspecialchars(strip_tags($this->user_id));
        $this->product_id = htmlspecialchars(strip_tags($this->product_id));
        $this->quantity = htmlspecialchars(strip_tags($this->quantity));
        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->bindParam(":product_id", $this->product_id);
        $stmt->bindParam(":quantity", $this->quantity);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . " SET quantity=:quantity WHERE user_id=:user_id AND product_id=:product_id";
        $stmt = $this->conn->prepare($query);
        $this->user_id = htmlspecialchars(strip_tags($this->user_id));
        $this->product_id = htmlspecialchars(strip_tags($this->product_id));
        $this->quantity = htmlspecialchars(strip_tags($this->quantity));
        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->bindParam(":product_id", $this->product_id);
        $stmt->bindParam(":quantity", $this->quantity);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE user_id=:user_id AND product_id=:product_id";
        $stmt = $this->conn->prepare($query);
        $this->user_id = htmlspecialchars(strip_tags($this->user_id));
        $this->product_id = htmlspecialchars(strip_tags($this->product_id));
        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->bindParam(":product_id", $this->product_id);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
