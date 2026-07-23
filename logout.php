<?php
session_start();

// Xóa tất cả session variables
session_destroy();

// Chuyển hướng về trang chủ
header("Location: " . (isset($_GET['redirect']) ? htmlspecialchars($_GET['redirect']) : '/1_project/DuAn1/'));
exit;
