<?php	
	session_start();

	$_SESSION['err'] = 1;
	foreach($_POST as $key => $value){
		if(trim($value) == ''){
			$_SESSION['err'] = 0;
		}
		break;
	}

	if($_SESSION['err'] == 0){
		header("Location: purchase.php");
		exit;
	} else {
		unset($_SESSION['err']);
	}

	require_once "./functions/database_functions.php";
	// In header
	$title = "Purchase Process";
	require "./template/header.php";

	// Kết nối CSDL SQL Server
	$conn = db_connect();
	// Giả sử $_SESSION['ship'] chứa các thông tin ship: name, address, city, zip_code, country
	extract($_SESSION['ship']);

	// Validate phần dữ liệu thẻ
	$card_number = $_POST['card_number'];
	$card_PID = $_POST['card_PID'];
	$card_expire = strtotime($_POST['card_expire']);
	$card_owner = $_POST['card_owner'];

	// Tìm customer theo thông tin giao hàng
	$customerid = getCustomerId($name, $address, $city, $zip_code, $country);
	if($customerid == null) {
		// Nếu không có, insert customer và lấy customerid
		$customerid = setCustomerId($name, $address, $city, $zip_code, $country);
	}

	$date = date("Y-m-d H:i:s");
	// Chèn đơn hàng (order) vào bảng orders
	insertIntoOrder($conn, $customerid, $_SESSION['total_price'], $date, $name, $address, $city, $zip_code, $country);

	// Lấy orderid vừa được insert (giả sử hàm getOrderId đã chuyển sang sqlsrv)
	$orderid = getOrderId($conn, $customerid);

	// Duyệt qua giỏ hàng để chèn dữ liệu vào bảng order_items
	foreach($_SESSION['cart'] as $isbn => $qty){
		$bookprice = getbookprice($isbn); // Hàm getbookprice() sử dụng sqlsrv
		$query = "INSERT INTO order_items (orderid, book_isbn, item_price, quantity) VALUES (?, ?, ?, ?)";
		$params = array($orderid, $isbn, $bookprice, $qty);
		$result = sqlsrv_query($conn, $query, $params);
		if($result === false){
			echo "Insert value false! " . print_r(sqlsrv_errors(), true);
			exit;
		}
	}

	// Xóa session
	session_unset();
?>
	<p class="lead text-success">
		Your order has been processed successfully. Please check your email to get your order confirmation and shipping detail!. 
		Your cart has been emptied.
	</p>

<?php
	if(isset($conn)){
		sqlsrv_close($conn);
	}
	require_once "./template/footer.php";
?>
