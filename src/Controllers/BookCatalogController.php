<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Repositories\BookCatalogRepository;
use PDO;

class BookCatalogController extends Controller
{
  private $bookRepo;

  public function __construct()
  {
    $this->bookRepo = new BookCatalogRepository();
  }

  public function index()
  {
    $books = $this->bookRepo->getAllBooks();
    $this->view("Student/bookCatalog", [
      "books" => $books,
      "title" => "Books Inventory"
    ]);
  }

  public function search()
  {
    $keyword = $_GET['pl'] ?? '';
    $books = $this->bookRepo->searchBooks($keyword);
    $this->view("books/index", [
      "books" => $books,
      "title" => "Search Results"
    ]);
  }

  public function create()
  {
    $this->view("books/create", [
      "title" => "Add New Book"
    ]);
  }

  public function store()
  {
    $data = $_POST;
    $this->bookRepo->addBook($data);
    header("Location: /books");
  }

  public function edit($id)
  {
    $book = $this->bookRepo->getBookById($id);
    $this->view("books/edit", [
      "book" => $book,
      "title" => "Edit Book"
    ]);
  }

  public function update($id)
  {
    $data = $_POST;
    $this->bookRepo->updateBook($id, $data);
    header("Location: /books");
  }

  public function destroy($id)
  {
    $this->bookRepo->deleteBook($id);
    header("Location: /books");
  }

  public function filter()
  {
    $filters = $_GET;
    $books = $this->bookRepo->filterBooks($filters);
    $this->view("books/index", [
      "books" => $books,
      "title" => "Filtered Books"
    ]);
  }

  public function fetch()
  {
    $search   = $_GET['search'] ?? '';
    $offset   = (int)($_GET['offset'] ?? 0);
    $limit    = (int)($_GET['limit'] ?? 30);
    $category = $_GET['category'] ?? ''; 
    $status   = $_GET['status'] ?? '';
    $sort     = $_GET['sort'] ?? 'default'; 

    $books = $this->bookRepo->getPaginatedFiltered(
      $limit,
      $offset,
      $search,
      $category,
      $status,
      $sort 
    );

    $totalCount = $this->bookRepo->countPaginatedFiltered($search, $category, $status);

    $response = [
      'books' => $books,
      'totalCount' => $totalCount
    ];

    header('Content-Type: application/json');
    echo json_encode($response);
  }

  public function getAvailableCount()
  {
    $count = $this->bookRepo->countAvailableBooks();
    header('Content-Type: application/json');
    echo json_encode(['available' => $count]);
  }
}
