<?php 

// Require file Common
require_once './commons/env.php'; // Khai báo biến môi trường
require_once './commons/function.php'; // Hàm hỗ trợ

// Require toàn bộ file Controllers
require_once './controllers/HomeController.php';
require_once './controllers/ProductController.php';
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

    // Danh sách toàn bộ sản phẩm
    case 'products':
        (new ProductController())->index();
        break;

    // Danh sách sản phẩm theo danh mục (Điện thoại, Laptop...)
    case 'category':
        (new ProductController())->category();
        break;

    // Xem chi tiết sản phẩm
    case 'product-detail':
        (new ProductController())->detail();
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

    default:
        http_response_code(404);
        echo 'Không tìm thấy trang.';
        break;
};
