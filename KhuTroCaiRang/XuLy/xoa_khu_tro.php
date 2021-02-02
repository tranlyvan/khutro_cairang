<?php

    include "./reset_auto_id.php";

    $conn = mysqli_connect("localhost", "root", "", "ql_khutro_cairang");
    

    // sql to delete a record
    $sql = "DELETE FROM khu_tro WHERE id='".$_GET['id']."'";

    try {
        if ($conn->query($sql) === TRUE) {

            reset_id($conn, "khu_tro");

            $sql = "DELETE FROM kcach WHERE id_tro='".$_GET['id']."'";

            if ($conn->query($sql) === TRUE) {

                $sql = "DELETE FROM phong WHERE id_khu_tro='".$_GET['id']."'";

                if ($conn->query($sql) === TRUE) {

                    reset_id($conn, "phong");

                    header("Location: /GDien/ql_khu_tro.php?mess=Xóa thành công");
                    exit();
                }
                else{
                    header("Location: /GDien/ql_khu_tro.php?mess=Xóa thất bại, thử lại sau");
                    exit();
                }                
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