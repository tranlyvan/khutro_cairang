<?php
if (!$_SESSION['uname']){
    // PHP permanent URL redirection
    header("Location: /GDien/Login.php", true, 301);
    exit();
}
?>