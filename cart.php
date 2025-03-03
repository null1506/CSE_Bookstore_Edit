<?php
    session_start();
    require_once "./functions/database_functions.php";
    require_once "./functions/cart_functions.php";

    // Nếu có book_isbn được gửi qua POST, thêm vào giỏ hàng
    if(isset($_POST['bookisbn'])){
        $book_isbn = $_POST['bookisbn'];
    }
    if(isset($book_isbn)){
        if(!isset($_SESSION['cart'])){
            // Khởi tạo giỏ hàng dưới dạng mảng: bookisbn => số lượng
            $_SESSION['cart'] = array();
            $_SESSION['total_items'] = 0;
            $_SESSION['total_price'] = '0.00';
        }
        if(!isset($_SESSION['cart'][$book_isbn])){
            $_SESSION['cart'][$book_isbn] = 1;
        } elseif(isset($_POST['cart'])){
            $_SESSION['cart'][$book_isbn]++;
            unset($_POST);
        }
    }

    // Nếu người dùng bấm nút "Save Changes", cập nhật số lượng cho từng sản phẩm
    if(isset($_POST['save_change'])){
        foreach($_SESSION['cart'] as $isbn => $qty){
            if($_POST[$isbn] == '0'){
                unset($_SESSION['cart'][$isbn]);
            } else {
                $_SESSION['cart'][$isbn] = $_POST[$isbn];
            }
        }
    }

    $title = "Your shopping cart";
    require "./template/header.php";

    if(isset($_SESSION['cart']) && count($_SESSION['cart']) > 0){
        $_SESSION['total_price'] = total_price($_SESSION['cart']);
        $_SESSION['total_items'] = total_items($_SESSION['cart']);
        
        // Mở kết nối SQL Server một lần để sử dụng cho các truy vấn trong giỏ hàng
        $conn = db_connect();
?>
    <form action="cart.php" method="post">
        <table class="table">
            <tr>
                <th>Item</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
            </tr>
            <?php
                // Lặp qua từng sản phẩm trong giỏ hàng
                foreach($_SESSION['cart'] as $isbn => $qty){
                    // Hàm getBookByIsbn() trong database_functions.php nên được chuyển sang dùng sqlsrv_query()
                    $result = getBookByIsbn($conn, $isbn);
                    $book = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
            ?>
            <tr>
                <td><?php echo $book['book_title'] . " by " . $book['book_author']; ?></td>
                <td><?php echo "$" . $book['book_price']; ?></td>
                <td><input type="text" value="<?php echo $qty; ?>" size="2" name="<?php echo $isbn; ?>"></td>
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
        <input type="submit" class="btn btn-primary" name="save_change" value="Save Changes">
    </form>
    <br/><br/>
    <a href="checkout.php" class="btn btn-primary">Go To Checkout</a> 
    <a href="books.php" class="btn btn-primary">Continue Shopping</a>
<?php
        // Đóng kết nối SQL Server sau khi sử dụng
        sqlsrv_close($conn);
    } else {
        echo "<p class=\"text-warning\">Your cart is empty! Please make sure you add some books in it!</p>";
    }
    require_once "./template/footer.php";
?>
