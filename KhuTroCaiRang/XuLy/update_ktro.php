<?php

    $conn = mysqli_connect("localhost", "root", "", "ql_khutro_cairang");
    
    mysqli_set_charset($conn,"utf8");// đọc và ghi dữ liệu dạng utf8

    // sql to delete a record
    $sql = "UPDATE khu_tro 
    SET ten='".$_POST['ten']."', 
    so_nha='".$_POST['sonha']."',
    tenduong='".$_POST['duong']."',
    phuongxa='".$_POST['phuong']."',
    lat='".$_POST['lat']."',
    lng='".$_POST['lng']."'
    WHERE id = '".$_POST['id']."'";

    echo $sql;

    try {
        if ($conn->query($sql) === TRUE) {
            header("Location: /GDien/ql_khu_tro.php?mess=Cập nhật thành công");
            exit();
        } else {
            header("Location: /GDien/ql_khu_tro.php?mess=Cập nhật thất bại, thử lại sau");
            exit();
        }
    } catch(Exception $e) {
        header("Location: /GDien/ql_khu_tro.php?mess=Có lỗi khi xử lý, thử lại sau");
        exit();
    }

    mysqli_close($conn); 
?>