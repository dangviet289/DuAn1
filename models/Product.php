<?php

class Product
{
    // Lấy toàn bộ sản phẩm
    public static function getAll()
    {
        $conn = connectDB();
        $stmt = $conn->prepare("SELECT * FROM products ORDER BY created_at DESC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Top 5 sản phẩm mới nhất (còn hoạt động)
    public static function getTop5Latest()
    {
        $conn = connectDB();
        $stmt = $conn->prepare(
            "SELECT * FROM products
             WHERE is_active = 1
             ORDER BY created_at DESC
             LIMIT 5"
        );
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Sản phẩm nổi bật, dùng cho trang chủ
    public static function getFeatured($limit = 8)
    {
        $conn = connectDB();
        $stmt = $conn->prepare(
            "SELECT * FROM products
             WHERE is_featured = 1 AND is_active = 1
             ORDER BY created_at DESC
             LIMIT :limit"
        );
        $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Lấy 1 sản phẩm theo id
    public static function getById($id)
    {
        $conn = connectDB();
        $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Lấy 1 sản phẩm theo slug, dùng cho trang chi tiết sản phẩm
    public static function getBySlug($slug)
    {
        $conn = connectDB();
        $stmt = $conn->prepare("SELECT * FROM products WHERE slug = ?");
        $stmt->execute([$slug]);
        return $stmt->fetch();
    }

    // Danh sách sản phẩm theo danh mục
    public static function getByCategory($categoryId)
    {
        $conn = connectDB();
        $stmt = $conn->prepare(
            "SELECT * FROM products
             WHERE category_id = ? AND is_active = 1
             ORDER BY created_at DESC"
        );
        $stmt->execute([$categoryId]);
        return $stmt->fetchAll();
    }

    // Tạo sản phẩm mới
    public static function create($data)
    {
        $conn = connectDB();
        $stmt = $conn->prepare(
            "INSERT INTO products
                (name, slug, thumbnail, price, sale_price, category_id, brand_id,
                 description, stock, is_active, is_featured)
             VALUES
                (:name, :slug, :thumbnail, :price, :sale_price, :category_id, :brand_id,
                 :description, :stock, :is_active, :is_featured)"
        );

        return $stmt->execute([
            ':name'        => $data['name'],
            ':slug'        => $data['slug'],
            ':thumbnail'   => $data['thumbnail'] ?? null,
            ':price'       => $data['price'],
            ':sale_price'  => $data['sale_price'] ?? null,
            ':category_id' => $data['category_id'],
            ':brand_id'    => $data['brand_id'],
            ':description' => $data['description'] ?? null,
            ':stock'       => $data['stock'] ?? 0,
            ':is_active'   => $data['is_active'] ?? 1,
            ':is_featured' => $data['is_featured'] ?? 0,
        ]);
    }

    // Cập nhật sản phẩm
    public static function update($id, $data)
    {
        $conn = connectDB();
        $stmt = $conn->prepare(
            "UPDATE products SET
                name = :name,
                slug = :slug,
                thumbnail = :thumbnail,
                price = :price,
                sale_price = :sale_price,
                category_id = :category_id,
                brand_id = :brand_id,
                description = :description,
                stock = :stock,
                is_active = :is_active,
                is_featured = :is_featured
             WHERE id = :id"
        );

        return $stmt->execute([
            ':id'          => $id,
            ':name'        => $data['name'],
            ':slug'        => $data['slug'],
            ':thumbnail'   => $data['thumbnail'] ?? null,
            ':price'       => $data['price'],
            ':sale_price'  => $data['sale_price'] ?? null,
            ':category_id' => $data['category_id'],
            ':brand_id'    => $data['brand_id'],
            ':description' => $data['description'] ?? null,
            ':stock'       => $data['stock'] ?? 0,
            ':is_active'   => $data['is_active'] ?? 1,
            ':is_featured' => $data['is_featured'] ?? 0,
        ]);
    }

    // Xóa sản phẩm
    public static function delete($id)
    {
        $conn = connectDB();
        $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
        return $stmt->execute([$id]);
    }
}