<?php

require_once './models/Product.php';
require_once './models/category.php';

class ProductController
{
    // Trang tất cả sp / sp nổi bật
    public function index()
    {
        $featuredOnly = ($_GET['featured'] ?? '') === '1';
        $sort = $_GET['sort'] ?? 'newest';
        $allowedSorts = ['newest', 'price-asc', 'price-desc'];

        if (!in_array($sort, $allowedSorts, true)) {
            $sort = 'newest';
        }

        $categories = Category::getActiveParents();
        $products = Product::getCatalog($featuredOnly, $sort);
        $category = null;
        $pageTitle = $featuredOnly ? 'Sản phẩm nổi bật' : 'Tất cả sản phẩm';

        require './views/products/list.php';
    }

    // Trang danh sách
    public function category()
    {
        $slug = trim((string) ($_GET['slug'] ?? ''));
        $category = $slug !== '' ? Category::getBySlug($slug) : false;

        if (!$category) {
            $this->notFound('Không tìm thấy danh mục bạn yêu cầu.');
            return;
        }

        $sort = $_GET['sort'] ?? 'newest';
        if (!in_array($sort, ['newest', 'price-asc', 'price-desc'], true)) {
            $sort = 'newest';
        }

        $categories = Category::getActiveParents();
        $products = Product::getByCategory($category['id'], $sort);
        $pageTitle = $category['name'];

        require './views/products/list.php';
    }

    // chi tiết sp
    public function detail()
    {
        $slug = trim((string) ($_GET['slug'] ?? ''));
        $product = $slug !== '' ? Product::getBySlug($slug) : false;

        if (!$product) {
            $this->notFound('Không tìm thấy sản phẩm bạn yêu cầu.');
            return;
        }

        $categories = Category::getActiveParents();
        $images = Product::getImages($product['id']);
        $specifications = Product::getSpecifications($product['id']);
        $variants = Product::getVariants($product['id']);
        $relatedProducts = Product::getRelated($product['category_id'], $product['id'], 4);

        require './views/products/detail.php';
    }

    private function notFound($message)
    {
        http_response_code(404);
        $categories = Category::getActiveParents();
        require './views/errors/404.php';
    }
}
