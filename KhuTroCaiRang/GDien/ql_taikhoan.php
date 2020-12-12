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

        $query = "SELECT * FROM tai_khoan";

        $result = mysqli_query($conn, $query);

        $ds_tai_khoan = [];
        while ($row = mysqli_fetch_array($result)) {
            $ds_tai_khoan[] = [$row["uname"], $row["hoten"]];
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
                    <h5 class="modal-title">Cập nhật người dùng</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">

                    <form class="form-group" id="f_update" method="POST" action="/XuLy/update_tai_khoan.php">
                        <label>Tên tài khoản</label>
                        <input type="text" id="txt_uname" class="form-control" name="uname" readonly>

                        <label>Họ tên</label>
                        <input type="text" id="txt_hoten" class="form-control" name="hoten" 
                         oninvalid="this.setCustomValidity('Vui lòng nhập tên họ tên')">

                        <label>Mật khẩu mới</label>
                        <input type="password" id="txt_pass" class="form-control" name="pass">
                        <small id="helpId" class="text-muted">Điền nếu cần đổi mật khẩu</small>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    <button onclick="$(f_update).submit();" type="button" class="btn btn-primary">Lưu</button>
                </div>
            </div>
        </div>
    </div>

<script>
    function show_md_update(uname, hoten) {
        $("#txt_uname").val(uname);
        $("#txt_hoten").val(hoten);
    }

    $(document).ready(function() {
        $('#tb_data').DataTable();

        $(".alert").fadeTo(2000, 500).slideUp(500, function(){
            $(".alert").slideUp(500);
        });
    });
</script>


    <h2>Danh sách quản trị viên</h2><hr>
    
    <?php
        if (isset($_GET['mess'])) {
            $mess  = $_GET['mess'];
            echo '
            <div class="alert alert-success alert-dismissible fade show">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                '.$mess.'
            </div>
            ';
        }
    ?>
    <h6><b>Thêm quản trị viên mới</b></h6><br>
    <form class="form-inline" method="POST" action="/XuLy/them_tai_khoan.php">
        <label>Tên tài khoản</label>
        <input type="text" class="form-control ml-3" name="uname" required>

        <label class="ml-3">Họ tên</label>
        <input type="text" class="form-control ml-3" name="hoten" required>

        <label class="ml-3">Mật khẩu</label>
        <input type="password" class="form-control ml-3" name="pass" required>

        <button type="submit" class="btn btn-success ml-3">Thêm</button>
    </form>


    <hr><table id="tb_data" class="table table-hover table-bordered" style="width:100%">
    <h6><i>Click lên tên cột nếu cần sắp xếp</i></h6><br>
    <thead class="table-secondary">
        <tr>
            <th>Tên tài khoản</th>
            <th>Họ tên</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php 
            foreach ($ds_tai_khoan as $i => $u) {
                echo '<tr>
                        <td>'.$u[0].'</td>
                        <td>'.$u[1].'</td>
                        <td>
                            <button onclick="show_md_update(\''.$u[0].'\', \''.$u[1].'\')" data-toggle="modal" data-target="#md_update" class="btn btn-warning">Sửa</button>
                            <a href="/XuLy/xoa_tai_khoan.php?uname='.$u[0].'"
                                onclick="var r = confirm(\'Xóa người dùng '.$u[1].'\');
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