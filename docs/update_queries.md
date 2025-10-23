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


-- pang tanggal lang ng c sa year column ng books table
UPDATE books
SET year = REPLACE(year, 'c', '')
WHERE year LIKE 'c%';

-- ITO TALAGA 
ALTER TABLE borrow_transaction_items DROP FOREIGN KEY borrow_transaction_items_ibfk_2;
ALTER TABLE borrowings DROP FOREIGN KEY borrowings_ibfk_2;
ALTER TABLE carts DROP FOREIGN KEY carts_ibfk_2;

TRUNCATE TABLE books;

-- then ibalik ulit foreign keys
ALTER TABLE borrow_transaction_items 
    ADD CONSTRAINT borrow_transaction_items_ibfk_2 FOREIGN KEY (book_id) REFERENCES books(book_id);
ALTER TABLE borrowings 
    ADD CONSTRAINT borrowings_ibfk_2 FOREIGN KEY (book_id) REFERENCES books(book_id);
ALTER TABLE carts 
    ADD CONSTRAINT carts_ibfk_2 FOREIGN KEY (book_id) REFERENCES books(book_id);

-- IRESET UNG AUTO INCREMENT
ALTER TABLE books AUTO_INCREMENT = 1;

ALTER TABLE carts
ADD COLUMN checkout_token VARCHAR(255) NULL,
ADD COLUMN checked_out_at DATETIME NULL;

CREATE TABLE deleted_users (
  user_id INT,
  username VARCHAR(50),
  full_name VARCHAR(100),
  email VARCHAR(100),
  role ENUM('superadmin','admin','librarian','student','scanner'),
  deleted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  deleted_by INT,
  FOREIGN KEY (deleted_by) REFERENCES users(user_id)
);

CREATE TABLE deleted_students (
  student_id INT,
  user_id INT,
  student_number VARCHAR(20),
  course VARCHAR(50),
  year_level INT,
  status ENUM('enrolled','dropped','transferred'),
  deleted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  deleted_by INT,
  FOREIGN KEY (deleted_by) REFERENCES users(user_id)
);

ALTER TABLE users ADD COLUMN deleted_at TIMESTAMP NULL;
ALTER TABLE students ADD COLUMN deleted_at TIMESTAMP NULL;

ALTER TABLE users ADD COLUMN deleted_by INT NULL;
ALTER TABLE students ADD COLUMN deleted_by INT NULL;

ALTER TABLE users ADD COLUMN updated_at DATETIME NULL;


CREATE TABLE `deleted_books` (
  `deleted_book_id` int(11) NOT NULL AUTO_INCREMENT,
  `book_id` int(11) NOT NULL,
  `accession_number` varchar(50) NOT NULL,
  `call_number` varchar(50) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(255) DEFAULT NULL,
  `book_place` varchar(150) DEFAULT NULL,
  `book_publisher` varchar(150) DEFAULT NULL,
  `year` year(4) DEFAULT NULL,
  `book_edition` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `book_isbn` varchar(50) DEFAULT NULL,
  `book_supplementary` varchar(255) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `availability` enum('available','borrowed') DEFAULT 'available',
  `quantity` int(11) NOT NULL DEFAULT 1,
  `cover` varchar(255) DEFAULT NULL,
  `deleted_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`deleted_book_id`),
  KEY `idx_original_book_id` (`book_id`),
  KEY `idx_deleted_accession` (`accession_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

ALTER TABLE `books`
  ADD COLUMN `updated_at` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP AFTER `cover`,
  ADD COLUMN `updated_by` INT(11) NULL DEFAULT NULL AFTER `updated_at`,
  ADD COLUMN `deleted_at` TIMESTAMP NULL DEFAULT NULL AFTER `updated_by`,
  ADD COLUMN `deleted_by` INT(11) NULL DEFAULT NULL AFTER `deleted_at`;
  
ALTER TABLE `deleted_books`
ADD COLUMN `deleted_by` INT(11) NULL DEFAULT NULL AFTER `deleted_at`;

ALTER TABLE users
ADD COLUMN first_name VARCHAR(100) NULL AFTER full_name,
ADD COLUMN middle_name VARCHAR(100) NULL AFTER first_name,
ADD COLUMN last_name VARCHAR(100) NULL AFTER middle_name,
ADD COLUMN profile_picture VARCHAR(255) NULL AFTER email;

UPDATE users
SET 
    first_name = SUBSTRING_INDEX(full_name, ' ', 1),
    last_name = SUBSTRING_INDEX(full_name, ' ', -1)
WHERE 
    full_name IS NOT NULL;

UPDATE books
SET accession_number = CONCAT('0000', accession_number)
WHERE accession_number IS NOT NULL 
AND accession_number NOT LIKE '0000%';


ALTER TABLE users
DROP COLUMN full_name;





