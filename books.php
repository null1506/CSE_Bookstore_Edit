<?php
	session_start();
	$count = 0;
	require_once "./functions/database_functions.php";
	$conn = db_connect(); // Hàm db_connect() được viết sử dụng sqlsrv_connect()

	$query = "SELECT book_isbn, book_image FROM books";
	// Sử dụng scrollable cursor để có thể dùng sqlsrv_num_rows nếu cần
	$result = sqlsrv_query($conn, $query, null, array("Scrollable" => SQLSRV_CURSOR_STATIC));
	if($result === false){
		echo "Can't retrieve data: " . print_r(sqlsrv_errors(), true);
		exit;
	}

	$title = "Full Catalogs of Books";
	require_once "./template/header.php";
?>
	<p class="lead text-center text-muted">Full Catalogs of Books</p>
	<?php
		// Hiển thị các cuốn sách, mỗi 4 cuốn trong một hàng (row)
		echo '<div class="row">';
		while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
			?>
			<div class="col-md-3">
				<a href="book.php?bookisbn=<?php echo $row['book_isbn']; ?>">
					<img class="img-responsive img-thumbnail" src="./bootstrap/img/<?php echo $row['book_image']; ?>">
				</a>
			</div>
			<?php
			$count++;
			if($count % 4 == 0){
				echo '</div><br><div class="row">';
			}
		}
		echo '</div>';
	?>
<?php
	if(isset($conn)) { sqlsrv_close($conn); }
	require_once "./template/footer.php";
?>
