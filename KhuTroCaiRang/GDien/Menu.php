<nav class="navbar navbar-expand-lg navbar-dark bg-success">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse m-0" id="navbarTogglerDemo01">
        <a class="navbar-brand m-0 p-0" href="/">
            <img class="img-thumbnail m-0 p-0" style="width: 64px" src="/images/icons/house.png" alt="abc"></a>
        <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
            <li class="nav-item <?php if($_SERVER['REQUEST_URI'] == '/') echo "active"; ?>">
            <a class="nav-link lead" href="/">Bản đồ<span class="sr-only">(current)</span></a>
            </li>

            <?php
                if ($_SESSION["uname"]) {

                    $active = "";

                    // Menu khu trọ
                    if(strpos($_SERVER['REQUEST_URI'], '/GDien/ql_khu_tro.php') !== false)  
                        $active = "active";
                    else $active = "";       
                    
                    echo '
                        <li class="nav-item '.$active.'">
                        <a class="nav-link lead" href="/GDien/ql_khu_tro.php">Khu trọ</a>
                        </li>
                    ';

                    // Menu loại phòng
                    if(strpos($_SERVER['REQUEST_URI'], '/GDien/ql_loai_phong.php') !== false)  
                        $active = "active";
                    else $active = "";       
                    
                    echo '
                        <li class="nav-item '.$active.'">
                        <a class="nav-link lead" href="/GDien/ql_loai_phong.php">Loại phòng</a>
                        </li>
                    ';

                    // Menu chủ trọ
                    // if(strpos($_SERVER['REQUEST_URI'], '/GDien/ql_chu_tro.php') !== false) 
                    //     $active = "active";
                    // else $active = "";
                    
                    // echo '
                    //     <li class="nav-item '.$active.'">
                    //     <a class="nav-link lead" href="/GDien/ql_chu_tro.php">Chủ trọ</a>
                    //     </li>
                    // ';

                    // Menu trường học
                    // if(strpos($_SERVER['REQUEST_URI'], '/GDien/ql_truong.php') !== false) 
                    //     $active = "active";
                    // else $active = "";

                    // echo '
                    //     <li class="nav-item '.$active.'">
                    //     <a class="nav-link lead" href="/GDien/ql_truong.php">Trường học</a>
                    //     </li>
                    // ';
                    

                    // Menu quản lý tài khoản
                    if(strpos($_SERVER['REQUEST_URI'], '/GDien/ql_taikhoan.php') !== false) 
                        $active = "active";
                    else $active = "";

                    echo '
                        <li class="nav-item '.$active.'">
                        <a class="nav-link lead" href="/GDien/ql_taikhoan.php">Quản lý tài khoản</a>
                        </li>
                    ';
                }
            ?>
        </ul>
        
        <div class="nav justify-content-end">

            <?php

                if (!$_SESSION["uname"]) {
                    echo '
                    <a class="btn btn-light lead" href="/GDien/Login.php">
                    Đăng nhập<span class="sr-only">(current)</span></a>
                    ';
                }
                else{
                    echo '
                    <p class="lead mr-3 text-light">'.$_SESSION["uhoten"].'</p>
                    <a class="btn btn-light lead" href="/GDien/Logout.php">
                    Đăng xuất<span class="sr-only">(current)</span></a>
                    ';
                }
            ?>
        </div>
    </div>
</nav>