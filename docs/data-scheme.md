users (
user_id INT AUTO_INCREMENT PRIMARY KEY,
username VARCHAR(50) UNIQUE,
password VARCHAR(255),
full_name VARCHAR(100),
email VARCHAR(100),
role ENUM('superadmin','admin','librarian','student'),
is_active BOOLEAN DEFAULT 1,
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

permissions (
permission_id INT AUTO_INCREMENT PRIMARY KEY,
name VARCHAR(100), -- e.g., Manage Books
code VARCHAR(50) UNIQUE, -- e.g., MANAGE_BOOKS
description TEXT
);

user_permissions (
user_id INT,
permission_id INT,
granted BOOLEAN DEFAULT 1,
PRIMARY KEY (user_id, permission_id),
FOREIGN KEY (user_id) REFERENCES users(user_id),
FOREIGN KEY (permission_id) REFERENCES permissions(permission_id)
);

students (
student_id INT AUTO_INCREMENT PRIMARY KEY,
user_id INT UNIQUE, -- links to users
student_number VARCHAR(20) UNIQUE,
course VARCHAR(50),
year_level INT,
status ENUM('enrolled','dropped','transferred') DEFAULT 'enrolled',
FOREIGN KEY (user_id) REFERENCES users(user_id)
);

books (
book_id INT AUTO_INCREMENT PRIMARY KEY,
title VARCHAR(200),
author VARCHAR(100),
category VARCHAR(50),
location VARCHAR(50),
isbn VARCHAR(20),
availability ENUM('available','borrowed','lost','damaged') DEFAULT 'available',
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

borrowings (
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

attendance (
attendance_id INT AUTO_INCREMENT PRIMARY KEY,
student_id INT,
scanned_at DATETIME DEFAULT CURRENT_TIMESTAMP,
FOREIGN KEY (student_id) REFERENCES students(student_id)
);

reports (
report_id INT AUTO_INCREMENT PRIMARY KEY,
generated_by INT, -- librarian/admin
report_type VARCHAR(50), -- e.g., attendance, borrowed_books
file_path VARCHAR(255),
generated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
FOREIGN KEY (generated_by) REFERENCES users(user_id)
);

activity_logs (
log_id INT AUTO_INCREMENT PRIMARY KEY,
user_id INT,
action VARCHAR(100), -- e.g., "Added Book", "Borrowed Book"
details TEXT,
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
FOREIGN KEY (user_id) REFERENCES users(user_id)
);