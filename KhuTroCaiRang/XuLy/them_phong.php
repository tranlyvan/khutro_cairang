<?php

    $conn = mysqli_connect("localhost", "root", "", "ql_khutro_cairang");
    
    // echo json_encode($_POST['trang_thai']);

    // sql to delete a record
    $sql = "INSERT INTO phong(maloai, id_khu_tro, trang_thai) VALUES ('".$_POST["maloai"]."' ,'".$_POST["id_khu_tro"]."' ,'".$_POST["trang_thai"]."' )";

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