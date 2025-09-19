-- Users
CREATE TABLE users (
  user_id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) UNIQUE,
  password VARCHAR(255),
  full_name VARCHAR(100),
  email VARCHAR(100),
  role ENUM('superadmin','admin','librarian','student'),
  is_active BOOLEAN DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Attendance Logs
CREATE TABLE attendance_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    student_number VARCHAR(50) NOT NULL,
    full_name VARCHAR(150) NOT NULL,
    course VARCHAR(100) NOT NULL,
    year_level VARCHAR(10) NOT NULL,
    student_id INT NOT NULL,
    method ENUM('qr','manual') DEFAULT 'qr',
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (student_id) REFERENCES students(student_id)
) ENGINE=InnoDB;

-- Permissions
CREATE TABLE permissions (
  permission_id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100),
  code VARCHAR(50) UNIQUE,
  description TEXT
);

-- User Permissions (Access Control)
CREATE TABLE user_permissions (
  user_id INT,
  permission_id INT,
  granted BOOLEAN DEFAULT 1,
  PRIMARY KEY (user_id, permission_id),
  FOREIGN KEY (user_id) REFERENCES users(user_id),
  FOREIGN KEY (permission_id) REFERENCES permissions(permission_id)
);

-- Students
CREATE TABLE students (
  student_id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNIQUE,
  student_number VARCHAR(20) UNIQUE,
  course VARCHAR(50),
  year_level INT,
  status ENUM('enrolled','dropped','transferred') DEFAULT 'enrolled',
  FOREIGN KEY (user_id) REFERENCES users(user_id)
);

-- Books
CREATE TABLE books (
  book_id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(200),
  author VARCHAR(100),
  category VARCHAR(50),
  location VARCHAR(50),
  isbn VARCHAR(20),
  availability ENUM('available','borrowed','lost','damaged') DEFAULT 'available',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Borrowings
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

-- Attendance
CREATE TABLE attendance (
  attendance_id INT AUTO_INCREMENT PRIMARY KEY,
  student_id INT,
  scanned_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (student_id) REFERENCES students(student_id)
);

-- Reports
CREATE TABLE reports (
  report_id INT AUTO_INCREMENT PRIMARY KEY,
  generated_by INT,
  report_type VARCHAR(50),
  file_path VARCHAR(255),
  generated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (generated_by) REFERENCES users(user_id)
);

-- Activity Logs
CREATE TABLE activity_logs (
  log_id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  action VARCHAR(100),
  details TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(user_id)
);