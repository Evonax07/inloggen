<?php
class User {
    private $conn;
    private $table_name = "users";
    public $id;
    public $username;
    public $password;
    public function __construct($db) {
        $this->conn = $db;
    }

    function login($identifier) {
        $query = "SELECT id, username, password FROM " . $this->table_name . " WHERE username = :identifier OR email = :identifier";
        $stmt = $this->conn->prepare($query);

        $identifier = htmlspecialchars(strip_tags($identifier));
        $stmt->bindParam(":identifier", $identifier);

        $stmt->execute();

        return $stmt;
    }
    function register($username, $email, $password) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $query = "INSERT INTO " . $this->table_name . " (username, email, password) VALUES (:username, :email, :password)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":username", $username);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":password", $hashed_password);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
}
?>
