<?php
	session_start();
	require_once "./functions/database_functions.php";
	// Lấy pubid từ URL
	if(isset($_GET['pubid'])){
		$pubid = $_GET['pubid'];
	} else {
		echo "Wrong query! Check again!";
		exit;
	}

	// Kết nối cơ sở dữ liệu
	$conn = db_connect();
	$pubName = getPubName($conn, $pubid);

	// Sử dụng truy vấn có tham số để lấy sách theo publisherid
	$query = "SELECT book_isbn, book_title, book_image FROM books WHERE publisherid = ?";
	$params = array($pubid);
	$result = sqlsrv_query($conn, $query, $params);
	if($result === false){
		echo "Can't retrieve data: " . print_r(sqlsrv_errors(), true);
		exit;
	}
	// Kiểm tra xem có dữ liệu hay không
	if(!sqlsrv_has_rows($result)){
		echo "Empty books! Please wait until new books coming!";
		exit;
	}

	$title = "Books Per Publisher";
	require "./template/header.php";
?>
	<p class="lead"><a href="publisher_list.php">Publishers</a> > <?php echo $pubName; ?></p>
	<?php 
	while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
	?>
	<div class="row">
		<div class="col-md-3">
			<img class="img-responsive img-thumbnail" src="./bootstrap/img/<?php echo $row['book_image']; ?>">
		</div>
		<div class="col-md-7">
			<h4><?php echo $row['book_title']; ?></h4>
			<a href="book.php?bookisbn=<?php echo $row['book_isbn']; ?>" class="btn btn-primary">Get Details</a>
		</div>
	</div>
	<br>
	<?php
	}
	if(isset($conn)) { 
		sqlsrv_close($conn); 
	}
	require "./template/footer.php";
?>
