<?php
session_start();
require_once './functions/database_functions.php'; // File này chứa hàm db_connect() sử dụng sqlsrv_connect()
$conn = db_connect();

$responseMessage = "Operation completed successfully.";

try {
    // Lấy giá trị từ form POST mà không thực hiện kiểm tra kiểu số để cho phép payload injection
    $price = isset($_POST['price']) ? trim($_POST['price']) : '';

    // Xây dựng truy vấn bằng cách nối chuỗi trực tiếp (vulnerable to SQL injection)
    // Nếu người dùng nhập payload "20'; WAITFOR DELAY '0:0:10' -- -", câu lệnh sẽ thành:
    // SELECT COUNT(book_isbn) AS BookCount FROM books WHERE book_price <= '20'; WAITFOR DELAY '0:0:10'
    $query = "SELECT COUNT(book_isbn) AS BookCount FROM books WHERE book_price <= '$price' ";
    
    $stmt = sqlsrv_query($conn, $query);
    if ($stmt === false) {
        // Nếu truy vấn thất bại (ví dụ do cấu hình không cho phép multiple statements), ném exception
        throw new Exception("Query execution failed.");
        $responseMessage = "Operation completed successfully.";
    }
    
    // Nếu truy vấn thực thi thành công, chúng ta không quan tâm kết quả, chỉ trả về OK.
    
} catch (Exception $e) {
    // Ghi log lỗi nội bộ, nhưng trả về thông báo chung OK cho client
    error_log($e->getMessage());
    $responseMessage = "Operation completed successfully.";
}

sqlsrv_close($conn);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Books Search by Price</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <h1>Search Books by Price</h1>
    <form method="post" action="search_1.php" class="form-inline">
        <div class="form-group">
            <label for="price">Enter price:</label>
            <input type="text" class="form-control" id="price" name="price" value="<?php echo htmlspecialchars($price); ?>">
        </div>
        <button type="submit" class="btn btn-primary">Search</button>
    </form>
    <hr>
    <div class="alert alert-success">
        <?php echo htmlspecialchars($responseMessage); ?>
    </div>
</div>
</body>
</html>
