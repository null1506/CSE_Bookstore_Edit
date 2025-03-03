<?php
	session_start();
	require_once "./functions/database_functions.php";
	$conn = db_connect();

	$query = "SELECT * FROM publisher ORDER BY publisherid";
	// Sử dụng tùy chọn scrollable để có thể đếm số dòng
	$result = sqlsrv_query($conn, $query, null, array("Scrollable" => SQLSRV_CURSOR_STATIC));
	if($result === false){
		echo "Can't retrieve data " . print_r(sqlsrv_errors(), true);
		exit;
	}
	if(sqlsrv_num_rows($result) == 0){
		echo "Empty publisher! Something wrong! Check again";
		exit;
	}

	$title = "List Of Publishers";
	require "./template/header.php";
?>
	<p class="lead">List of Publisher</p>
	<ul>
	<?php 
		while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
			$count = 0; 
			$query2 = "SELECT publisherid FROM books";
			$result2 = sqlsrv_query($conn, $query2, null, array("Scrollable" => SQLSRV_CURSOR_STATIC));
			if($result2 === false){
				echo "Can't retrieve data " . print_r(sqlsrv_errors(), true);
				exit;
			}
			while ($pubInBook = sqlsrv_fetch_array($result2, SQLSRV_FETCH_ASSOC)){
				if($pubInBook['publisherid'] == $row['publisherid']){
					$count++;
				}
			}
	?>
		<li>
			<span class="badge"><?php echo $count; ?></span>
		    <a href="bookPerPub.php?pubid=<?php echo $row['publisherid']; ?>"><?php echo $row['publisher_name']; ?></a>
		</li>
	<?php } ?>
		<li>
			<a href="books.php">List full of books</a>
		</li>
	</ul>
<?php
	sqlsrv_close($conn);
	require "./template/footer.php";
?>
