<?php
if (isset($_POST['submit'])) {
    if (isset($_POST['book_no']) && isset($_POST['book_name']) &&
        isset($_POST['book_author']) && isset($_POST['student_id']) &&
        isset($_POST['user']) && isset($_POST['issue_date'])) {
        
        $book_no = $_POST['book_no'];
        $book_name = $_POST['book_name'];
        $book_author = $_POST['book_author'];
        $student_id = $_POST['student_id'];
        $user = $_POST['user'];
        $issue_date = $_POST['issue_date'];

        $host = "localhost";
        $dbUsername = "root";
        $dbPassword = "";
        $dbName = "lms";

        $conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);

        if ($conn->connect_error) {
            die('Could not connect to the database.');
        }
        else {
            $Select = "SELECT book_no FROM issued_books WHERE book_no = ? LIMIT 1";
            $Insert = "INSERT INTO issued_books(book_no, book_name, book_author, student_id, user, issue_date) values(?, ?, ?, ?, ?, ?)";

            $stmt = $conn->prepare($Select);
            $stmt->bind_param("i", $book_no);
            $stmt->execute();
            $stmt->bind_result($book_no);
            $stmt->store_result();
            $stmt->fetch();
            $rnum = $stmt->num_rows;

            if ($rnum == 0) {
                $stmt->close();

                $stmt = $conn->prepare($Insert);
                $stmt->bind_param("issisi",$book_no, $book_name, $book_author, $student_id, $user, $issue_date);
                if ($stmt->execute()) {
                    echo "New record inserted sucessfully.";
                }
                else {
                    echo $stmt->error;
                }
            }
            else {
                echo "This book no. is invalid.";
            }
            $stmt->close();
            $conn->close();
        }
    }
    else {
        echo "All field are required.";
        die();
    }
}
else {
    echo "Submit button is not set";
}
?>