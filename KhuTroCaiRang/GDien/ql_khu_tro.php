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

        // Đọc thông tin khu trọ và chủ trọ tương ứng.
        $query = 'SELECT ten, so_nha, tenduong, phuongxa, quanhuyen, tinh, c.hoten, c.sdt, k.id, k.lat, k.lng
        FROM khu_tro k, chu_tro c 
        WHERE k.cmnd = c.cmnd';

        $result = mysqli_query($conn, $query);

        $ds_khu_tro = [];
        while ($row = mysqli_fetch_array($result)) {
            $ds_khu_tro[] = [
                $row["ten"], 
                $row["so_nha"],
                $row["tenduong"],
                $row["phuongxa"],
                $row["quanhuyen"],
                $row["tinh"], 
                $row["hoten"], 
                $row["sdt"],
                $row["id"],
                $row["lat"],
                $row["lng"]
            ];
        }
        

        mysqli_close($conn); 
    }
?>

<div class="container-fluid"><br>
    
    <!-- Modal update -->
    <div class="modal fade" id="md_update" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Cập nhật thông tin khu trọ</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">

                    <form class="form-group" id="f_update" method="POST" action="/XuLy/update_ktro.php">
                        <input type="hidden" id="txt_id" class="form-control" name="id">
                    
                        <label>Tên khu trọ</label>
                        <input type="text" autofocus id="txt_ten" class="form-control" name="ten" required>

                        <label>Số nhà</label>
                        <input type="text" id="txt_sonha" class="form-control" name="sonha">

                        <label>Đường</label>
                        <input type="text" id="txt_duong" class="form-control" name="duong">

                        <label>Phường</label>
                        <input type="text" id="txt_phuong" class="form-control" name="phuong" required>

                        <label id="lb_vt">Vị trí</label>
                        <div id="map" style="width:100%; height:400px; position:relative;" class="form-control"></div>
                        <input type="hidden" id="txt_lat" class="form-control" name="lat">
                        <input type="hidden" id="txt_lng" class="form-control" name="lng">

                        <button id="btn_update" type="submit" class="btn btn-primary form-control">Lưu</button>
                        <button type="button" class="btn btn-secondary form-control" data-dismiss="modal">Đóng</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal add -->
    <div class="modal fade" id="md_add" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Thêm khu trọ mới</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">

                    <form class="form-group" id="f_add" method="POST" action="/XuLy/them_ktro.php">
                        <input type="hidden" class="form-control" id="new_admin" name="new_admin">
                    
                        <label>Tên khu trọ</label>
                        <input type="text" class="form-control" name="ten" required autofocus>

                        <label>CMND chủ trọ</label>
                        <input type="text" id="txt_cmnd" 
                        onkeyup="this.value=this.value.replace(/[^\d]/,'')" class="form-control" name="cmnd" 
                        title="Vui lòng nhập đủ 9 số cmnd" pattern="[0-9]{9}" required>

                        <label>Họ tên chủ trọ</label>
                        <input type="text" id="txt_hoten" class="form-control" name="hoten" required>

                        <label>SĐT chủ trọ</label>
                        <input type="text" id="txt_sdt" class="form-control" name="sdt" required>

                        <label>Giới tính chủ trọ</label>
                        <select name="gioitinh" id="sl_gt" class="form-control">
                            <option value="M" selected>Nam</option>
                            <option value="W">Nữ</option>
                        </select>

                        <label id="lb_vt_a">Vị trí</label>
                        <div id="map_a" style="width:100%; height:400px; position:relative;" class="form-control"></div>
                        <input type="hidden" id="txt_lat_a" class="form-control" name="lat">
                        <input type="hidden" id="txt_lng_a" class="form-control" name="lng">

                        <label>Số nhà</label>
                        <input type="text" id="txt_sn_a" class="form-control" name="so_nha">

                        <label>Đường</label>
                        <input type="text" id="txt_d_a" class="form-control" name="tenduong">

                        <label>Phường</label>
                        <input type="text" class="form-control" id="txt_p_a" name="phuongxa" readonly>

                        <input type="hidden" class="form-control" id="txt_q_a" name="quanhuyen">
                        <input type="hidden" class="form-control" id="txt_t_a" name="tinh">

                        <button id="btn_add" type="submit" class="btn btn-primary form-control">Thêm</button>
                        <button type="button" class="btn btn-secondary form-control" data-dismiss="modal">Đóng</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal phong -->
    <div class="modal fade" id="md_phong" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content" style="width: 122%">
                <div class="modal-header">
                    <h5 class="modal-title">Danh sách phòng</h5>
                    
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>                    
                </div>
                
                
                <div class="form-inline">
                    <table class="table">
                        <tr>
                            <td colspan="4">
                                <div id="alert_them_phong" style="width: 100%" class="alert alert-danger alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                                    <span></span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="align-middle">
                                <input type="hidden" id="id_tro">
                                Thêm phòng mới
                            </td>
                            <td>
                                <select class="form-control" id="sl_L">
                                    <option value="cb" selected>Cơ Bản</option>
                                    <option value="tn">Tiện Nghi</option>
                                    <option value="cc">Cao Cấp</option>
                                    <option value="mn">Mini House</option>
                                </select>
                            </td>
                            <td>
                                <select class="form-control" id="sl_tt">
                                    <option value="0" selected>Còn Trống</option>
                                    <option value="1">Đã đặt cọc</option>
                                    <option value="2">Đã thuê</option>
                                </select>
                            </td>
                            <td>
                                <button class="btn btn-success" id="btn_them_phong" type="button">
                                    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-plus-circle-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3v-3z"/>
                                    </svg> Thêm phòng
                                </button>
                            </td>
                        </tr>
                    </table>
                </div>
                
                <div class="modal-body my-0 py-0">

                </div>
                <div class="modal-footer">
                    <div>
                        <i>Thay đổi giá trị rồi chọn "Lưu" để cập nhật.</i>
                    </div>
                </div>
                <div class="modal-footer bg-success">
                    <div id="alert_update_phong" style="width: 80%" class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <span></span>
                    </div>
                    <button type="button" class="btn btn-light" data-dismiss="modal">Đóng</button>
                    <button onclick="luu_phong()" type="button" class="btn btn-light">Lưu</button>
                </div>
            </div>
        </div>
    </div>



