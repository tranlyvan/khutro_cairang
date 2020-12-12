<?php

    $conn = mysqli_connect("localhost", "root", "", "ql_khutro_cairang");
    
    mysqli_set_charset($conn,"utf8");// đọc và ghi dữ liệu dạng utf8
    try {

        if ($_POST['new_admin'] == "no"){
            // sql to delete a record
            $sql = "INSERT INTO khu_tro(ten,so_nha,tenduong,phuongxa,quanhuyen,tinh,lat,lng,cmnd) 
            VALUES ('".$_POST['ten']."','".$_POST['so_nha']."','".$_POST['tenduong']."','".$_POST['phuongxa']."'
            ,'".$_POST['quanhuyen']."','".$_POST['tinh']."','".$_POST['lat']."','".$_POST['lng']."','".$_POST['cmnd']."')";

            $kq = ($conn->query($sql));
        }
        else{
            $sql = "INSERT INTO chu_tro(cmnd, hoten, sdt, gioitinh) VALUES ('".$_POST['cmnd']."','".$_POST['hoten']."','".$_POST['sdt']."','".$_POST['gioitinh']."')";

            if ($conn->query($sql) === TRUE) {
                $sql = "INSERT INTO khu_tro(ten,so_nha,tenduong,phuongxa,quanhuyen,tinh,lat,lng,cmnd) 
                VALUES ('".$_POST['ten']."','".$_POST['so_nha']."','".$_POST['tenduong']."','".$_POST['phuongxa']."'
                ,'".$_POST['quanhuyen']."','".$_POST['tinh']."','".$_POST['lat']."','".$_POST['lng']."','".$_POST['cmnd']."');";

                $kq = ($conn->query($sql));
            }
            else{
                header("Location: /GDien/ql_khu_tro.php?mess=Thêm thất bại, thử lại sau");
                exit();
            }
        }

        if ($kq === TRUE) {

            $ktro_id = $conn->insert_id;

            // Đọc danh sách trường.
            $query = "SELECT lat, lng, id FROM truong";
            $result = mysqli_query($conn, $query);

            $in_txt = "";

            while ($row = mysqli_fetch_array($result)) {

                $get_url = 'https://api.mapbox.com/directions/v5/mapbox/driving/'.$_POST["lng"].','.$_POST['lat'].';'.$row["lng"].','.$row["lat"].'?steps=true&geometries=geojson&access_token=pk.eyJ1IjoibHl2YW1heCIsImEiOiJja2djN3JxOGYwbzNoMnJydnU5amJiMXBxIn0.-aX9gOgQhcCtpaf79ufgUw';
                $json = file_get_contents($get_url); // this WILL do an http request for you

                $data = json_decode($json);

                $kcach = $data->routes[0]->distance/1000;

                $sql = "INSERT INTO kcach(id_tro, id_truong, kcach) VALUES ( '".$ktro_id."', '".$row["id"]."', '".$kcach."');";
                $in_txt .= $sql;
            }

            if ($conn->multi_query($in_txt) === TRUE) {
                header("Location: /GDien/ql_khu_tro.php?mess=Thêm thành công.");
                exit();
            } else {
                header("Location: /GDien/ql_khu_tro.php?mess=Thêm thất bại, thử lại sau");
                exit();
            }

            
        } else {
            header("Location: /GDien/ql_khu_tro.php?mess=Thêm thất bại, thử lại sau");
            exit();
        }
    } catch(Exception $e) {
        header("Location: /GDien/ql_khu_tro.php?mess=Có lỗi khi xử lý, thử lại sau");
        exit();
    }

    mysqli_close($conn); 
?>