<?php

    $conn = mysqli_connect("localhost", "root", "", "ql_khutro_cairang");
    mysqli_set_charset($conn,"utf8");// đọc và ghi dữ liệu dạng utf8

    // sql to delete a record
    $query = "SELECT * 
    FROM chu_tro
    WHERE chu_tro.cmnd ='".$_POST["cmnd"]."'";

    $result = mysqli_query($conn, $query);

    $chu_tro = null;
    while ($row = mysqli_fetch_array($result)) {
        $chu_tro[] = array(
            "hoten" => $row["hoten"],
            "sdt" => $row["sdt"],
            "gioitinh" => $row["gioitinh"],
        );
    }

    echo json_encode($chu_tro);
    mysqli_close($conn); 
?>