<script src="/js/ql_khu_tro.js"></script>


    <h2>Danh sách khu trọ</h2><hr>

    <?php
        if (isset($_GET['mess'])) {
            $mess  = $_GET['mess'];
            echo '
            <div id="alert_khutro" class="alert alert-success alert-dismissible fade show">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                '.$mess.'
            </div>
            ';
        }
    ?>
    
    <!-- Button trigger modal -->
    <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#md_add">
        Thêm khu trọ mới
    </button>


    <hr><table id="tb_data" class="table table-hover table-bordered" style="width:100%">
    <h6><i>Click lên tên cột nếu cần sắp xếp</i></h6><br>
    <thead class="table-secondary">
        <tr>
            <th>Tên khu trọ</th>
            <th>Địa chỉ</th>
            <th>Chủ trọ</th>
            <th>Sdt</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php 
            foreach ($ds_khu_tro as $i => $kt) {
                echo '<tr>
                        <td>'.$kt[0].'</td>
                        <td>'.make_address($kt[1], $kt[2], $kt[3], $kt[4], $kt[5]).'</td>
                        <td>'.$kt[6].'</td>
                        <td>'.$kt[7].'</td>
                        <td>
                            <button onclick="show_md_update(\''.$kt[0].'\', \''.$kt[1].'\''.', \''.$kt[2].'\''.', \''.$kt[3].'\', \''.$kt[8].'\', \''.$kt[9].'\', \''.$kt[10].'\')" data-toggle="modal" data-target="#md_update" class="btn btn-warning">Sửa thông tin</button>
                            <button data-toggle="modal" data-target="#md_phong" onclick="show_md_phong(\''.$kt[8].'\',\''.$kt[0].'\')" class="btn btn-info">Dsách Phòng</button>
                            <a href="/XuLy/xoa_khu_tro.php?id='.$kt[8].'"
                                onclick="var r = confirm(\'Xóa tất cả thông tin của '.$kt[0].'\');
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