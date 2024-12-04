<?php

class Controller {
    private $conn;

    public function __construct($pdo) {
        $this->conn = $pdo;
    }

    // Register method
    public function register($name, $email, $password, $type = 'user') {
        if (empty($name) || empty($email) || empty($password)) {
            die("All fields are required");
        }

        $hash_password = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (name, email, password, type, approved) VALUES (:name, :email, :password, :type, 0)";
        $stmt = $this->conn->prepare($sql);

        if ($stmt === false) {
            die("Prepare failed: " . implode(", ", $this->conn->errorInfo()));
        }

        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hash_password);
        $stmt->bindParam(':type', $type);

        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return $this->conn->lastInsertId();
        } else {
            return null;
        }
    }

    // Validate login method
    public function validateLogin($email, $password) {
        $sql = "SELECT id, name, password, type, approved FROM users WHERE email = :email";
        $stmt = $this->conn->prepare($sql);

        if ($stmt === false) {
            die("Prepare failed: " . implode(", ", $this->conn->errorInfo()));
        }

        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->errorCode() != '00000') {
            die("Execute failed: " . implode(", ", $stmt->errorInfo()));
        }

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            if ($user['approved'] == 0) {
                // User is not approved
                return ['error' => 'Your account is not approved yet. Please wait for admin approval.'];
            } elseif (password_verify($password, $user['password'])) {
                return $user;
            }
        }

        return null;
    }

    // Method to get the user's approval status
    public function getUserStatus($userId) {
        $sql = "SELECT approved FROM users WHERE id = :id";
        $stmt = $this->conn->prepare($sql);

        if ($stmt === false) {
            die("Prepare failed: " . implode(", ", $this->conn->errorInfo()));
        }

        $stmt->bindParam(':id', $userId);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            return $user['approved']; 
        }

        return null; 
    }
}
?>