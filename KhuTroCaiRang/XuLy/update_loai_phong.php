<?php

    $conn = mysqli_connect("localhost", "root", "", "ql_khutro_cairang");
    
    mysqli_set_charset($conn,"utf8");// đọc và ghi dữ liệu dạng utf8

    $gia = str_replace(",", "", $_POST['gia']);

    // sql to delete a record
    $sql = "UPDATE loai_phong 
    SET ten_loai='".$_POST['ten_loai']."', 
    so_nguoi='".$_POST['so_nguoi']."',
    dien_tich='".$_POST['dien_tich']."',
    gia='".$gia."'
    WHERE maloai = '".$_POST['maloai']."'";

    echo $sql;

    try {
        if ($conn->query($sql) === TRUE) {
            header("Location: /GDien/ql_loai_phong.php?mess=Cập nhật thành công");
            exit();
        } else {
            header("Location: /GDien/ql_loai_phong.php?mess=Cập nhật thất bại, thử lại sau");
            exit();
        }
    } catch(Exception $e) {
        header("Location: /GDien/ql_loai_phong.php?mess=Có lỗi khi xử lý, thử lại sau");
        exit();
    }

    mysqli_close($conn); 
?>