<?php
/**
 * Thêm method này vào class Product hiện có của bạn (models/Product.php),
 * đặt cạnh getTop5Latest() để dùng chung 1 connection PDO.
 */

/**
 * Lấy sản phẩm nổi bật (is_featured = 1, is_active = 1)
 * Dùng cho khối "Sản phẩm nổi bật" ở trang chủ.
 *
 * @param int $limit
 * @return array
 */
public static function getFeatured($limit = 8)
{
    $db = Database::getConnection();

    $stmt = $db->prepare(
        "SELECT id, name, slug, thumbnail, price, sale_price,
                rating, review_count, sold, stock
         FROM products
         WHERE is_featured = 1 AND is_active = 1
         ORDER BY created_at DESC
         LIMIT :limit"
    );
    $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}