<?php
namespace App\Models;

class Borrowing {
    public $borrowing_id;
    public $student_id;
    public $qr_token;
    public $book_id;
    public $borrowed_at;
    public $due_date;
    public $returned_at;
    public $status;
}
