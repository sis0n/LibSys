<?php
namespace App\Repositories;

use App\Core\Database;

class UserRepository{
  private $db;

  public function __construct(){
    $this->db = Database::getInstance()->getConnection();
  }

  public function findByIdentifier(string $identifier){
    $stmt = $this->db->prepare("
        SELECT u.*, s.student_number 
        FROM users u
        LEFT JOIN students s ON u.user_id = s.user_id
        WHERE u.username = :identifier OR s.student_number = :identifier
        LIMIT 1
    ");
    $stmt->execute(['identifier' => $identifier]);
    return $stmt->fetch();
  }

  public function createUser(array $data){
    $stmt = $this->db->prepare("
        INSERT INTO users (username, password, full_name, role) 
        VALUES (:username, :password, :full_name, :role)
    ");
    return $stmt->execute([
        'username' => $data['username'],
        'password' => $data['password'],
        'full_name' => $data['full_name'],
        'role' => $data['role']
    ]);
  }

  public function getAllUsers(){
    $stmt = $this->db->query("SELECT * FROM users");
    return $stmt->fetchAll();
  }
}
