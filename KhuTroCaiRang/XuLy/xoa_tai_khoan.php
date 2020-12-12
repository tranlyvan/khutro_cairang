<?php

    $conn = mysqli_connect("localhost", "root", "", "ql_khutro_cairang");
    

    echo "XOA ". $_GET['uname'];
    // sql to delete a record
    $sql = "DELETE FROM tai_khoan WHERE uname='".$_GET['uname']."'";

    try {
        if ($conn->query($sql) === TRUE) {
            header("Location: /GDien/ql_taikhoan.php?mess=Xóa thành công");
            exit();
        } else {
            header("Location: /GDien/ql_taikhoan.php?mess=Xóa thất bại, thử lại sau");
            exit();
        }
    } catch(Exception $e) {
        header("Location: /GDien/ql_taikhoan.php?mess=Có lỗi khi xử lý, thử lại sau");
        exit();
    }
    
    mysqli_close($conn); 

?>