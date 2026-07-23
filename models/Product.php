<?php

class Product
{
    // Lấy toàn bộ sản phẩm
    public static function getAll()
    {
        $conn = connectDB();
        $stmt = $conn->prepare("
            SELECT p.*, p.id AS product_id, c.name AS type 
            FROM products p 
            LEFT JOIN categories c ON p.category_id = c.id 
            ORDER BY p.created_at DESC
        ");
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
        $stmt = $conn->prepare("
            SELECT p.*, p.id AS product_id, p.category_id AS type 
            FROM products p 
            WHERE p.id = ?
        ");
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
    public static function create($data, $type = null)
    {
        if (!is_array($data)) {
            $name = $data;
            $data = [
                'name'        => $name,
                'slug'        => self::helperCreateSlug($name),
                'price'       => 0,
                'category_id' => $type,
                'brand_id'    => 1,
            ];
        }
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
            ':price'       => $data['price'] ?? 0,
            ':sale_price'  => $data['sale_price'] ?? null,
            ':category_id' => $data['category_id'] ?? null,
            ':brand_id'    => $data['brand_id'] ?? 1,
            ':description' => $data['description'] ?? null,
            ':stock'       => $data['stock'] ?? 0,
            ':is_active'   => $data['is_active'] ?? 1,
            ':is_featured' => $data['is_featured'] ?? 0,
        ]);
    }

    // Cập nhật sản phẩm
    public static function update($id, $data = null, $type = null)
    {
        if (!is_array($data)) {
            $name = $data;
            $data = [
                'name'        => $name,
                'slug'        => self::helperCreateSlug($name),
                'price'       => 0,
                'category_id' => $type,
                'brand_id'    => 1,
            ];
        }
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
            ':price'       => $data['price'] ?? 0,
            ':sale_price'  => $data['sale_price'] ?? null,
            ':category_id' => $data['category_id'] ?? null,
            ':brand_id'    => $data['brand_id'] ?? 1,
            ':description' => $data['description'] ?? null,
            ':stock'       => $data['stock'] ?? 0,
            ':is_active'   => $data['is_active'] ?? 1,
            ':is_featured' => $data['is_featured'] ?? 0,
        ]);
    }

    private static function helperCreateSlug($str) {
        $str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", 'a', $str);
        $str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $str);
        $str = preg_replace("/(ì|í|ị|ỉ|ĩ)/", 'i', $str);
        $str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", 'o', $str);
        $str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $str);
        $str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", 'y', $str);
        $str = preg_replace("/(đ)/", 'd', $str);
        $str = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", 'A', $str);
        $str = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", 'E', $str);
        $str = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", 'I', $str);
        $str = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", 'O', $str);
        $str = preg_replace("/(Ù|Ú|Ụ|Ủ|U|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", 'U', $str);
        $str = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", 'Y', $str);
        $str = preg_replace("/(Đ)/", 'D', $str);
        $str = strtolower(trim($str));
        $str = preg_replace('/[^a-z0-9-]+/', '-', $str);
        return trim(preg_replace('/-+/', '-', $str), '-');
    }

    // Xóa sản phẩm
    public static function delete($id)
    {
        $conn = connectDB();
        $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
        return $stmt->execute([$id]);
    }
}