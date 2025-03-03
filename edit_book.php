<?php	
	// Nếu không có submit save_change, thông báo lỗi và thoát
	if(!isset($_POST['save_change'])){
		echo "Something wrong!";
		exit;
	}

	$isbn = trim($_POST['isbn']);
	$title = trim($_POST['title']);
	$author = trim($_POST['author']);
	$descr = trim($_POST['descr']);
	$price = floatval(trim($_POST['price']));
	$publisher = trim($_POST['publisher']);

	// Xử lý upload hình ảnh nếu có
	if(isset($_FILES['image']) && $_FILES['image']['name'] != ""){
		$image = $_FILES['image']['name'];
		$directory_self = str_replace(basename($_SERVER['PHP_SELF']), '', $_SERVER['PHP_SELF']);
		$uploadDirectory = $_SERVER['DOCUMENT_ROOT'] . $directory_self . "bootstrap/img/";
		$uploadDirectory .= $image;
		move_uploaded_file($_FILES['image']['tmp_name'], $uploadDirectory);
	}

	require_once("./functions/database_functions.php");
	$conn = db_connect(); // Hàm này sử dụng sqlsrv_connect()

	// Kiểm tra xem publisher có tồn tại không, nếu không thì insert mới
	$findPub = "SELECT * FROM publisher WHERE publisher_name = '$publisher'";
	//$params = array($publisher);
	$findResult = sqlsrv_query($conn, $findPub);
	// Nếu truy vấn thất bại hoặc không trả về bản ghi nào thì chèn publisher mới
	if($findResult === false || sqlsrv_fetch_array($findResult, SQLSRV_FETCH_ASSOC) === null){
		$insertPub = "INSERT INTO publisher(publisher_name) VALUES (?)";
		$insertResult = sqlsrv_query($conn, $insertPub, array($publisher));
		if($insertResult === false){
			echo "Can't add new publisher: " . print_r(sqlsrv_errors(), true);
			exit;
		}
	}

	// Xây dựng câu lệnh UPDATE cho bảng books
	$query = "UPDATE books SET  
		book_title = '$title', 
		book_author = '$author', 
		book_descr = '$descr', 
		book_price = '$price'";
	if(isset($image)){
		$query .= ", book_image='$image' WHERE book_isbn = '$isbn'";
	} else {
		$query .= " WHERE book_isbn = '$isbn'";
	}

	$result = sqlsrv_query($conn, $query);
	if($result === false){
		echo "Can't update data: " . print_r(sqlsrv_errors(), true);
		exit;
	} else {
		header("Location: admin_edit.php?bookisbn=$isbn");
	}
?>
