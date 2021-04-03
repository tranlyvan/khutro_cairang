<?php

    $conn = mysqli_connect("localhost", "root", "", "ql_khutro_cairang");
    mysqli_set_charset($conn,"utf8");// đọc và ghi dữ liệu dạng utf8

    // sql to delete a record
    $query = "SELECT id_khu_tro, maloai, COUNT(*) AS soluong 
    FROM phong WHERE trang_thai = "0" GROUP BY id_khu_tro, maloai";

    $result = mysqli_query($conn, $query);

    $ds_kcach = null;
    while ($row = mysqli_fetch_array($result)) {
        $ds_kcach[] = array(
            "ten" => $row["ten"],
            "kcach" => $row["kcach"]
        );
    }

    echo json_encode($ds_kcach);

    mysqli_close($conn); 
?>