<?php
	session_start();
	require_once "./functions/admin.php";
	$title = "Add new book";
	require "./template/header.php";
	require_once "./functions/database_functions.php";
	$conn = db_connect(); // Hàm db_connect() của bạn cần sử dụng sqlsrv_connect()

	if(isset($_POST['add'])){
		// Lấy dữ liệu từ form và loại bỏ khoảng trắng
		$isbn       = trim($_POST['isbn']);
		$title_book = trim($_POST['title']);
		$author     = trim($_POST['author']);
		$descr      = trim($_POST['descr']);
		$price      = floatval(trim($_POST['price']));
		$publisher  = trim($_POST['publisher']);
		
		// Xử lý upload hình ảnh
		$image = "";
		if(isset($_FILES['image']) && $_FILES['image']['name'] != ""){
			$image = $_FILES['image']['name'];
			$directory_self = str_replace(basename($_SERVER['PHP_SELF']), '', $_SERVER['PHP_SELF']);
			$uploadDirectory = $_SERVER['DOCUMENT_ROOT'] . $directory_self . "bootstrap/img/";
			$uploadPath = $uploadDirectory . $image;
			move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath);
		}

		// Tìm publisher theo publisher_name
		$query = "SELECT * FROM publisher WHERE publisher_name = ?";
		$params = array($publisher);
		$stmt = sqlsrv_query($conn, $query, $params);
		if($stmt === false){
			echo "Error executing query: " . print_r(sqlsrv_errors(), true);
			exit;
		}
		$rowPub = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
		if(!$rowPub){
			// Publisher không tồn tại, thêm mới
			$insertPub = "INSERT INTO publisher (publisher_name) VALUES (?)";
			$paramsInsert = array($publisher);
			$stmtInsert = sqlsrv_query($conn, $insertPub, $paramsInsert);
			if($stmtInsert === false){
				echo "Can't add new publisher: " . print_r(sqlsrv_errors(), true);
				exit;
			}
			// Lấy publisherid mới vừa được insert
			$queryId = "SELECT SCOPE_IDENTITY() AS publisherid";
			$stmtId = sqlsrv_query($conn, $queryId);
			if($stmtId === false){
				echo "Can't retrieve new publisher id: " . print_r(sqlsrv_errors(), true);
				exit;
			}
			$rowId = sqlsrv_fetch_array($stmtId, SQLSRV_FETCH_ASSOC);
			$publisherid = $rowId['publisherid'];
		} else {
			$publisherid = $rowPub['publisherid'];
		}

		// Chèn dữ liệu vào bảng books
		$queryBooks = "INSERT INTO books (book_isbn, book_title, book_author, book_image, book_descr, book_price, publisherid) 
		               VALUES (?,?,?,?,?,?,?)";
		$paramsBooks = array($isbn, $title_book, $author, $image, $descr, $price, $publisherid);
		$stmtBooks = sqlsrv_query($conn, $queryBooks, $paramsBooks);
		if($stmtBooks === false){
			echo "Can't add new data: " . print_r(sqlsrv_errors(), true);
			exit;
		} else {
			header("Location: admin_book.php");
		}
	}
?>
	<form method="post" action="admin_add.php" enctype="multipart/form-data">
		<table class="table">
			<tr>
				<th>ISBN</th>
				<td><input type="text" name="isbn"></td>
			</tr>
			<tr>
				<th>Title</th>
				<td><input type="text" name="title" required></td>
			</tr>
			<tr>
				<th>Author</th>
				<td><input type="text" name="author" required></td>
			</tr>
			<tr>
				<th>Image</th>
				<td><input type="file" name="image"></td>
			</tr>
			<tr>
				<th>Description</th>
				<td><textarea name="descr" cols="40" rows="5"></textarea></td>
			</tr>
			<tr>
				<th>Price</th>
				<td><input type="text" name="price" required></td>
			</tr>
			<tr>
				<th>Publisher</th>
				<td><input type="text" name="publisher" required></td>
			</tr>
		</table>
		<input type="submit" name="add" value="Add new book" class="btn btn-primary">
		<input type="reset" value="Cancel" class="btn btn-default">
	</form>
	<br/>
<?php
	if(isset($conn)) { sqlsrv_close($conn); }
	require_once "./template/footer.php";
?>
