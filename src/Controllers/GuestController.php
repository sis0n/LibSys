<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Repositories\BookRepository;

class GuestController extends Controller
{
    private $bookRepository;

    public function __construct()
    {
        $this->bookRepository = new BookRepository();
    }

    public function guestDisplay()
    {
        $books = $this->bookRepository->getAllBooks();

        $this->view("Guest/landingPage", [
            "title" => "LOSUCC",
            "books" => $books
        ], false);
    }

    public function fetchGuestBooks()
    {
        $search = $_GET['search'] ?? '';
        $offset = (int)($_GET['offset'] ?? 0);
        $limit  = (int)($_GET['limit'] ?? 30);
        $category = $_GET['category'] ?? '';
        $status   = $_GET['status'] ?? '';

        $books = $this->bookRepository->getPaginatedFiltered($limit, $offset, $search, $category, $status);

        header('Content-Type: application/json');
        echo json_encode($books);
    }
}