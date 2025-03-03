<?php
    $book_isbn = $_GET['bookisbn'];

    require_once "./functions/database_functions.php";
    $conn = db_connect();

    // Sử dụng truy vấn có tham số để tránh SQL injection
    $query = "DELETE FROM books WHERE book_isbn = ?";
    $params = array($book_isbn);
    $result = sqlsrv_query($conn, $query, $params);
    if($result === false){
        echo "Delete data unsuccessfully: " . print_r(sqlsrv_errors(), true);
        exit;
    }
    header("Location: admin_book.php");
?>
