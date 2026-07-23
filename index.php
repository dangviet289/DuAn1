<?php 

// Khởi tạo session
session_start();

// Require file Common
require_once './commons/env.php'; // Khai báo biến môi trường
require_once './commons/function.php'; // Hàm hỗ trợ

// Require toàn bộ file Controllers
require_once './controllers/HomeController.php';
require_once './controllers/AuthController.php';
require_once './controllers/AdminProductController.php';

// Require toàn bộ file Models
require_once './models/Product.php';

// Route
$act = $_GET['act'] ?? '/';

switch ($act) {
    // Trang chủ
    case '/':
        (new HomeController())->home();
        break;
    
    // Auth Routes
    case 'login':
        (new AuthController())->login();
        break;

    case 'register':
        (new AuthController())->register();
        break;

    case 'logout':
        (new AuthController())->logout();
        break;
    
    // Admin Routes
    case 'product-list':
        (new AdminProductController())->list();
        break;

    case 'product-create':
        (new AdminProductController())->showCreate();
        break;

    case 'product-store':
        (new AdminProductController())->store();
        break;

    case 'product-edit':
        (new AdminProductController())->showEdit();
        break;

    case 'product-update':
        (new AdminProductController())->update();
        break;

    case 'product-delete':
        (new AdminProductController())->delete();
        break;
};