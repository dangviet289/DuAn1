<?php

class Category
{
    // Danh mục cha đang hoạt động — dùng cho thanh danh mục ở trang chủ
    public static function getActiveParents()
    {
        $conn = connectDB();
        $stmt = $conn->prepare(
            "SELECT * FROM categories
             WHERE is_active = 1 AND parent_id IS NULL
             ORDER BY name ASC"
        );
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Toàn bộ danh mục
    public static function getAll()
    {
        $conn = connectDB();
        $stmt = $conn->prepare("SELECT * FROM categories ORDER BY name ASC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function getById($id)
    {
        $conn = connectDB();
        $stmt = $conn->prepare("SELECT * FROM categories WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Lấy danh mục đang hoạt động theo slug
    public static function getBySlug($slug)
    {
        $conn = connectDB();
        $stmt = $conn->prepare(
            "SELECT * FROM categories
             WHERE slug = ? AND is_active = 1
             LIMIT 1"
        );
        $stmt->execute([$slug]);
        return $stmt->fetch();
    }
}
