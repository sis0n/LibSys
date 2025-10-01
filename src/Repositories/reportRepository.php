<?php

namespace App\Repositories;

class reportRepository{
  //change password
  public function updatePassword($userId, $hashedPassword){
    $sql = "UPDATE users SET password = :password WHERE id = :id";
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':password', $hashedPassword);
    $stmt->bindParam(':id', $userId, \PDO::PARAM_INT);
    return $stmt->execute();
}
}