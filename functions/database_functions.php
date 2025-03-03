<?php

// Kết nối đến SQL Server
function db_connect(){
    $serverName = "DESKTOP-T99IU4F"; // Thay đổi nếu cần (có thể có instance, ví dụ: "localhost\SQLEXPRESS")
    $connectionOptions = array(
        "Database" => "www_project",
        "UID" => "",      // Thay bằng username SQL Server của bạn
        "PWD" => "",      // Thay bằng password của bạn
        "CharacterSet" => "UTF-8",
        "TrustServerCertificate" => true
    );
    $conn = sqlsrv_connect($serverName, $connectionOptions);
    if(!$conn){
        echo "Can't connect database: ";
        die(print_r(sqlsrv_errors(), true));
    }
    
    
    return $conn;
}

/*
$serverName = "DESKTOP-T99IU4F"; // Thay đổi nếu cần (có thể có instance, ví dụ: "localhost\SQLEXPRESS")
$connectionOptions = array(
    "Database" => "www_project",
    "UID" => "",      // Thay bằng username SQL Server của bạn
    "PWD" => "",      // Thay bằng password của bạn
    "CharacterSet" => "UTF-8",
    "TrustServerCertificate" => true
);
$conn = sqlsrv_connect($serverName, $connectionOptions);
if(!$conn){
    echo "Can't connect database: ";
    die(print_r(sqlsrv_errors(), true));
}
echo 'connection success';
*/

// Lấy 4 cuốn sách mới nhất
function select4LatestBook($conn){
    $rows = array();
    $query = "SELECT book_isbn, book_image FROM books ORDER BY book_isbn DESC";
    $result = sqlsrv_query($conn, $query);
    if(!$result){
        echo "Can't retrieve data: ";
        die(print_r(sqlsrv_errors(), true));
    }
    for($i = 0; $i < 4; $i++){
        $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
        if($row === null)
            break;
        array_push($rows, $row);
    }
    return $rows;
}

// Lấy thông tin sách theo ISBN
function getBookByIsbn($conn, $isbn){
    $query = "SELECT book_title, book_author, book_price FROM books WHERE book_isbn = ?";
    $params = array($isbn);
    $result = sqlsrv_query($conn, $query, $params);
    if(!$result){
        echo "Can't retrieve data: ";
        die(print_r(sqlsrv_errors(), true));
    }
    return $result;
}

// Lấy orderid dựa vào customerid
function getOrderId($conn, $customerid){
    $query = "SELECT orderid FROM orders WHERE customerid = ?";
    $params = array($customerid);
    $result = sqlsrv_query($conn, $query, $params);
    if(!$result){
        echo "Retrieve data failed: ";
        die(print_r(sqlsrv_errors(), true));
    }
    $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
    return $row['orderid'];
}

// Chèn dữ liệu vào bảng orders
function insertIntoOrder($conn, $customerid, $total_price, $date, $ship_name, $ship_address, $ship_city, $ship_zip_code, $ship_country){
    // Giả sử bảng orders có các cột: orderid (IDENTITY), customerid, total_price, [date], ship_name, ship_address, ship_city, ship_zip_code, ship_country
    $query = "INSERT INTO orders (customerid, total_price, [date], ship_name, ship_address, ship_city, ship_zip_code, ship_country)
              VALUES (?,?,?,?,?,?,?,?)";
    $params = array($customerid, $total_price, $date, $ship_name, $ship_address, $ship_city, $ship_zip_code, $ship_country);
    $result = sqlsrv_query($conn, $query, $params);
    if(!$result){
        echo "Insert orders failed: ";
        die(print_r(sqlsrv_errors(), true));
    }
}

// Lấy giá sách theo ISBN
function getbookprice($isbn){
    $conn = db_connect();
    $query = "SELECT book_price FROM books WHERE book_isbn = ?";
    $params = array($isbn);
    $result = sqlsrv_query($conn, $query, $params);
    if(!$result){
        echo "Get book price failed: ";
        die(print_r(sqlsrv_errors(), true));
    }
    $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
    return $row['book_price'];
}

// Lấy customerid dựa vào thông tin khách hàng
function getCustomerId($name, $address, $city, $zip_code, $country){
    $conn = db_connect();
    $query = "SELECT customerid FROM customers WHERE name = ? AND address = ? AND city = ? AND zip_code = ? AND country = ?";
    $params = array($name, $address, $city, $zip_code, $country);
    $result = sqlsrv_query($conn, $query, $params);
    if($result){
        $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
        return $row ? $row['customerid'] : null;
    } else {
        return null;
    }
}

// Chèn khách hàng mới và trả về customerid (giả sử cột customerid là IDENTITY)
function setCustomerId($name, $address, $city, $zip_code, $country){
    $conn = db_connect();
    $query = "INSERT INTO customers (name, address, city, zip_code, country) VALUES (?,?,?,?,?)";
    $params = array($name, $address, $city, $zip_code, $country);
    $result = sqlsrv_query($conn, $query, $params);
    if(!$result){
        echo "Insert customer failed: ";
        die(print_r(sqlsrv_errors(), true));
    }
    // Lấy ID vừa chèn
    $query2 = "SELECT SCOPE_IDENTITY() AS customerid";
    $result2 = sqlsrv_query($conn, $query2);
    if(!$result2){
        die(print_r(sqlsrv_errors(), true));
    }
    $row = sqlsrv_fetch_array($result2, SQLSRV_FETCH_ASSOC);
    return $row['customerid'];
}

// Lấy tên nhà xuất bản dựa vào pubid
function getPubName($conn, $pubid){
    $query = "SELECT publisher_name FROM publisher WHERE publisherid = ?";
    $params = array($pubid);
    $result = sqlsrv_query($conn, $query, $params);
    if(!$result){
        echo "Can't retrieve data: ";
        die(print_r(sqlsrv_errors(), true));
    }
    $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
    if(!$row){
        echo "Empty publisher! Something wrong! Check again.";
        exit;
    }
    return $row['publisher_name'];
}

// Lấy tất cả sách
function getAll($conn){
    $query = "SELECT * FROM books ORDER BY book_isbn DESC";
    $result = sqlsrv_query($conn, $query);
    if(!$result){
        echo "Can't retrieve data: ";
        die(print_r(sqlsrv_errors(), true));
    }
    return $result;
}
?>
