<?php

    include "./reset_auto_id.php";

    $conn = mysqli_connect("localhost", "root", "", "ql_khutro_cairang");
    
    // sql to delete a record
    $sql = "DELETE FROM phong WHERE stt='".$_POST['stt']."'";

    try {
        if ($conn->query($sql) === TRUE) {

            reset_id($conn, "phong");

            echo json_encode(array("ok" => 1));
        } else {
            echo json_encode(array("ok" => 0));
        }
    } catch(Exception $e) {
        echo json_encode(array("ok" => "ex"));
    }
    
    mysqli_close($conn); 

?>