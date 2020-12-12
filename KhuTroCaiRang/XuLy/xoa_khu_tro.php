<?php

    $conn = mysqli_connect("localhost", "root", "", "ql_khutro_cairang");
    

    // sql to delete a record
    $sql = "DELETE FROM khu_tro WHERE id='".$_GET['id']."'";

    try {
        if ($conn->query($sql) === TRUE) {

            $sql = "DELETE FROM kcach WHERE id_tro='".$_GET['id']."'";

            if ($conn->query($sql) === TRUE) {
                header("Location: /GDien/ql_khu_tro.php?mess=Xóa thành công");
                exit();
            }
            else {
                header("Location: /GDien/ql_khu_tro.php?mess=Xóa thất bại, thử lại sau");
                exit();
            }
        } else {
            header("Location: /GDien/ql_khu_tro.php?mess=Xóa thất bại, thử lại sau");
            exit();
        }
    } catch(Exception $e) {
        header("Location: /GDien/ql_khu_tro.php?mess=Có lỗi khi xử lý, thử lại sau");
        exit();
    }

    mysqli_close($conn); 
    

?>