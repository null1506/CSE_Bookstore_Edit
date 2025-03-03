<?php
	// the shopping cart needs sessions, to start one
	/*
		Array of session(
			cart => array (
				book_isbn (get from $_GET['book_isbn']) => number of books
			),
			items => 0,
			total_price => '0.00'
		)
	*/
	session_start();
	require_once "./functions/database_functions.php";
	require_once "./functions/cart_functions.php";

	// print out header here
	$title = "Checking out";
	require "./template/header.php";

	// Kiểm tra giỏ hàng có tồn tại và có ít nhất 1 mục không
	if(isset($_SESSION['cart']) && count($_SESSION['cart']) > 0){
?>
	<table class="table">
		<tr>
			<th>Item</th>
			<th>Price</th>
	    	<th>Quantity</th>
	    	<th>Total</th>
	    </tr>
	    <?php
			// Mở kết nối SQL Server một lần trước vòng lặp
			$conn = db_connect();
			// Duyệt qua từng sản phẩm trong giỏ hàng
			foreach($_SESSION['cart'] as $isbn => $qty){
				// Hàm getBookByIsbn() cần trả về kết quả từ sqlsrv_query()
				$result = getBookByIsbn($conn, $isbn);
				$book = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
	    ?>
		<tr>
			<td><?php echo $book['book_title'] . " by " . $book['book_author']; ?></td>
			<td><?php echo "$" . $book['book_price']; ?></td>
			<td><?php echo $qty; ?></td>
			<td><?php echo "$" . ($qty * $book['book_price']); ?></td>
		</tr>
		<?php } ?>
		<tr>
			<th>&nbsp;</th>
			<th>&nbsp;</th>
			<th><?php echo $_SESSION['total_items']; ?></th>
			<th><?php echo "$" . $_SESSION['total_price']; ?></th>
		</tr>
	</table>
	<form method="post" action="purchase.php" class="form-horizontal">
		<?php if(isset($_SESSION['err']) && $_SESSION['err'] == 1){ ?>
			<p class="text-danger">All fields have to be filled</p>
		<?php } ?>
		<div class="form-group">
			<label for="name" class="control-label col-md-4">Name</label>
			<div class="col-md-4">
				<input type="text" name="name" class="form-control">
			</div>
		</div>
		<div class="form-group">
			<label for="address" class="control-label col-md-4">Address</label>
			<div class="col-md-4">
				<input type="text" name="address" class="form-control">
			</div>
		</div>
		<div class="form-group">
			<label for="city" class="control-label col-md-4">City</label>
			<div class="col-md-4">
				<input type="text" name="city" class="form-control">
			</div>
		</div>
		<div class="form-group">
			<label for="zip_code" class="control-label col-md-4">Zip Code</label>
			<div class="col-md-4">
				<input type="text" name="zip_code" class="form-control">
			</div>
		</div>
		<div class="form-group">
			<label for="country" class="control-label col-md-4">Country</label>
			<div class="col-md-4">
				<input type="text" name="country" class="form-control">
			</div>
		</div>
		<div class="form-group">
			<input type="submit" name="submit" value="Purchase" class="btn btn-primary">
		</div>
	</form>
	<p class="lead">Please press Purchase to confirm your purchase, or Continue Shopping to add or remove items.</p>
<?php
	} else {
		echo "<p class=\"text-warning\">Your cart is empty! Please make sure you add some books in it!</p>";
	}
	// Đóng kết nối nếu đã mở
	if(isset($conn)){
		sqlsrv_close($conn);
	}
	require_once "./template/footer.php";
?>
