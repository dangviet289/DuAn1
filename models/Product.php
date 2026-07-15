<?php

class Product {
    public static function getAll() {
        $conn = connectDB();
        $stmt = $conn->prepare("SELECT * FROM products");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function getTop5Latest() {
        $conn = connectDB();
        $stmt = $conn->prepare("SELECT * FROM products ORDER BY product_id DESC LIMIT 5");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function getById($product_id) {
        $conn = connectDB();
        $stmt = $conn->prepare("SELECT * FROM products WHERE product_id = ?");
        $stmt->execute([$product_id]);
        return $stmt->fetch();
    }

    public static function create($name, $type) {
        $conn = connectDB();
        $stmt = $conn->prepare("INSERT INTO products (name, type) VALUES (?, ?)");
        return $stmt->execute([$name, $type]);
    }

    public static function update($product_id, $name, $type) {
        $conn = connectDB();
        $stmt = $conn->prepare("UPDATE products SET name = ?, type = ? WHERE product_id = ?");
        return $stmt->execute([$name, $type, $product_id]);
    }

    public static function delete($product_id) {
        $conn = connectDB();
        $stmt = $conn->prepare("DELETE FROM products WHERE product_id = ?");
        return $stmt->execute([$product_id]);
    }
}