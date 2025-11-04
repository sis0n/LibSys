<?php

namespace App\Repositories;

use App\Core\Database;
use PDO;

class PasswordResetRepository
{

  private $db;

  public function __construct()
  {
    $this->db = Database::getInstance()->getConnection();
  }

  public function createToken(string $email, string $token, int $expiryTimestamp): bool
  {
    try {
      $this->deleteToken($email);

      $sql = "INSERT INTO password_reset_tokens (email, token, expires_at) 
                    VALUES (:email, :token, :expires_at)";

      $stmt = $this->db->prepare($sql);

      return $stmt->execute([
        'email' => $email,
        'token' => $token,
        'expires_at' => date('Y-m-d H:i:s', $expiryTimestamp)
      ]);
    } catch (\PDOException $e) {
      error_log("[PasswordResetRepository::createToken] " . $e->getMessage());
      return false;
    }
  }

  public function deleteToken(string $email): bool
  {
    try {
      $sql = "DELETE FROM password_reset_tokens WHERE email = :email";
      $stmt = $this->db->prepare($sql);
      return $stmt->execute(['email' => $email]);
    } catch (\PDOException $e) {
      error_log("[PasswordResetRepository::deleteToken] " . $e->getMessage());
      return false;
    }
  }

  public function findToken(string $token)
  {
    try {
      $sql = "SELECT * FROM password_reset_tokens 
                    WHERE token = :token 
                    LIMIT 1";

      $stmt = $this->db->prepare($sql);
      $stmt->execute(['token' => $token]);
      $result = $stmt->fetch(PDO::FETCH_ASSOC);

      return $result ?: null;
    } catch (\PDOException $e) {
      error_log("[PasswordResetRepository::findToken] " . $e->getMessage());
      return null;
    }
  }
}
