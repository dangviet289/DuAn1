<?php

require_once './models/Product.php';

class HomeController {
    public function home() {
        // Lấy danh sách sản phẩm
        $top5ProductLatest = Product::getTop5Latest();

        // Hiển thị view
        require_once './views/home.php';
    }
}