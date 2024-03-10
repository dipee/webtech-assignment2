<?php
class Comment{
    private $conn;
    private $table_name = "comment";

    public $id;
    public $product_id;
    public $user_id;
    public $rating;
    public $image;
    public $text;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function read() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " SET product_id=:product_id, user_id=:user_id, rating=:rating, image=:image, text=:text";
        $stmt = $this->conn->prepare($query);

        // set properties
        $this->product_id = htmlspecialchars(strip_tags($this->product_id));
        $this->user_id = htmlspecialchars(strip_tags($this->user_id));
        $this->rating = htmlspecialchars(strip_tags($this->rating));
        $this->image = htmlspecialchars(strip_tags($this->image));
        $this->text = htmlspecialchars(strip_tags($this->text));
        $stmt->bindParam(":product_id", $this->product_id);
        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->bindParam(":rating", $this->rating);
        $stmt->bindParam(":image", $this->image);
        $stmt->bindParam(":text", $this->text);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . " SET product_id=:product_id, user_id=:user_id, rating=:rating, image=:image, text=:text WHERE id=:id";
        $stmt = $this->conn->prepare($query);
        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->product_id = htmlspecialchars(strip_tags($this->product_id));
        $this->user_id = htmlspecialchars(strip_tags($this->user_id));
        $this->rating = htmlspecialchars(strip_tags($this->rating));
        $this->image = htmlspecialchars(strip_tags($this->image));
        $this->text = htmlspecialchars(strip_tags($this->text));
        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":product_id", $this->product_id);
        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->bindParam(":rating", $this->rating);
        $stmt->bindParam(":image", $this->image);
        $stmt->bindParam(":text", $this->text);
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

    public function setCommentById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id=:id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->id = $row['id'];
        $this->product_id = $row['product_id'];
        $this->user_id = $row['user_id'];
        $this->rating = $row['rating'];
        $this->image = $row['image'];
        $this->text = $row['text'];
    }

}