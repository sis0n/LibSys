step 1:
C:\Users\adria> "C:\xampp\mysql\bin\mysql.exe" --local-infile=1 -u root -p library_system -e "LOAD DATA LOCAL INFILE 'C:/xampp/htdocs/libsys/docs/Collection_Main_Campus_Library_final.csv' INTO TABLE books CHARACTER SET utf8mb4 FIELDS TERMINATED BY ',' ENCLOSED BY '\"' LINES TERMINATED BY '\n' IGNORE 1 ROWS (accession_number, call_number, title, author, book_place, book_publisher, year, book_edition, description, book_isbn, book_supplementary, subject);"

step 2: pag may lumabas na "enter password:" click mo lang enter
step 3: ienter sa cmd ""C:\xampp\mysql\bin\mysql.exe" -u root -p"
step 4: ipaste to "USE library_system;"
step 5: ipaste to "SELECT COUNT(*) FROM books;" pang check lang yan kung may data na
step 6: paste ulit to "SELECT * FROM books LIMIT 5;" pang preview lang yan ng laman ng db