<?php

    function insert_polygon($insert_text)
    {

        $conn = mysqli_connect("localhost", "root", "", "ql_khutro_cairang");

        if (!$conn) { //loi ket noi csdl
            echo "Error: Unable to connect to MySQL." . PHP_EOL;
            echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
            echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
            exit;
        } else { //ket noi den csdl
            mysqli_set_charset($conn,"utf8");// đọc và ghi dữ liệu dạng utf8
            mysqli_set_charset($conn, "utf8_general_ci"); // đọc và ghi dữ liệu dạng utf8

            if ($conn->query($insert_text) === TRUE) {
            echo "New record created successfully";
            } else {
            echo "Error: " . $insert_text . "<br>" . $conn->error;
            }

            $conn->close();
        }
    }



    $insert_text = "INSERT INTO `diagioihanhchinh` (`IDPhuong`, `DiaGioi`, `TenPhuong`) VALUES (0, ST_GeomFromText('POLYGON((";
    $current_shape_id = 0;

    // Danh sách theo thứ tự tên các phường trong file csv atrributes xuất ra từ QGis.
    $ten = ["Ba Láng", "Hưng Phú", "Hưng Thạnh", "Lê Bình", "Phú Thứ", "Tân Phú", "Thường Thạnh"];

    // Thay cairang1.csv thành file csv chứa dữ liệu địa giới (nodes) xuất ra từ QGis.
    if (($handle = fopen('cairang_nodes.csv', 'r')) !== FALSE) { // Check the resource is valid
        $data = fgetcsv($handle, 1000, ",");
        $i = 0;
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {

            if ($data[0] == $current_shape_id) {
                if ($i == 0) {
                    $insert_text.= ($data[2]." ".$data[3]);
                }
                else {
                    $insert_text.= (",".$data[2]." ".$data[3]);
                }
                $i++;
            }
            else {
                $i = 1;
                $insert_text.= "))'), '".$ten[$current_shape_id]."')";
                $current_shape_id = $data[0];

                insert_polygon($insert_text);

                $insert_text = "INSERT INTO `diagioihanhchinh` (`IDPhuong`, `DiaGioi`, `TenPhuong`) VALUES (".($current_shape_id + 1).", ST_GeomFromText('POLYGON((".$data[2]." ".$data[3];
            }
        }
        $insert_text.= "))'), '".$ten[$current_shape_id]."')";
        insert_polygon($insert_text);
    }
?>
