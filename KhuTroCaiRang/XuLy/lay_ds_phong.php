<?php
    
    $conn = mysqli_connect("localhost", "root", "", "ql_khutro_cairang");
    mysqli_set_charset($conn,"utf8");// đọc và ghi dữ liệu dạng utf8

    // Tạo màu select theo trạng thái.
    // function make_trthai_color($trang_thai)
    // {
    //     if ($trang_thai == "0") return "#99ffcc";
    //     if ($trang_thai == "1") return "#33adff";
    //     if ($trang_thai == "2") return "#666633";
    // }

    // Tạo chuối html phần select option cho giá trị loại phòng.
    function taoOptionLoaiPhong($stt, $maloai)
    {
        $html_text =  '<select class="form-control" id="slL_'.$stt.'">
            <option value="cb"'.(($maloai == "cb") ? ' selected' : '').'>Cơ Bản</option>
            <option value="tn"'.(($maloai == "tn") ? ' selected' : '').'>Tiện Nghi</option>
            <option value="cc"'.(($maloai == "cc") ? ' selected' : '').'>Cao Cấp</option>
            <option value="mn"'.(($maloai == "mn") ? ' selected' : '').'>Mini House</option>
        </select>';

        return $html_text;
    }

    // Tạo chuối html phần select option cho giá trị trạng thái phòng.
    function taoOptionTrThai($stt, $trang_thai)
    {
        $html_text =  '<select class="form-control" id="slT_'.$stt.'">
            <option value="0"'.(($trang_thai == "0") ? ' selected' : '').'>Còn Trống</option>
            <option value="1"'.(($trang_thai == "1") ? ' selected' : '').'>Đã đặt cọc</option>
            <option value="2"'.(($trang_thai == "2") ? ' selected' : '').'>Đã thuê</option>
        </select>';

        return $html_text;
    }

    // sql to delete a record
    $query = "SELECT p.stt, l.maloai, p.trang_thai 
    FROM phong p, loai_phong l 
    WHERE p.id_khu_tro='".$_POST["id"]."' AND l.maloai = p.maloai";

    $result = mysqli_query($conn, $query);

    $ds_phong = null;
    while ($row = mysqli_fetch_array($result)) {
        $ds_phong[] = array(
            "stt" => $row["stt"],
            "ten_loai" => taoOptionLoaiPhong($row["stt"], $row["maloai"]),
            "trang_thai" => taoOptionTrThai($row["stt"], $row["trang_thai"])
        );
    }

    echo json_encode($ds_phong);


    mysqli_close($conn); 

?>