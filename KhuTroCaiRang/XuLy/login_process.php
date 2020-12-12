<?php

// Start the session
session_start();

// $options = [
//     'cost' => 12,
// ];
// echo password_hash("van", PASSWORD_BCRYPT, $options);

$conn = mysqli_connect("localhost", "root", "", "ql_khutro_cairang");

if (!$conn) {//loi ket noi csdl
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
    exit;
}
else{//ket noi den csdl
    mysqli_set_charset($conn,"utf8");// đọc và ghi dữ liệu dạng utf8

    $query = "SELECT pass, hoten FROM tai_khoan WHERE uname = '".$_POST['uname']."'";

    $result = mysqli_query($conn, $query);

    $count = mysqli_num_rows($result);

    if ($count > 0) {
        while ($row = mysqli_fetch_array($result)) {

            $hash = $row[0];
        
            if (password_verify($_POST['pass'], $hash)) {
                $_SESSION["uname"] = $_POST['uname'];
                $_SESSION["uhoten"] = $row[1];

                header("Location: /");
                exit();
            } else {
                header("Location: /GDien/Login.php?mess=Sai mật khẩu");
                exit();
            }
        }
    } else {
        header("Location: /?mess=Người dùng không tồn tại");
        exit();
    }

    mysqli_close($conn); 
    
}

?>