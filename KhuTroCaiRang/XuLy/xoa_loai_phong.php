<?php

    $conn = mysqli_connect("localhost", "root", "", "ql_khutro_cairang");
    

    // sql to delete a record
    $sql = "DELETE FROM loai_phong WHERE maloai='".$_GET['maloai']."'";

    try {
        if ($conn->query($sql) === TRUE) {

            $sql = "DELETE FROM phong WHERE maloai='".$_GET['maloai']."'";


            if ($conn->query($sql) === TRUE) {

                reset_id($conn, "phong");

                header("Location: /GDien/ql_loai_phong.php?mess=Xóa thành công");
                exit();
            }
            else{
                header("Location: /GDien/ql_loai_phong.php?mess=Xóa thất bại, thử lại sau");
                exit();
            }

        } else {
            header("Location: /GDien/ql_loai_phong.php?mess=Xóa thất bại, thử lại sau");
            exit();
        }
    } catch(Exception $e) {
        header("Location: /GDien/ql_loai_phong.php?mess=Có lỗi khi xử lý, thử lại sau");
        exit();
    }
    
    mysqli_close($conn); 

?>