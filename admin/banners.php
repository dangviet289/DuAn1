<?php

require_once __DIR__ . '/../commons/env.php';
require_once __DIR__ . '/../commons/function.php';
require_once __DIR__ . '/../controllers/AdminBannerController.php';

$act = $_GET['act'] ?? 'list';

switch ($act) {
    case 'list':
        (new AdminBannerController())->list();
        break;

    case 'create':
        (new AdminBannerController())->showCreate();
        break;

    case 'store':
        (new AdminBannerController())->store();
        break;

    case 'edit':
        (new AdminBannerController())->showEdit();
        break;

    case 'update':
        (new AdminBannerController())->update();
        break;

    case 'delete':
        (new AdminBannerController())->delete();
        break;

    default:
        http_response_code(404);
        echo 'Không tìm thấy trang quản trị banner.';
        break;
}
