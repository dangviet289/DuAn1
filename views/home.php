<?php
/**
 * Dữ liệu cần truyền từ controller sang view này:
 *
 * $banners           -> SELECT * FROM banners WHERE is_active = 1 ORDER BY sort_order
 * $categories        -> SELECT * FROM categories WHERE is_active = 1 AND parent_id IS NULL
 * $featuredProducts  -> SELECT * FROM products WHERE is_featured = 1 AND is_active = 1 LIMIT 8
 * $top5ProductLatest -> SELECT * FROM products WHERE is_active = 1 ORDER BY created_at DESC LIMIT 5
 *
 * Mỗi phần tử product cần các cột: name, slug, thumbnail, price, sale_price,
 * rating, review_count, sold, stock (đúng như bảng `products` trong project_1.sql)
 */

// Định dạng tiền VNĐ
function formatVnd($value)
{
    return number_format((float) $value, 0, ',', '.') . 'đ';
}

// % giảm giá nếu có sale_price
function discountPercent($price, $salePrice)
{
    if (empty($salePrice) || $salePrice >= $price) {
        return 0;
    }
    return (int) round((($price - $salePrice) / $price) * 100);
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang chủ</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        :root {
            --navy: #16213E;
            --navy-soft: #223159;
            --electric: #2F6FED;
            --electric-dark: #1F52C4;
            --coral: #FF5A5F;
            --mint: #17B890;
            --bg: #F5F7FB;
            --paper: #FFFFFF;
            --ink: #1A1F2B;
            --ink-soft: #6B7280;
            --line: #E7EAF2;
            --radius: 14px;
        }

        * { box-sizing: border-box; }

        body {
            background: var(--bg);
            color: var(--ink);
            font-family: 'Inter', system-ui, sans-serif;
            margin: 0;
        }

        h1, h2, h3, .display {
            font-family: 'Space Grotesk', system-ui, sans-serif;
        }

        a { text-decoration: none; }

        .container { max-width: 1180px; }

        /* ---------- Hero / banner ---------- */
        .hero {
            padding: 28px 0 8px;
        }

        .hero-slide {
            border-radius: 18px;
            overflow: hidden;
            position: relative;
            background: linear-gradient(135deg, var(--navy), var(--navy-soft));
            min-height: 300px;
            display: flex;
            align-items: center;
        }

        .hero-slide img {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0.55;
        }

        .hero-slide .hero-caption {
            position: relative;
            z-index: 2;
            padding: 40px 48px;
            color: #fff;
            max-width: 520px;
        }

        .hero-caption .eyebrow {
            font-size: 12.5px;
            font-weight: 700;
            letter-spacing: 1.4px;
            text-transform: uppercase;
            color: #9FC1FF;
            margin-bottom: 10px;
        }

        .hero-caption h2 {
            font-size: 32px;
            font-weight: 700;
            margin: 0 0 14px;
            line-height: 1.2;
        }

        .hero-caption .btn-hero {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: var(--electric);
            color: #fff;
            font-weight: 600;
            padding: 11px 22px;
            border-radius: 10px;
            font-size: 14px;
        }

        .hero-caption .btn-hero:hover { background: var(--electric-dark); color: #fff; }

        .carousel-indicators button { background-color: rgba(255,255,255,0.5); }
        .carousel-indicators .active { background-color: #fff; }

        /* ---------- Category strip ---------- */
        .cat-strip {
            padding: 26px 0 6px;
        }

        .cat-strip .row-g {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 14px;
        }

        .cat-card {
            background: var(--paper);
            border: 1px solid var(--line);
            border-radius: var(--radius);
            padding: 18px 12px;
            text-align: center;
            color: var(--ink);
            transition: transform .12s ease, border-color .12s ease, box-shadow .12s ease;
        }

        .cat-card:hover {
            transform: translateY(-3px);
            border-color: var(--electric);
            box-shadow: 0 10px 20px rgba(47, 111, 237, 0.12);
            color: var(--ink);
        }

        .cat-card img {
            width: 42px;
            height: 42px;
            object-fit: contain;
            margin-bottom: 10px;
        }

        .cat-card .cat-name {
            font-size: 13.5px;
            font-weight: 600;
        }

        /* ---------- Section heading ---------- */
        .section {
            padding: 30px 0 6px;
        }

        .section-head {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-bottom: 18px;
        }

        .section-head .eyebrow {
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 1.2px;
            text-transform: uppercase;
            color: var(--electric);
            margin-bottom: 4px;
        }

        .section-head h2 {
            font-size: 23px;
            font-weight: 700;
            margin: 0;
        }

        .section-head .see-all {
            font-size: 13.5px;
            font-weight: 600;
            color: var(--electric-dark);
        }

        /* ---------- Product grid ---------- */
        .product-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 18px;
        }

        .product-card {
            background: var(--paper);
            border: 1px solid var(--line);
            border-radius: var(--radius);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            color: var(--ink);
            transition: transform .14s ease, box-shadow .14s ease;
            position: relative;
        }

        .product-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 14px 28px rgba(22, 33, 62, 0.10);
            color: var(--ink);
        }

        .product-thumb {
            aspect-ratio: 1 / 1;
            background: #F0F2F8;
            position: relative;
            overflow: hidden;
        }

        .product-thumb img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            padding: 14px;
        }

        .discount-tag {
            position: absolute;
            top: 10px;
            left: 10px;
            background: var(--coral);
            color: #fff;
            font-size: 11.5px;
            font-weight: 700;
            padding: 3px 8px;
            border-radius: 6px;
        }

        .product-body {
            padding: 14px 16px 16px;
            display: flex;
            flex-direction: column;
            gap: 6px;
            flex: 1;
        }

        .product-name {
            font-size: 14px;
            font-weight: 600;
            line-height: 1.35;
            min-height: 38px;
        }

        .product-rating {
            font-size: 12.5px;
            color: var(--ink-soft);
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .product-rating .stars { color: #F5A623; }

        .product-price-row {
            margin-top: auto;
            display: flex;
            align-items: baseline;
            gap: 8px;
            flex-wrap: wrap;
        }

        .price-now {
            font-size: 16.5px;
            font-weight: 700;
            color: var(--coral);
        }

        .price-old {
            font-size: 12.5px;
            color: var(--ink-soft);
            text-decoration: line-through;
        }

        .price-normal {
            font-size: 16.5px;
            font-weight: 700;
            color: var(--navy);
        }

        .product-sold {
            font-size: 11.5px;
            color: var(--ink-soft);
        }

        /* ---------- Latest list ---------- */
        .latest-list {
            background: var(--paper);
            border: 1px solid var(--line);
            border-radius: var(--radius);
            overflow: hidden;
        }

        .latest-row {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 14px 20px;
            border-bottom: 1px solid var(--line);
            color: var(--ink);
        }

        .latest-row:last-child { border-bottom: none; }
        .latest-row:hover { background: #FAFBFE; }

        .latest-row .rank {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 20px;
            font-weight: 700;
            color: var(--line);
            width: 30px;
            flex-shrink: 0;
        }

        .latest-row .rank-thumb {
            width: 52px;
            height: 52px;
            border-radius: 10px;
            background: #F0F2F8;
            flex-shrink: 0;
            overflow: hidden;
        }

        .latest-row .rank-thumb img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            padding: 6px;
        }

        .latest-row .rank-info {
            flex: 1;
            min-width: 0;
        }

        .latest-row .rank-name {
            font-size: 14.5px;
            font-weight: 600;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .latest-row .rank-date {
            font-size: 12px;
            color: var(--ink-soft);
        }

        .latest-row .rank-price {
            font-weight: 700;
            color: var(--navy);
            font-size: 14.5px;
            flex-shrink: 0;
        }

        .empty-note {
            padding: 40px 20px;
            text-align: center;
            color: var(--ink-soft);
            font-size: 14px;
        }

        @media (max-width: 992px) {
            .product-grid { grid-template-columns: repeat(2, 1fr); }
        }

        @media (max-width: 576px) {
            .hero-caption { padding: 26px 24px; }
            .hero-caption h2 { font-size: 24px; }
        }
    </style>
</head>

<body>
    <?php include('./views/components/navbar.php'); ?>

    <!-- ============ HERO / BANNERS ============ -->
    <div class="container hero">
        <?php if (!empty($banners)) : ?>
            <div id="heroCarousel" class="carousel slide hero-slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <?php foreach ($banners as $i => $banner) : ?>
                        <div class="carousel-item <?= $i === 0 ? 'active' : '' ?>">
                            <?php if (!empty($banner['image'])) : ?>
                                <img src="<?= htmlspecialchars($banner['image']) ?>" alt="<?= htmlspecialchars($banner['title'] ?? '') ?>">
                            <?php endif; ?>
                            <div class="hero-caption">
                                <div class="eyebrow">Ưu đãi hôm nay</div>
                                <h2><?= htmlspecialchars($banner['title'] ?? 'Khuyến mãi đặc biệt') ?></h2>
                                <a href="<?= htmlspecialchars($banner['link'] ?? '#') ?>" class="btn-hero">Xem ngay →</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php if (count($banners) > 1) : ?>
                    <div class="carousel-indicators">
                        <?php foreach ($banners as $i => $banner) : ?>
                            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="<?= $i ?>" class="<?= $i === 0 ? 'active' : '' ?>"></button>
                        <?php endforeach; ?>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon"></span>
                    </button>
                <?php endif; ?>
            </div>
        <?php else : ?>
            <div class="hero-slide">
                <div class="hero-caption">
                    <div class="eyebrow">Chào mừng bạn</div>
                    <h2>Công nghệ chính hãng, giá tốt mỗi ngày</h2>
                    <a href="#" class="btn-hero">Khám phá ngay →</a>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- ============ DANH MỤC ============ -->
    <?php if (!empty($categories)) : ?>
        <div class="container cat-strip">
            <div class="row-g">
                <?php foreach ($categories as $category) : ?>
                    <a href="/categories/<?= htmlspecialchars($category['slug']) ?>" class="cat-card">
                        <?php if (!empty($category['image'])) : ?>
                            <img src="<?= htmlspecialchars($category['image']) ?>" alt="<?= htmlspecialchars($category['name']) ?>">
                        <?php endif; ?>
                        <div class="cat-name"><?= htmlspecialchars($category['name']) ?></div>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="container">

        <!-- ============ SẢN PHẨM NỔI BẬT ============ -->
        <?php if (!empty($featuredProducts)) : ?>
            <div class="section">
                <div class="section-head">
                    <div>
                        <div class="eyebrow">Được chọn lọc</div>
                        <h2>Sản phẩm nổi bật</h2>
                    </div>
                    <a href="/products?featured=1" class="see-all">Xem tất cả →</a>
                </div>

                <div class="product-grid">
                    <?php foreach ($featuredProducts as $product) :
                        $percent = discountPercent($product['price'], $product['sale_price'] ?? null);
                    ?>
                        <a href="/products/<?= htmlspecialchars($product['slug']) ?>" class="product-card">
                            <div class="product-thumb">
                                <?php if ($percent > 0) : ?>
                                    <span class="discount-tag">-<?= $percent ?>%</span>
                                <?php endif; ?>
                                <img src="<?= htmlspecialchars($product['thumbnail'] ?? '') ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                            </div>
                            <div class="product-body">
                                <div class="product-name"><?= htmlspecialchars($product['name']) ?></div>
                                <div class="product-rating">
                                    <span class="stars">★</span>
                                    <span><?= number_format((float) ($product['rating'] ?? 0), 1) ?> (<?= (int) ($product['review_count'] ?? 0) ?>)</span>
                                </div>
                                <div class="product-price-row">
                                    <?php if ($percent > 0) : ?>
                                        <span class="price-now"><?= formatVnd($product['sale_price']) ?></span>
                                        <span class="price-old"><?= formatVnd($product['price']) ?></span>
                                    <?php else : ?>
                                        <span class="price-normal"><?= formatVnd($product['price']) ?></span>
                                    <?php endif; ?>
                                </div>
                                <div class="product-sold">Đã bán <?= (int) ($product['sold'] ?? 0) ?></div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- ============ TOP 5 SẢN PHẨM MỚI NHẤT ============ -->
        <div class="section pb-5">
            <div class="section-head">
                <div>
                    <div class="eyebrow">Vừa lên kệ</div>
                    <h2>Top 5 sản phẩm mới nhất</h2>
                </div>
                <a href="/products?sort=newest" class="see-all">Xem tất cả →</a>
            </div>

            <?php if (!empty($top5ProductLatest)) : ?>
                <div class="latest-list">
                    <?php foreach ($top5ProductLatest as $index => $product) :
                        $percent = discountPercent($product['price'], $product['sale_price'] ?? null);
                        $displayPrice = $percent > 0 ? $product['sale_price'] : $product['price'];
                    ?>
                        <a href="/products/<?= htmlspecialchars($product['slug']) ?>" class="latest-row">
                            <div class="rank"><?= str_pad($index + 1, 2, '0', STR_PAD_LEFT) ?></div>
                            <div class="rank-thumb">
                                <img src="<?= htmlspecialchars($product['thumbnail'] ?? '') ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                            </div>
                            <div class="rank-info">
                                <div class="rank-name"><?= htmlspecialchars($product['name']) ?></div>
                                <?php if (!empty($product['created_at'])) : ?>
                                    <div class="rank-date">Đăng ngày <?= date('d/m/Y', strtotime($product['created_at'])) ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="rank-price"><?= formatVnd($displayPrice) ?></div>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php else : ?>
                <div class="latest-list">
                    <div class="empty-note">Chưa có sản phẩm mới nào được đăng.</div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>