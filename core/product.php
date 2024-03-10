<?php

class Product {
    // database connection and table name
    private $conn;
    private $table_name = "product";

    // object properties
    public $id;
    public $name;
    public $image;
    public $price;
    public $description;
    public $shipping_cost;

    // constructor with $db as database connection
    public function __construct($db) {
        $this->conn = $db;
    }

    // read products
    public function read() {
        // select all query
        $query = "SELECT * FROM " . $this->table_name;

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();

        return $stmt;
    }

    // create product
    public function create() {
        // query to insert record
        $query = "INSERT INTO " . $this->table_name . " SET name=:name, image=:image, price=:price, description=:description, shipping_cost=:shipping_cost";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->image = htmlspecialchars(strip_tags($this->image));
        $this->price = htmlspecialchars(strip_tags($this->price));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->shipping_cost = htmlspecialchars(strip_tags($this->shipping_cost));

        // bind values
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":image", $this->image);
        $stmt->bindParam(":price", $this->price);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":shipping_cost", $this->shipping_cost);

        // execute query
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // update the product
    public function update() {
        // update query
        $query = "UPDATE " . $this->table_name . " SET name=:name, image=:image, price=:price, description=:description, shipping_cost=:shipping_cost WHERE id=:id";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->image = htmlspecialchars(strip_tags($this->image));
        $this->price = htmlspecialchars(strip_tags($this->price));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->shipping_cost = htmlspecialchars(strip_tags($this->shipping_cost));
        $this->id = htmlspecialchars(strip_tags($this->id));

        // bind new values
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":image", $this->image);
        $stmt->bindParam(":price", $this->price);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":shipping_cost", $this->shipping_cost);
        $stmt->bindParam(":id", $this->id);

        // execute the query
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // delete the product
    public function delete() {
        // delete query
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->id = htmlspecialchars(strip_tags($this->id));

        // bind id of record to delete
        $stmt->bindParam(":id", $this->id);

        // execute query
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // search and setproduct by id
    public function setProductById($id) {
        // query to read single record
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ?";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // bind id of product to be updated
        $stmt->bindParam(1, $id);

        // execute query
        $stmt->execute();

        $num = $stmt->rowCount();

        

        if($num > 0) {
        
        // get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->id = $row['id'];
        $this->name = $row['name'];
        $this->image = $row['image'];
        $this->price = $row['price'];
        $this->description = $row['description'];
        $this->shipping_cost = $row['shipping_cost'];
        }
      
    }

}
