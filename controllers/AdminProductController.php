<?php

require_once './models/Product.php';
require_once './models/category.php';

class AdminProductController {
    
    public function list() {
        $products = Product::getAll();
        require_once './views/admin/products/list.php';
    }

    public function showCreate() {
        $categories = Category::getAll();
        $brands = Product::getBrands();
        require_once './views/admin/products/create.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $this->productDataFromRequest();

            if (empty($data['name']) || empty($data['category_id']) || empty($data['brand_id']) || $data['price'] <= 0) {
                die('Vui lòng điền đủ thông tin');
            }

            Product::create($data);
            header('Location: ' . BASE_URL_ADMIN . '?act=product-list');
            exit();
        }
    }

    public function showEdit() {
        $product_id = $_GET['product_id'] ?? null;
        if (!$product_id) {
            header('Location: ' . BASE_URL_ADMIN . '?act=product-list');
            exit();
        }
        
        $product = Product::getById($product_id);
        if (!$product) {
            header('Location: ' . BASE_URL_ADMIN . '?act=product-list');
            exit();
        }

        $categories = Category::getAll();
        $brands = Product::getBrands();
        
        require_once './views/admin/products/edit.php';
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $product_id = $_POST['product_id'] ?? null;
            $data = $this->productDataFromRequest();

            if (!$product_id || empty($data['name']) || empty($data['category_id']) || empty($data['brand_id']) || $data['price'] <= 0) {
                die('Vui lòng điền đủ thông tin');
            }

            Product::update($product_id, $data);
            header('Location: ' . BASE_URL_ADMIN . '?act=product-list');
            exit();
        }
    }

    public function delete() {
        $product_id = $_GET['product_id'] ?? null;
        if (!$product_id) {
            header('Location: ' . BASE_URL_ADMIN . '?act=product-list');
            exit();
        }

        Product::delete($product_id);
        header('Location: ' . BASE_URL_ADMIN . '?act=product-list');
        exit();
    }

    private function productDataFromRequest()
    {
        $name = trim((string) ($_POST['name'] ?? ''));
        $slug = trim((string) ($_POST['slug'] ?? ''));

        return [
            'name' => $name,
            'slug' => $slug !== '' ? makeSlug($slug) : makeSlug($name),
            'thumbnail' => trim((string) ($_POST['thumbnail'] ?? '')) ?: null,
            'price' => max(0, (float) ($_POST['price'] ?? 0)),
            'sale_price' => ($_POST['sale_price'] ?? '') !== '' ? max(0, (float) $_POST['sale_price']) : null,
            'category_id' => (int) ($_POST['category_id'] ?? 0),
            'brand_id' => (int) ($_POST['brand_id'] ?? 0),
            'description' => trim((string) ($_POST['description'] ?? '')) ?: null,
            'stock' => max(0, (int) ($_POST['stock'] ?? 0)),
            'is_active' => isset($_POST['is_active']) ? 1 : 0,
            'is_featured' => isset($_POST['is_featured']) ? 1 : 0,
        ];
    }
}
