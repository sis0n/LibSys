ALTER TABLE books
    ADD COLUMN accession_number VARCHAR(50) NOT NULL UNIQUE AFTER book_id,
    ADD COLUMN call_number VARCHAR(50) AFTER accession_number,
    ADD COLUMN book_place VARCHAR(150) AFTER author,
    ADD COLUMN book_publisher VARCHAR(150) AFTER book_place,
    ADD COLUMN year YEAR AFTER book_publisher,
    ADD COLUMN book_edition VARCHAR(50) AFTER year,
    ADD COLUMN description TEXT AFTER book_edition,
    ADD COLUMN book_isbn VARCHAR(50) AFTER description,
    ADD COLUMN book_supplementary VARCHAR(255) AFTER book_isbn,
    ADD COLUMN subject VARCHAR(255) AFTER book_supplementary;

-- optional: adjust column sizes
ALTER TABLE books
    MODIFY title VARCHAR(255) NOT NULL,
    MODIFY author VARCHAR(255);

-- drop old columns if not needed anymore
ALTER TABLE books
    DROP COLUMN category,
    DROP COLUMN location,
    DROP COLUMN isbn,
    DROP COLUMN availability;



DROP TABLE IF EXISTS borrowings;
DROP TABLE IF EXISTS books;

-- Recreate books
CREATE TABLE books (
    book_id INT AUTO_INCREMENT PRIMARY KEY,
    accession_number VARCHAR(50) NOT NULL UNIQUE,
    call_number VARCHAR(50),
    title VARCHAR(255) NOT NULL,
    author VARCHAR(255),
    book_place VARCHAR(150),
    book_publisher VARCHAR(150),
    year YEAR,
    book_edition VARCHAR(50),
    description TEXT,
    book_isbn VARCHAR(50),
    book_supplementary VARCHAR(255),
    subject VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Recreate borrowings with new FK to books
CREATE TABLE borrowings (
  borrowing_id INT AUTO_INCREMENT PRIMARY KEY,
  student_id INT,
  book_id INT,
  borrowed_at DATETIME,
  due_date DATETIME,
  returned_at DATETIME NULL,
  status ENUM('borrowed','returned','overdue') DEFAULT 'borrowed',
  FOREIGN KEY (student_id) REFERENCES students(student_id),
  FOREIGN KEY (book_id) REFERENCES books(book_id)
);


-- pang add ng csv file sa mysql pero naka query
LOAD DATA INFILE '/path/to/books.csv'
INTO TABLE books
FIELDS TERMINATED BY ','
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS
(accession_number, call_number, title, author, book_place, book_publisher, year, book_edition, description, book_isbn, book_supplementary, subject);


CREATE TABLE carts (
  cart_id INT AUTO_INCREMENT PRIMARY KEY,
  student_id INT NOT NULL,
  book_id INT NOT NULL,
  added_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (student_id) REFERENCES students(student_id),
  FOREIGN KEY (book_id) REFERENCES books(book_id)
);

CREATE TABLE borrow_transactions (
  transaction_id INT AUTO_INCREMENT PRIMARY KEY,
  student_id INT NOT NULL,
  transaction_code VARCHAR(50) UNIQUE, -- for QR code
  borrowed_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  due_date DATETIME NOT NULL,
  status ENUM('pending','borrowed','returned','overdue') DEFAULT 'pending',
  FOREIGN KEY (student_id) REFERENCES students(student_id)
);

CREATE TABLE borrow_transaction_items (
  item_id INT AUTO_INCREMENT PRIMARY KEY,
  transaction_id INT NOT NULL,
  book_id INT NOT NULL,
  returned_at DATETIME NULL,
  status ENUM('borrowed','returned','overdue') DEFAULT 'borrowed',
  FOREIGN KEY (transaction_id) REFERENCES borrow_transactions(transaction_id),
  FOREIGN KEY (book_id) REFERENCES books(book_id)
);

ALTER TABLE books 
ADD COLUMN availability ENUM('available','borrowed') DEFAULT 'available';

ALTER TABLE books ADD COLUMN quantity INT NOT NULL DEFAULT 1;
ALTER TABLE books ADD COLUMN cover VARCHAR(255) NULL;
