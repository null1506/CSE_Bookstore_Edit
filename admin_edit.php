<?php
	session_start();
	require_once "./functions/admin.php";
	$title = "Edit book";
	require_once "./template/header.php";
	require_once "./functions/database_functions.php";
	$conn = db_connect(); // Hàm db_connect() cần được viết để sử dụng sqlsrv_connect()

	// Lấy giá trị bookisbn từ URL
	if(isset($_GET['bookisbn'])){
		$book_isbn = $_GET['bookisbn'];
	} else {
		echo "Empty query!";
		exit;
	}

	if(!isset($book_isbn)){
		echo "Empty isbn! check again!";
		exit;
	}

	// Lấy dữ liệu sách theo book_isbn (sử dụng truy vấn tham số)
	$query = "SELECT * FROM books WHERE book_isbn = '$book_isbn'";
	//$params = array($book_isbn);
	$result = sqlsrv_query($conn, $query);
	if($result === false){
		echo "Can't retrieve data: ";
		die(print_r(sqlsrv_errors(), true));
	}
	$row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
?>
	<form method="post" action="edit_book.php" enctype="multipart/form-data">
		<table class="table">
			<tr>
				<th>ISBN</th>
				<td><input type="text" name="isbn" value="<?php echo $row['book_isbn']; ?>" readonly="true"></td>
			</tr>
			<tr>
				<th>Title</th>
				<td><input type="text" name="title" value="<?php echo $row['book_title']; ?>" required></td>
			</tr>
			<tr>
				<th>Author</th>
				<td><input type="text" name="author" value="<?php echo $row['book_author']; ?>" required></td>
			</tr>
			<tr>
				<th>Image</th>
				<td><input type="file" name="image"></td>
			</tr>
			<tr>
				<th>Description</th>
				<td><textarea name="descr" cols="40" rows="5"><?php echo $row['book_descr']; ?></textarea></td>
			</tr>
			<tr>
				<th>Price</th>
				<td><input type="text" name="price" value="<?php echo $row['book_price']; ?>" required></td>
			</tr>
			<tr>
				<th>Publisher</th>
				<td><input type="text" name="publisher" value="<?php echo getPubName($conn, $row['publisherid']); ?>" required></td>
			</tr>
		</table>
		<input type="submit" name="save_change" value="Change" class="btn btn-primary">
		<input type="reset" value="Cancel" class="btn btn-default">
	</form>
	<br/>
	<a href="admin_book.php" class="btn btn-success">Confirm</a>
<?php
	if(isset($conn)) { 
		sqlsrv_close($conn); 
	}
	require_once "./template/footer.php";
?>
