<?php

    $conn = mysqli_connect("localhost", "root", "", "ql_khutro_cairang");

    $options = [
        'cost' => 12,
    ];
    $pass_hash = password_hash($_POST['pass'], PASSWORD_BCRYPT, $options);
    
    mysqli_set_charset($conn,"utf8");// đọc và ghi dữ liệu dạng utf8

    // sql to delete a record
    $sql = "UPDATE tai_khoan SET hoten='".$_POST['hoten']."', pass='".$pass_hash."' WHERE uname = '".$_POST['uname']."'";

    try {
        if ($conn->query($sql) === TRUE) {
            header("Location: /GDien/ql_taikhoan.php?mess=Cập nhật thành công");
            exit();
        } else {
            header("Location: /GDien/ql_taikhoan.php?mess=Cập nhật thất bại, thử lại sau");
            exit();
        }
    } catch(Exception $e) {
        header("Location: /GDien/ql_taikhoan.php?mess=Có lỗi khi xử lý, thử lại sau");
        exit();
    }

    mysqli_close($conn); 
?>