<?php

namespace App\Controllers;

use App\Repositories\BookManagementRepository;
use App\Core\Controller;

class BookManagementController extends Controller
{
    private $bookRepo;

    public function __construct()
    {
        $this->bookRepo = new BookManagementRepository();
    }

    private function json($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    private function handleImageUpload($file)
    {
        if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
            return null;
        }
        $targetDir = "uploads/book_covers/";
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $fileName = uniqid('book_', true) . '.' . $extension;
        $targetFile = $targetDir . $fileName;
        if (move_uploaded_file($file['tmp_name'], $targetFile)) {
            return BASE_URL . $targetFile;
        }
        return null;
    }

    public function fetch()
    {
        try {
            $search   = $_GET['search'] ?? '';
            $status   = $_GET['status'] ?? 'All Status';
            $sort     = $_GET['sort'] ?? 'default';
            $limit    = (int)($_GET['limit'] ?? 30);
            $offset   = (int)($_GET['offset'] ?? 0);

            $books = $this->bookRepo->getPaginatedBooks($limit, $offset, $search, $status, $sort);
            $totalCount = $this->bookRepo->countPaginatedBooks($search, $status);

            $this->json(['success' => true, 'books' => $books, 'totalCount' => $totalCount]);
        } catch (\Exception $e) {
            $this->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function getDetails($id)
    {
        try {
            $book = $this->bookRepo->findBookById($id);
            if (!$book) {
                return $this->json(['success' => false, 'message' => 'Book not found.'], 404);
            }
            $this->json(['success' => true, 'book' => $book]);
        } catch (\Exception $e) {
            $this->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function store()
    {
        $data = $_POST;
        if (empty($data['title']) || empty($data['author']) || empty($data['accession_number']) || empty($data['call_number'])) {
            return $this->json(['success' => false, 'message' => 'Required fields are missing.'], 400);
        }
        if (isset($_FILES['book_image']) && $_FILES['book_image']['error'] == 0) {
            $imagePath = $this->handleImageUpload($_FILES['book_image']);
            if ($imagePath) {
                $data['cover'] = $imagePath;
            } else {
                return $this->json(['success' => false, 'message' => 'Error uploading file.'], 500);
            }
        }
        try {
            $success = $this->bookRepo->createBook($data);
            if ($success) {
                $this->json(['success' => true, 'message' => 'Book added successfully!']);
            } else {
                $this->json(['success' => false, 'message' => 'Database error.'], 500);
            }
        } catch (\Exception $e) {
            if (str_contains($e->getMessage(), 'Duplicate entry')) {
                return $this->json(['success' => false, 'message' => 'Accession number or ISBN might already exist.'], 409);
            }
            $this->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function update($id)
    {
        $data = $_POST;
        if (empty($data['title']) || empty($data['author']) || empty($data['accession_number']) || empty($data['call_number'])) {
            return $this->json(['success' => false, 'message' => 'Required fields are missing.'], 400);
        }

        $currentUserId = $_SESSION['user_id'] ?? null;
        if ($currentUserId === null) {
            return $this->json(['success' => false, 'message' => 'Authentication required.'], 401);
        }

        if (isset($_FILES['book_image']) && $_FILES['book_image']['error'] == 0) {
            $imagePath = $this->handleImageUpload($_FILES['book_image']);
            if ($imagePath) {
                $data['cover'] = $imagePath;
            } else {
                return $this->json(['success' => false, 'message' => 'Error uploading new file.'], 500);
            }
        }

        try {
            $book = $this->bookRepo->findBookById($id);
            if ($book && isset($book['availability'])) {
                $data['availability'] = $book['availability'];
            }

            $success = $this->bookRepo->updateBook($id, $data, $currentUserId);

            if ($success) {
                $this->json(['success' => true, 'message' => 'Book updated successfully!']);
            } else {
                $this->json(['success' => false, 'message' => 'Failed to update or no changes made.'], 500);
            }
        } catch (\Exception $e) {
            $this->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        $bookId = (int)$id;
        $deletedByUserId = $_SESSION['user_id'] ?? null;

        if ($deletedByUserId === null) {
            return $this->json(['success' => false, 'message' => 'Authentication required.'], 401);
        }

        try {
            $book = $this->bookRepo->findBookById($bookId);

            if (!$book) {
                return $this->json(['success' => false, 'message' => 'Book not found.'], 404);
            }

            if ($book['availability'] === 'borrowed') {
                return $this->json([
                    'success' => false,
                    'message' => 'Cannot delete book. It is currently borrowed.'
                ], 409); 
            }

            $result = $this->bookRepo->deleteBook($bookId, $deletedByUserId);

            $status = $result['success'] ? 200 : 400;

            if ($result['message'] === 'Book not found.') $status = 404;
            if ($result['message'] === 'Book already deleted.') $status = 409;

            return $this->json($result, $status);
        } catch (\PDOException $e) {
            return $this->json([
                'success' => false,
                'message' => 'Internal server error.'
            ], 500);
        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function bulkImport()
    {
        header('Content-Type: application/json');

        $imported = 0;
        $errors = [];

        if (!isset($_FILES['csv_file'])) {
            echo json_encode(['success' => false, 'message' => 'No file uploaded.']);
            exit;
        }

        $file = $_FILES['csv_file']['tmp_name'];

        if (!file_exists($file) || !is_readable($file)) {
            echo json_encode(['success' => false, 'message' => 'Uploaded file not readable.']);
            exit;
        }

        $bookRepo = new \App\Repositories\BookManagementRepository();

        if (($handle = fopen($file, 'r')) !== false) {
            $header = fgetcsv($handle);
            $rowNumber = 2; 

            while (($row = fgetcsv($handle)) !== false) {
                $accessionNumber = trim($row[0] ?? '');
                $callNumber      = trim($row[1] ?? '');
                $title           = trim($row[2] ?? '');
                $author          = trim($row[3] ?? '');
                $bookPlace       = trim($row[4] ?? '');
                $bookPublisher   = trim($row[5] ?? '');
                $year            = trim($row[6] ?? '');
                $bookEdition     = trim($row[7] ?? '');
                $description     = trim($row[8] ?? '');
                $bookISBN        = trim($row[9] ?? '');
                $bookSupplementary = trim($row[10] ?? '');
                $subject         = trim($row[11] ?? '');

                if (!$accessionNumber) {
                    $errors[] = "Row $rowNumber: Missing required field accession_number.";
                    $rowNumber++;
                    continue;
                }

                try {
                    $bookRepo->createBook([
                        'accession_number'   => $accessionNumber,
                        'call_number'        => $callNumber ?: null,
                        'title'              => $title ?: null,
                        'author'             => $author ?: null,
                        'book_place'         => $bookPlace ?: null,
                        'book_publisher'     => $bookPublisher ?: null,
                        'year'               => $year ?: null,
                        'book_edition'       => $bookEdition ?: null,
                        'description'        => $description ?: null,
                        'book_isbn'          => $bookISBN ?: null,
                        'book_supplementary' => $bookSupplementary ?: null,
                        'subject'            => $subject ?: null,
                        'availability'       => 'available', // default
                    ]);
                    $imported++;
                } catch (\Exception $e) {
                    $errors[] = "Row $rowNumber: " . $e->getMessage();
                }

                $rowNumber++;
            }
            fclose($handle);
        }

        echo json_encode([
            'success'  => true,
            'imported' => $imported,
            'errors'   => $errors
        ]);
    }
}
