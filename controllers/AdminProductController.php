<?php

require_once './models/Product.php';

class AdminProductController {
    
    public function list() {
        $products = Product::getAll();
        require_once './views/admin/products/list.php';
    }

    public function showCreate() {
        require_once './views/admin/products/create.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $type = $_POST['type'] ?? '';

            if (empty($name) || empty($type)) {
                die('Vui lòng điền đủ thông tin');
            }

            Product::create($name, $type);
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
        
        require_once './views/admin/products/edit.php';
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $product_id = $_POST['product_id'] ?? null;
            $name = $_POST['name'] ?? '';
            $type = $_POST['type'] ?? '';

            if (!$product_id || empty($name) || empty($type)) {
                die('Vui lòng điền đủ thông tin');
            }

            Product::update($product_id, $name, $type);
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
}
