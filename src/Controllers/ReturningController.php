<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Repositories\ReturningRepository;

class ReturningController extends Controller
{
  private $returningRepo;

  public function __construct()
  {
    $this->returningRepo = new ReturningRepository();
  }

  private function sendJson(array $data, int $status = 200): void
  {
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data);
    exit; // optional: prevents further HTML output
  }

  public function getDueSoonAndOverdue()
  {
    $data = $this->returningRepo->getDueSoonAndOverdue();

    if ($data === null) {
      $this->sendJson(['success' => false, 'message' => 'Failed to retrieve data from server.'], 500);
      return;
    }

    $this->sendJson(['success' => true, 'data' => $data]);
  }

  public function checkBookStatus()
  {
    $accessionNumber = $_POST['accession_number'] ?? null;

    if (!$accessionNumber) {
      $this->sendJson(['success' => false, 'message' => 'Accession Number is required.'], 400);
      return;
    }

    $result = $this->returningRepo->findBookByAccession($accessionNumber);

    if ($result === null) {
      $this->sendJson(['success' => false, 'message' => 'An error occurred while searching for the book.'], 500);
      return;
    }

    $this->sendJson(['success' => true, 'data' => $result]);
  }

  public function returnBook()
  {
    $itemId = $_POST['borrowing_id'] ?? null;

    if (!$itemId) {
      $this->sendJson(['success' => false, 'message' => 'Borrowing Item ID is required.'], 400);
      return;
    }

    $success = $this->returningRepo->markAsReturned((int)$itemId);

    if ($success) {
      $this->sendJson(['success' => true, 'message' => 'Book returned successfully!']);
    } else {
      $this->sendJson(['success' => false, 'message' => 'Failed to return book. It might be already returned or a database error occurred.']);
    }
  }

  public function extendDueDate()
  {
    $itemId = $_POST['borrowing_id'] ?? null;
    $daysToExtend = $_POST['days'] ?? null;

    if (!$itemId || !$daysToExtend) {
      $this->sendJson(['success' => false, 'message' => 'Borrowing Item ID and extension days are required.'], 400);
      return;
    }

    if (!is_numeric($daysToExtend) || $daysToExtend <= 0) {
      $this->sendJson(['success' => false, 'message' => 'Invalid number of days.'], 400);
      return;
    }

    $newDueDate = $this->returningRepo->extendDueDate((int)$itemId, (int)$daysToExtend);

    if ($newDueDate) {
      $this->sendJson([
        'success' => true,
        'message' => 'Due date extended successfully!',
        'new_due_date' => $newDueDate
      ]);
    } else {
      $this->sendJson(['success' => false, 'message' => 'Failed to extend due date. Transaction not found, already returned, or a database error occurred.']);
    }
  }
}
