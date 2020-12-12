<?php

    $conn = mysqli_connect("localhost", "root", "", "ql_khutro_cairang");
    
    mysqli_set_charset($conn,"utf8");// đọc và ghi dữ liệu dạng utf8

    $data = json_decode($_POST['data'], true);

    // sql to delete a record
    $sql = "UPDATE phong 
    SET maloai='".$data["maloai"]."', 
    trang_thai='".$data["trang_thai"]."'
    WHERE stt = '".$data["stt"]."'";

    try {
        if ($conn->query($sql) === TRUE) {
            echo json_encode(array("ok" => 1));
        } else {
            echo json_encode(array("ok" => 0));
        }
    } catch(Exception $e) {
        echo json_encode(array("ok" => "ex"));
    }

    mysqli_close($conn); 
?>