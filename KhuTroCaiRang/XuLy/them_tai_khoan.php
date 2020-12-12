<?php

    $conn = mysqli_connect("localhost", "root", "", "ql_khutro_cairang");

    $uname = $_POST['uname'];
    $hoten = $_POST['hoten'];
    $options = [
        'cost' => 12,
    ];
    $pass_hash = password_hash($_POST['pass'], PASSWORD_BCRYPT, $options);
    
    mysqli_set_charset($conn,"utf8");// đọc và ghi dữ liệu dạng utf8

    // sql to delete a record
    $sql = "INSERT INTO `tai_khoan` (`uname`, `hoten`, `pass`) 
    VALUES ('".$uname."', '".$hoten."', '".$pass_hash."');";

    try {
        if ($conn->query($sql) === TRUE) {
            header("Location: /GDien/ql_taikhoan.php?mess=Thêm thành công");
            exit();
        } else {
            header("Location: /GDien/ql_taikhoan.php?mess=Thêm thất bại, thử lại sau");
            exit();
        }
    } catch(Exception $e) {
        header("Location: /GDien/ql_taikhoan.php?mess=Có lỗi khi xử lý, thử lại sau");
        exit();
    }

    mysqli_close($conn);
?>