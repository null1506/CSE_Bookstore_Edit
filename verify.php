<?php
    $email = $_POST['inputEmail'];
    $pswd = $_POST['inputPasswd'];

    // Cấu hình kết nối đến SQL Server
    $serverName = "localhost"; // Nếu cần instance, ví dụ: "localhost\\SQLEXPRESS"
    $connectionOptions = array(
        "Database" => "www_project",
        "UID" => "your_username",      // Thay bằng username của SQL Server
        "PWD" => "your_password",      // Thay bằng password của SQL Server
        "CharacterSet" => "UTF-8"
    );
    
    // Kết nối đến SQL Server
    $conn = sqlsrv_connect($serverName, $connectionOptions);
    if(!$conn){
        echo "Cannot connect to database: " . print_r(sqlsrv_errors(), true);
        exit;
    }

    $query = "SELECT username, password FROM admin";
    $result = sqlsrv_query($conn, $query);
    if($result === false){
        echo "Empty!";
        exit;
    }

    while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
        if($email == $row['username'] && $pswd == $row['password']){
            echo "Welcome admin! Long time no see";
            break;
        }
    }
?>
