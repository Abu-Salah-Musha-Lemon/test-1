<?php
class User {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create($name, $email, $phone) {
        $query = "INSERT INTO users (name, email, phone) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("sss", $name, $email, $phone);
        return $stmt->execute();
    }

    public function read() {
        $query = "SELECT * FROM users";
        $result = $this->conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function update($id, $name, $email, $phone) {
        $query = "UPDATE users SET name=?, email=?, phone=? WHERE id=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("sssi", $name, $email, $phone, $id);
        return $stmt->execute();
    }

    public function delete($id) {
        $query = "DELETE FROM users WHERE id=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>
