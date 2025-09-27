<?php

namespace App\Controllers;

use App\Repositories\BookRepository;
use App\Core\Controller;
use PDO;

class BookController extends Controller
{
  private $bookRepo;

  public function __construct()
  {
    $this->bookRepo = new BookRepository();
  }

  public function index()
  {
    $books = $this->bookRepo->getAllBooks();
    $this->view("books/index", [
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

  public function catalog()
  {
    $books = $this->bookRepo->getAllBooks();

    $availableCount = count(array_filter($books, function ($book) {
      return isset($book['availability']) && strtolower($book['availability']) === 'available';
    }));

    $this->view("Student/bookCatalog", [
      "books" => $books,
      "available_count" => $availableCount,
      "title" => "Book Catalog",
      "currentPage" => "bookCatalog"
    ]);
  }

  public function fetch()
  {
    $search = $_GET['search'] ?? '';
    $offset = (int)($_GET['offset'] ?? 0);
    $limit  = (int)($_GET['limit'] ?? 30);
    $category = $_GET['category'] ?? '';
    $status   = $_GET['status'] ?? '';

    $books = $this->bookRepo->getPaginatedFiltered($limit, $offset, $search, $category, $status);

    header('Content-Type: application/json');
    echo json_encode($books);
  }
}
