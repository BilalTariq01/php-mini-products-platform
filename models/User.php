<?php
require_once __DIR__ . '/../config/database.php';

class User {
    private $conn;
    private $table_name = "users";
    
    public $id;
    public $email;
    public $password;
    
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }
    
    public function login($email, $password) {
        $query = "SELECT id, email, password FROM " . $this->table_name . " WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (password_verify($password, $row['password'])) {
                $this->id = $row['id'];
                $this->email = $row['email'];
                return true;
            }
        }
        return false;
    }
    
    public function create($email, $password) {
        $query = "INSERT INTO " . $this->table_name . " (email, password) VALUES (:email, :password)";
        $stmt = $this->conn->prepare($query);
        
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashed_password);
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    public function emailExists($email) {
        $query = "SELECT id FROM " . $this->table_name . " WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        return $stmt->rowCount() > 0;
    }
}
?>
