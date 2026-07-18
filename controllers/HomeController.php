<?php

require_once './models/Product.php';
require_once './models/category.php';
require_once './models/banner.php';

class HomeController
{
    public function home()
    {
        // Banner đang hoạt động, sắp xếp theo thứ tự hiển thị
        $banners = Banner::getActive();

        // Danh mục cha đang hoạt động (hiển thị ở thanh danh mục)
        $categories = Category::getActiveParents();

        // Sản phẩm nổi bật (is_featured = 1)
        $featuredProducts = Product::getFeatured(8);

        // Top 5 sản phẩm mới nhất
        $top5ProductLatest = Product::getTop5Latest();

        // Hiển thị view
        require_once './views/home.php';
    }
}
