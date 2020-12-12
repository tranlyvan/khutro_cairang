<?php

    // Start the session
    session_start();
    include "./check_session.php";

    include "./head.php";
    include "./Menu.php";

    $conn = mysqli_connect("localhost", "root", "", "ql_khutro_cairang");

    if (!$conn) {//loi ket noi csdl
    	echo "Error: Unable to connect to MySQL." . PHP_EOL;
    	echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    	echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
    	exit;
    }
    else{//ket noi den csdl
        mysqli_set_charset($conn,"utf8");// đọc và ghi dữ liệu dạng utf8

        $query = "SELECT * FROM loai_phong";

        $result = mysqli_query($conn, $query);

        $ds_loai_phong = [];
        while ($row = mysqli_fetch_array($result)) {
            $ds_loai_phong[] = [$row["maloai"], $row["ten_loai"], $row["so_nguoi"], $row["dien_tich"], $row["gia"]];
        }

        mysqli_close($conn); 
    }
?>

<div class="container-fluid"><br>
    
    <!-- Modal -->
    <div class="modal fade" id="md_update" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Cập nhật loại phòng</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">

                    <form class="form-group" id="f_update" method="POST" action="/XuLy/update_loai_phong.php">
                        <label>Mã loại</label>
                        <input type="text" class="form-control" id="txt_maloai" name="maloai" readonly>
                    
                        <label>Tên Loại</label>
                        <input type="text" class="form-control" name="ten_loai" id="txt_ten_loai" required>

                        <label>Số người tối đa (người)</label>
                        <input type="number" class="form-control" name="so_nguoi" id="txt_so_nguoi" min="1" > 

                        <label>Diện tích (m2)</label>
                        <input type="number" class="form-control" step="0.1" name="dien_tich" id="txt_dien_tich" min="20"> 

                        <label>Giá (VND)</label>
                        <input type="text" onkeyup="oneDot(this)" class="form-control" name="gia" id="txt_gia" min="500000"> 
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    <button onclick="$('#f_update').submit();" type="button" class="btn btn-primary">Lưu</button>
                </div>
            </div>
        </div>
    </div>

<script>
    function show_md_update(maloai, ten_loai, so_nguoi, dien_tich, gia) {
        $("#txt_maloai").val(maloai);
        $("#txt_ten_loai").val(ten_loai);
        $("#txt_so_nguoi").val(so_nguoi);
        $("#txt_dien_tich").val(dien_tich);
        $("#txt_gia").val(gia.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,"));
    }

    function oneDot(input) {
        V = input.value.replaceAll(",", "");
        V = V.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
        input.value = V;
    }

    $(document).ready(function() {
        $('#tb_data').DataTable();

        $(".alert").fadeTo(2000, 500).slideUp(500, function(){
            $(".alert").slideUp(500);
        });
    });
</script>


    <h2>Danh sách loại phòng</h2><hr>
    
    <?php
        $mess  = $_GET['mess'];

        if ($mess != NULL) {
            echo '
            <div class="alert alert-success alert-dismissible fade show">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                '.$mess.'
            </div>
            ';
        }
    ?>
    <h6><b>Thêm loại phòng mới</b></h6><br>
    <form class="form row" method="POST" action="/XuLy/them_loai_phong.php">

        <div class="col-2">
            <label>Mã loại</label>
            <input type="text" class="form-control" name="maloai" required>
        </div>
        
        <div class="col-2">
            <label>Tên Loại</label>
            <input type="text" class="form-control" name="ten_loai" required >
        </div>

        <div class="col-2">
            <label>Số người tối đa</label>
            <input type="number" class="form-control" name="so_nguoi" min="1" value="1"> 
        </div>

        <div class="col-2">
            <label>Diện tích</label>
            <input type="number" class="form-control" name="dien_tich" step="0.1" min="20" value="20"> 
        </div>

        <div class="col-2">
            <label>Giá</label>
            <input type="text" onkeyup="oneDot(this)" class="form-control" name="gia" value="500,000" data-type="currency">
        </div>

        <div class="col-12 row">
            <button type="submit" class="btn btn-success form-control col-2 mt-3 ml-3">Thêm</button>
        </div>
    </form>


    <hr><table id="tb_data" class="table table-hover table-bordered" style="width:100%">
    <h6><i>Click lên tên cột nếu cần sắp xếp</i></h6><br>
    <thead class="table-secondary">
        <tr>
            <th>Mã loại</th>
            <th>Tên loại</th>
            <th>Số người tối đa</th>
            <th>Diện tích</th>
            <th>Giá</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php 
            foreach ($ds_loai_phong as $i => $p) {
                echo '<tr>
                        <td>'.$p[0].'</td>
                        <td>'.$p[1].'</td>
                        <td>'.$p[2].'</td>
                        <td>'.$p[3].' (m<sup>2</sup>)</td>
                        <td>'.number_format($p[4]).' (VND)</td>
                        <td>
                            <button onclick="show_md_update(\''.$p[0].'\', \''.$p[1].'\', \''.$p[2].'\', \''.$p[3].'\', \''.$p[4].'\')" data-toggle="modal" data-target="#md_update" class="btn btn-warning">Sửa</button>
                            <a href="/XuLy/xoa_loai_phong.php?maloai='.$p[0].'"
                                onclick="var r = confirm(\'Xóa loại phòng '.$p[1].'\');
                                if (r == false) {
                                    return false;
                                }" class="btn btn-danger">
                                Xóa
                            </a>
                        </td>
                    </tr>
                ';
            }
        ?>
            
    </table>
</div>

</body>
</html>