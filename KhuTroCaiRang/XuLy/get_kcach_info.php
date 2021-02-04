<?php

    $conn = mysqli_connect("localhost", "root", "", "ql_khutro_cairang");
    mysqli_set_charset($conn,"utf8");// đọc và ghi dữ liệu dạng utf8

    // sql to delete a record
    $query = "SELECT t.ten, kc.kcach 
    FROM khu_tro k, kcach kc, truong t 
    WHERE k.id = '".$_POST['id']."' AND k.id = kc.id_tro AND t.id = kc.id_truong
    ORDER BY kcach ASC";

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