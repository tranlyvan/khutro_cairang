<?php include "./head.php"; ?>

<div class="limiter">
    <div class="container-login100">
        <div class="wrap-login100">
            <form method="POST" action="/XuLy/login_process.php" class="login100-form validate-form p-l-55 p-r-55 p-t-178">
                <span class="login100-form-title">
                    Đăng nhập quyền quản trị
                </span>

                <?php
                    $mess  = $_GET['mess'];

                    if ($mess != NULL) {
                        echo '<div class="text-center text-danger font-weight-bold p-t-13 p-b-23">'.$mess.'</div>';
                    }
                ?>

                <div class="wrap-input100 validate-input m-b-16" data-validate="Vui lòng nhập username">
                    <input class="input100" required type="text" name="uname" placeholder="Tên đăng nhập">
                    <span class="focus-input100"></span>
                </div>

                <div class="wrap-input100 validate-input" data-validate = "Vui lòng nhập mật khẩu">
                    <input class="input100" required type="password" name="pass" placeholder="mật khẩu">
                    <span class="focus-input100"></span>
                </div>

                <div class="text-right p-t-13 p-b-23"></div>

                <div class="container-login100-form-btn">
                    <button class="login100-form-btn">
                        Đăng nhập
                    </button>
                </div>

                <div class="flex-col-c p-4"></div>
            </form>
        </div>
    </div>
</div>
	
	
<!--===============================================================================================-->
	<script src="vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/animsition/js/animsition.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/bootstrap/js/popper.js"></script>
	<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/select2/select2.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/daterangepicker/moment.min.js"></script>
	<script src="vendor/daterangepicker/daterangepicker.js"></script>
<!--===============================================================================================-->
	<script src="vendor/countdowntime/countdowntime.js"></script>
<!--===============================================================================================-->
	<script src="js/main.js"></script>