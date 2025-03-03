<?php
	session_start();
	if(!isset($_POST['submit'])){
		echo "Something wrong! Check again!";
		exit;
	}
	require_once "./functions/database_functions.php";
	$conn = db_connect(); // Hàm db_connect() được viết để sử dụng sqlsrv_connect()

	$name = trim($_POST['name']);
	$pass = trim($_POST['pass']);

	if($name == "" || $pass == ""){
		echo "Name or Pass is empty!";
		exit;
	}

	// Trong sqlsrv không có hàm tương đương mysqli_real_escape_string, nên nếu không dùng truy vấn tham số,
	// bạn có thể dùng addslashes() hoặc tốt nhất là sử dụng truy vấn tham số. Ở đây query không sử dụng biến,
	// vì vậy ta có thể bỏ qua bước escape.
	$pass = sha1($pass);

	// Lấy dữ liệu admin từ CSDL SQL Server
	$query = "SELECT name, pass FROM admin";
	$result = sqlsrv_query($conn, $query);
	if($result === false){
		echo "Empty data: " . print_r(sqlsrv_errors(), true);
		exit;
	}
	$row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);

	// So sánh thông tin đăng nhập
	if($name != $row['name'] || $pass != $row['pass']){ //đã sửa && thành ||
		echo "Name or pass is wrong. Check again!";
		$_SESSION['admin'] = false;
		exit;
	}

	if(isset($conn)) { 
		sqlsrv_close($conn); 
	}
	$_SESSION['admin'] = true;
	header("Location: admin_book.php");
?>
