<?php

class Product
{
    // Lấy toàn bộ sản phẩm
    public static function getAll()
    {
        $conn = connectDB();
        $stmt = $conn->prepare(
            "SELECT p.*, c.name AS category_name, c.slug AS category_slug,
                    b.name AS brand_name
             FROM products p
             LEFT JOIN categories c ON c.id = p.category_id
             LEFT JOIN brands b ON b.id = p.brand_id
             ORDER BY p.created_at DESC"
        );
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Danh sách sản phẩm ở trang cửa hàng
    public static function getCatalog($featuredOnly = false, $sort = 'newest')
    {
        $conn = connectDB();
        $where = 'p.is_active = 1';
        if ($featuredOnly) {
            $where .= ' AND p.is_featured = 1';
        }

        $orderBy = $sort === 'price-asc'
            ? 'COALESCE(p.sale_price, p.price) ASC'
            : ($sort === 'price-desc'
                ? 'COALESCE(p.sale_price, p.price) DESC'
                : 'p.created_at DESC');

        $stmt = $conn->prepare(
            "SELECT p.*, c.name AS category_name, c.slug AS category_slug,
                    b.name AS brand_name
             FROM products p
             LEFT JOIN categories c ON c.id = p.category_id
             LEFT JOIN brands b ON b.id = p.brand_id
             WHERE $where
             ORDER BY $orderBy"
        );
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
        $stmt = $conn->prepare(
            "SELECT p.*, c.name AS category_name, c.slug AS category_slug,
                    b.name AS brand_name
             FROM products p
             LEFT JOIN categories c ON c.id = p.category_id
             LEFT JOIN brands b ON b.id = p.brand_id
             WHERE p.id = ?
             LIMIT 1"
        );
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Lấy 1 sản phẩm theo slug, dùng cho trang chi tiết sản phẩm
    public static function getBySlug($slug)
    {
        $conn = connectDB();
        $stmt = $conn->prepare(
            "SELECT p.*, c.name AS category_name, c.slug AS category_slug,
                    b.name AS brand_name
             FROM products p
             LEFT JOIN categories c ON c.id = p.category_id
             LEFT JOIN brands b ON b.id = p.brand_id
             WHERE p.slug = ? AND p.is_active = 1
             LIMIT 1"
        );
        $stmt->execute([$slug]);
        return $stmt->fetch();
    }

    // Danh sách sản phẩm theo danh mục
    public static function getByCategory($categoryId, $sort = 'newest')
    {
        $conn = connectDB();
        $orderBy = $sort === 'price-asc'
            ? 'COALESCE(p.sale_price, p.price) ASC'
            : ($sort === 'price-desc'
                ? 'COALESCE(p.sale_price, p.price) DESC'
                : 'p.created_at DESC');
        $stmt = $conn->prepare(
            "SELECT p.*, c.name AS category_name, c.slug AS category_slug,
                    b.name AS brand_name
             FROM products p
             LEFT JOIN categories c ON c.id = p.category_id
             LEFT JOIN brands b ON b.id = p.brand_id
             WHERE p.category_id = ? AND p.is_active = 1
             ORDER BY $orderBy"
        );
        $stmt->execute([$categoryId]);
        return $stmt->fetchAll();
    }

    public static function getImages($productId)
    {
        $conn = connectDB();
        $stmt = $conn->prepare(
            "SELECT * FROM product_images
             WHERE product_id = ?
             ORDER BY sort_order ASC, id ASC"
        );
        $stmt->execute([$productId]);
        return $stmt->fetchAll();
    }

    public static function getSpecifications($productId)
    {
        $conn = connectDB();
        $stmt = $conn->prepare(
            "SELECT * FROM product_specifications
             WHERE product_id = ?
             ORDER BY sort_order ASC, id ASC"
        );
        $stmt->execute([$productId]);
        return $stmt->fetchAll();
    }

    public static function getVariants($productId)
    {
        $conn = connectDB();
        $stmt = $conn->prepare(
            "SELECT * FROM product_variants
             WHERE product_id = ?
             ORDER BY price ASC, id ASC"
        );
        $stmt->execute([$productId]);
        return $stmt->fetchAll();
    }

    public static function getRelated($categoryId, $excludeId, $limit = 4)
    {
        $conn = connectDB();
        $stmt = $conn->prepare(
            "SELECT p.*, c.name AS category_name, c.slug AS category_slug,
                    b.name AS brand_name
             FROM products p
             LEFT JOIN categories c ON c.id = p.category_id
             LEFT JOIN brands b ON b.id = p.brand_id
             WHERE p.category_id = :category_id
               AND p.id <> :exclude_id
               AND p.is_active = 1
             ORDER BY p.is_featured DESC, p.created_at DESC
             LIMIT :limit"
        );
        $stmt->bindValue(':category_id', (int) $categoryId, PDO::PARAM_INT);
        $stmt->bindValue(':exclude_id', (int) $excludeId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function getBrands()
    {
        $conn = connectDB();
        $stmt = $conn->prepare("SELECT * FROM brands ORDER BY name ASC");
        $stmt->execute();
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
