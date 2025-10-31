<?php

namespace App\Repositories;

use App\Core\Database;
use PDO;
use PDOException;

class BackupRepository
{
  protected PDO $db;

  public function __construct()
  {
    $this->db = Database::getInstance()->getConnection();
  }

  public function getAllTableData(string $tableName): array
  {
    $safeTableName = $tableName;

    $this->db->beginTransaction();
    try {
      $stmtData = $this->db->prepare("SELECT * FROM $safeTableName");
      $stmtData->execute();
      $data = $stmtData->fetchAll(PDO::FETCH_ASSOC);

      $stmtColumns = $this->db->prepare("DESCRIBE $safeTableName");
      $stmtColumns->execute();
      $columns = $stmtColumns->fetchAll(PDO::FETCH_COLUMN, 0);

      $this->db->commit();

      return ['columns' => $columns, 'data' => $data];
    } catch (PDOException $e) {
      $this->db->rollBack();
      throw new \Exception("Database export failed for table '$tableName': " . $e->getMessage());
    }
  }

  public function getAllTableNames(): array
  {
    try {
      $stmt = $this->db->query("SHOW TABLES");
      return $stmt->fetchAll(PDO::FETCH_COLUMN);
    } catch (PDOException $e) {
      throw new \Exception("Failed to retrieve table names: " . $e->getMessage());
    }
  }

  public function exportTableSqlDump(string $tableName): string
  {
    $db = $this->db;
    $sql = "DROP TABLE IF EXISTS $tableName;\n";

    $createStmt = $db->query("SHOW CREATE TABLE $tableName");
    $row = $createStmt->fetch(PDO::FETCH_ASSOC);
    $sql .= $row['Create Table'] . ";\n\n";

    $dataStmt = $db->query("SELECT * FROM $tableName");
    while ($row = $dataStmt->fetch(PDO::FETCH_NUM)) {
      $row = array_map([$db, 'quote'], $row);
      $sql .= "INSERT INTO $tableName VALUES (" . implode(', ', $row) . ");\n";
    }
    $sql .= "\n";

    return $sql;
  }

  public function logBackup(string $fileName, string $fileType, string $createdBy, ?string $size = null)
  {
    $stmt = $this->db->prepare(
      "INSERT INTO backup_log (file_name, file_type, created_by, size, created_at) VALUES (:file_name, :file_type, :created_by, :size, NOW())"
    );
    $stmt->execute([
      ':file_name' => $fileName,
      ':file_type' => $fileType,
      ':created_by' => $createdBy,
      ':size' => $size
    ]);
  }

  public function getAllBackupLogs(): array
  {
    $stmt = $this->db->query("
            SELECT 
                bl.*, 
                IFNULL(CONCAT(u.first_name, ' ', u.last_name), 'Unknown User') AS created_by_name 
            FROM backup_log bl
            LEFT JOIN users u ON bl.created_by = u.user_id 
            ORDER BY bl.created_at DESC
        ");
    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
  }
}
