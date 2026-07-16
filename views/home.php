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
if (!function_exists('formatVnd')) {
    function formatVnd($value)
    {
        return number_format((float) $value, 0, ',', '.') . 'đ';
    }
}

// % giảm giá nếu có sale_price
if (!function_exists('discountPercent')) {
    function discountPercent($price, $salePrice)
    {
        if (empty($salePrice) || $salePrice >= $price) {
            return 0;
        }
        return (int) round((($price - $salePrice) / $price) * 100);
    }
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang chủ - Cửa hàng Công nghệ Hiện đại</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        :root {
            /* Bảng màu trẻ trung, tràn đầy năng lượng và cao cấp */
            --primary-grad: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            --secondary-grad: linear-gradient(135deg, #06b6d4 0%, #3b82f6 100%);
            --coral-grad: linear-gradient(135deg, #ff4e50 0%, #f9d423 100%);
            --accent-glow: rgba(124, 58, 237, 0.15);
            
            --navy: #0f172a; /* Slate 900 */
            --navy-soft: #1e293b; /* Slate 800 */
            --electric: #6366f1; /* Indigo 500 */
            --electric-dark: #4f46e5; /* Indigo 600 */
            --coral: #ef4444; /* Red 500 */
            --mint: #10b981; /* Emerald 500 */
            --bg: #f8fafc; /* Slate 50 */
            --paper: #ffffff;
            --ink: #0f172a;
            --ink-soft: #64748b; /* Slate 500 */
            --line: #f1f5f9; /* Slate 100 */
            --radius-lg: 24px;
            --radius-md: 16px;
            --radius-sm: 12px;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * { 
            box-sizing: border-box; 
        }

        body {
            background: var(--bg);
            color: var(--ink);
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
            margin: 0;
            overflow-x: hidden;
        }

        h1, h2, h3, .display, .section-head h2 {
            font-family: 'Space Grotesk', system-ui, sans-serif;
            font-weight: 700;
        }

        a { 
            text-decoration: none; 
            transition: var(--transition);
        }

        .container { 
            max-width: 1200px; 
            padding-left: 20px;
            padding-right: 20px;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: var(--bg);
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* ---------- Hero / banner ---------- */
        .hero {
            padding: 30px 0 15px;
        }

        .hero-carousel-container {
            border-radius: var(--radius-lg);
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(15, 23, 42, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.6);
            background: var(--navy);
        }

        .hero-slide {
            min-height: 420px;
            display: flex;
            align-items: center;
            position: relative;
        }

        .hero-slide img {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0.65;
            transition: transform 6s ease;
        }

        .carousel-item.active .hero-slide img {
            transform: scale(1.05);
        }

        /* Lớp phủ gradient hỗ trợ hiển thị văn bản rõ nét */
        .hero-slide::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg, rgba(15, 23, 42, 0.95) 0%, rgba(15, 23, 42, 0.6) 50%, rgba(15, 23, 42, 0.1) 100%);
            z-index: 1;
        }

        .hero-slide .hero-caption {
            position: relative;
            z-index: 2;
            padding: 60px 80px;
            color: #fff;
            max-width: 600px;
        }

        .hero-caption .eyebrow {
            display: inline-block;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: #38bdf8;
            background: rgba(56, 189, 248, 0.15);
            padding: 6px 16px;
            border-radius: 100px;
            margin-bottom: 20px;
            border: 1px solid rgba(56, 189, 248, 0.25);
            backdrop-filter: blur(4px);
            animation: fadeInUp 0.6s ease both;
        }

        .hero-caption h2 {
            font-size: 42px;
            font-weight: 700;
            margin: 0 0 20px;
            line-height: 1.15;
            animation: fadeInUp 0.8s ease both 0.1s;
        }

        .hero-caption .btn-hero {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: var(--primary-grad);
            color: #fff;
            font-weight: 700;
            padding: 14px 28px;
            border-radius: var(--radius-sm);
            font-size: 15px;
            box-shadow: 0 10px 20px rgba(79, 70, 229, 0.3);
            border: none;
            cursor: pointer;
            animation: fadeInUp 1s ease both 0.2s;
        }

        .hero-caption .btn-hero:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 25px rgba(79, 70, 229, 0.4);
            color: #fff;
        }

        .hero-caption .btn-hero svg {
            transition: transform 0.2s ease;
        }

        .hero-caption .btn-hero:hover svg {
            transform: translateX(4px);
        }

        /* Carousel controls */
        .carousel-indicators {
            margin-bottom: 24px;
            z-index: 3;
        }

        .carousel-indicators button {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            margin: 0 6px;
            background-color: rgba(255, 255, 255, 0.4);
            border: none;
            transition: var(--transition);
        }

        .carousel-indicators .active {
            background-color: #fff;
            width: 24px;
            border-radius: 5px;
        }

        .carousel-control-prev, .carousel-control-next {
            width: 50px;
            height: 50px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            top: 50%;
            transform: translateY(-50%);
            margin: 0 20px;
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            z-index: 3;
        }

        .carousel-control-prev:hover, .carousel-control-next:hover {
            background: rgba(255, 255, 255, 0.25);
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ---------- Category strip ---------- */
        .cat-strip {
            padding: 30px 0 15px;
        }

        .cat-strip .cat-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(130px, 1fr));
            gap: 16px;
        }

        .cat-card {
            background: var(--paper);
            border: 1px solid rgba(226, 232, 240, 0.7);
            border-radius: var(--radius-md);
            padding: 22px 14px;
            text-align: center;
            color: var(--ink);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02), 0 2px 4px -1px rgba(0, 0, 0, 0.006);
        }

        .cat-card:hover {
            transform: translateY(-5px);
            border-color: transparent;
            box-shadow: 0 16px 24px rgba(99, 102, 241, 0.08);
            background: linear-gradient(to bottom, #ffffff, #fcfdff);
            color: var(--electric-dark);
        }

        .cat-card .icon-wrapper {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: var(--bg);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 14px;
            transition: var(--transition);
        }

        .cat-card:hover .icon-wrapper {
            background: var(--accent-glow);
            transform: scale(1.1) rotate(5deg);
        }

        .cat-card img {
            width: 32px;
            height: 32px;
            object-fit: contain;
        }

        .cat-card .cat-name {
            font-size: 14px;
            font-weight: 700;
            letter-spacing: -0.2px;
        }

        /* ---------- Section heading ---------- */
        .section {
            padding: 35px 0 15px;
        }

        .section-head {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-bottom: 28px;
        }

        .section-head .eyebrow {
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: var(--electric);
            margin-bottom: 6px;
        }

        .section-head h2 {
            font-size: 28px;
            font-weight: 700;
            margin: 0;
            letter-spacing: -0.5px;
            color: var(--navy);
        }

        .section-head .see-all {
            font-size: 14px;
            font-weight: 700;
            color: var(--electric);
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .section-head .see-all:hover {
            color: var(--electric-dark);
        }

        .section-head .see-all svg {
            transition: transform 0.2s ease;
        }

        .section-head .see-all:hover svg {
            transform: translateX(4px);
        }

        /* ---------- Product grid ---------- */
        .product-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 24px;
        }

        .product-card {
            background: var(--paper);
            border: 1px solid rgba(226, 232, 240, 0.7);
            border-radius: var(--radius-md);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            color: var(--ink);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02);
            position: relative;
        }

        .product-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 30px rgba(15, 23, 42, 0.06);
            border-color: rgba(99, 102, 241, 0.2);
            color: var(--ink);
        }

        .product-thumb {
            aspect-ratio: 1 / 1;
            background: radial-gradient(circle at 50% 50%, #ffffff 0%, #f8fafc 100%);
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .product-thumb img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            padding: 24px;
            transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .product-card:hover .product-thumb img {
            transform: scale(1.08);
        }

        .discount-tag {
            position: absolute;
            top: 14px;
            left: 14px;
            background: var(--coral-grad);
            color: #fff;
            font-size: 11px;
            font-weight: 800;
            padding: 4px 10px;
            border-radius: 30px;
            box-shadow: 0 4px 10px rgba(239, 68, 68, 0.25);
            z-index: 2;
        }

        /* Hover Overlay Effect */
        .product-action-overlay {
            position: absolute;
            inset: 0;
            background: rgba(15, 23, 42, 0.03);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: var(--transition);
            z-index: 1;
        }

        .product-card:hover .product-action-overlay {
            opacity: 1;
        }

        .btn-quick-view {
            background: #ffffff;
            color: var(--navy);
            font-weight: 700;
            font-size: 12px;
            padding: 10px 18px;
            border-radius: 100px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            transform: translateY(15px);
            transition: var(--transition);
        }

        .product-card:hover .btn-quick-view {
            transform: translateY(0);
        }

        .btn-quick-view:hover {
            background: var(--navy);
            color: #ffffff;
        }

        .product-body {
            padding: 18px 20px 20px;
            display: flex;
            flex-direction: column;
            gap: 8px;
            flex: 1;
            border-top: 1px solid var(--line);
        }

        .product-name {
            font-size: 15px;
            font-weight: 700;
            line-height: 1.4;
            min-height: 42px;
            color: var(--navy);
            /* Giới hạn 2 dòng */
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .product-rating {
            font-size: 12.5px;
            color: var(--ink-soft);
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .product-rating .stars { 
            color: #f59e0b; /* Amber 500 */
            display: flex;
            gap: 2px;
        }

        .product-price-row {
            margin-top: auto;
            display: flex;
            align-items: baseline;
            gap: 8px;
            flex-wrap: wrap;
        }

        .price-now {
            font-size: 18px;
            font-weight: 800;
            color: var(--coral);
            letter-spacing: -0.5px;
        }

        .price-old {
            font-size: 13px;
            color: var(--ink-soft);
            text-decoration: line-through;
        }

        .price-normal {
            font-size: 18px;
            font-weight: 800;
            color: var(--navy);
            letter-spacing: -0.5px;
        }

        .product-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 6px;
            padding-top: 12px;
            border-top: 1px dashed var(--line);
        }

        .product-sold {
            font-size: 12px;
            font-weight: 500;
            color: var(--ink-soft);
            background: #f1f5f9;
            padding: 3px 10px;
            border-radius: 100px;
        }

        /* ---------- Latest list (Leaderboard Style) ---------- */
        .latest-container {
            background: var(--paper);
            border: 1px solid rgba(226, 232, 240, 0.7);
            border-radius: var(--radius-lg);
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.03);
        }

        .latest-row {
            display: flex;
            align-items: center;
            gap: 20px;
            padding: 18px 24px;
            border-bottom: 1px solid var(--line);
            color: var(--ink);
        }

        .latest-row:last-child { 
            border-bottom: none; 
        }

        .latest-row:hover { 
            background: #fafbfe; 
        }

        .latest-row .rank {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 22px;
            font-weight: 700;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            transition: var(--transition);
        }

        /* Huy hiệu xếp hạng Top 3 */
        .latest-row:nth-child(1) .rank {
            background: linear-gradient(135deg, #ffd700 0%, #ffa500 100%);
            color: #ffffff;
            box-shadow: 0 4px 10px rgba(255, 165, 0, 0.25);
        }
        .latest-row:nth-child(2) .rank {
            background: linear-gradient(135deg, #e2e8f0 0%, #94a3b8 100%);
            color: #ffffff;
            box-shadow: 0 4px 10px rgba(148, 163, 184, 0.25);
        }
        .latest-row:nth-child(3) .rank {
            background: linear-gradient(135deg, #cd7f32 0%, #b87333 100%);
            color: #ffffff;
            box-shadow: 0 4px 10px rgba(184, 115, 51, 0.25);
        }
        .latest-row:nth-child(n+4) .rank {
            background: #f1f5f9;
            color: var(--ink-soft);
        }

        .latest-row .rank-thumb {
            width: 60px;
            height: 60px;
            border-radius: var(--radius-sm);
            background: radial-gradient(circle at 50% 50%, #ffffff 0%, #f8fafc 100%);
            flex-shrink: 0;
            overflow: hidden;
            border: 1px solid rgba(226, 232, 240, 0.7);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .latest-row .rank-thumb img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            padding: 8px;
            transition: var(--transition);
        }

        .latest-row:hover .rank-thumb img {
            transform: scale(1.1);
        }

        .latest-row .rank-info {
            flex: 1;
            min-width: 0;
        }

        .latest-row .rank-name {
            font-size: 15px;
            font-weight: 700;
            color: var(--navy);
            margin-bottom: 4px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .latest-row .rank-date {
            font-size: 12.5px;
            color: var(--ink-soft);
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .latest-row .rank-price {
            font-weight: 800;
            color: var(--navy);
            font-size: 16px;
            flex-shrink: 0;
            background: rgba(15, 23, 42, 0.04);
            padding: 6px 14px;
            border-radius: 100px;
            transition: var(--transition);
        }

        .latest-row:hover .rank-price {
            background: var(--navy);
            color: #ffffff;
        }

        .empty-note {
            padding: 60px 20px;
            text-align: center;
            color: var(--ink-soft);
            font-size: 15px;
        }

        @media (max-width: 992px) {
            .product-grid { 
                grid-template-columns: repeat(2, 1fr); 
                gap: 16px;
            }
            .hero-slide {
                min-height: 350px;
            }
            .hero-slide .hero-caption {
                padding: 40px;
            }
            .hero-caption h2 {
                font-size: 32px;
            }
        }

        @media (max-width: 768px) {
            .cat-strip .cat-grid {
                grid-template-columns: repeat(3, 1fr);
            }
            .latest-row {
                padding: 14px 16px;
                gap: 12px;
            }
            .latest-row .rank {
                width: 28px;
                height: 28px;
                font-size: 16px;
            }
            .latest-row .rank-thumb {
                width: 48px;
                height: 48px;
            }
            .latest-row .rank-price {
                font-size: 14px;
                padding: 4px 10px;
            }
        }

        @media (max-width: 576px) {
            .hero-slide {
                min-height: 300px;
            }
            .hero-slide .hero-caption {
                padding: 30px 20px;
            }
            .hero-caption h2 {
                font-size: 26px;
            }
            .cat-strip .cat-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            .product-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 12px;
            }
            .product-body {
                padding: 12px;
            }
            .product-name {
                font-size: 13.5px;
                min-height: 38px;
            }
            .price-now, .price-normal {
                font-size: 15px;
            }
            .section-head h2 {
                font-size: 22px;
            }
        }
    </style>
</head>

<body>
    <?php include('./views/components/navbar.php'); ?>

    <!-- ============ HERO / BANNERS ============ -->
    <div class="container hero">
        <div class="hero-carousel-container">
            <?php if (!empty($banners)) : ?>
                <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <?php foreach ($banners as $i => $banner) : ?>
                            <div class="carousel-item <?= $i === 0 ? 'active' : '' ?>">
                                <div class="hero-slide">
                                    <?php if (!empty($banner['image'])) : ?>
                                        <img src="<?= htmlspecialchars($banner['image']) ?>" alt="<?= htmlspecialchars($banner['title'] ?? '') ?>">
                                    <?php endif; ?>
                                    <div class="hero-caption">
                                        <div class="eyebrow">Ưu đãi độc quyền</div>
                                        <h2><?= htmlspecialchars($banner['title'] ?? 'Khuyến mãi đặc biệt') ?></h2>
                                        <a href="<?= htmlspecialchars($banner['link'] ?? '#') ?>" class="btn-hero">
                                            Khám phá ngay 
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                                        </a>
                                    </div>
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
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
                        </button>
                    <?php endif; ?>
                </div>
            <?php else : ?>
                <div class="hero-slide">
                    <div class="hero-caption">
                        <div class="eyebrow">Welcome to Store</div>
                        <h2>Công nghệ đỉnh cao, kiến tạo tương lai</h2>
                        <a href="#" class="btn-hero">
                            Trải nghiệm ngay
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- ============ DANH MỤC ============ -->
    <?php if (!empty($categories)) : ?>
        <div class="container cat-strip">
            <div class="cat-grid">
                <?php foreach ($categories as $category) : ?>
                    <a href="/categories/<?= htmlspecialchars($category['slug']) ?>" class="cat-card">
                        <div class="icon-wrapper">
                            <?php if (!empty($category['image'])) : ?>
                                <img src="<?= htmlspecialchars($category['image']) ?>" alt="<?= htmlspecialchars($category['name']) ?>">
                            <?php else : ?>
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--electric)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="9"></rect><rect x="14" y="3" width="7" height="5"></rect><rect x="14" y="12" width="7" height="9"></rect><rect x="3" y="16" width="7" height="5"></rect></svg>
                            <?php endif; ?>
                        </div>
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
                        <div class="eyebrow">Xu hướng hiện nay</div>
                        <h2>Sản phẩm nổi bật</h2>
                    </div>
                    <a href="/products?featured=1" class="see-all">
                        Xem tất cả
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                    </a>
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
                                <div class="product-action-overlay">
                                    <span class="btn-quick-view">Xem chi tiết</span>
                                </div>
                            </div>
                            <div class="product-body">
                                <div class="product-name"><?= htmlspecialchars($product['name']) ?></div>
                                <div class="product-rating">
                                    <span class="stars">
                                        <?php 
                                        $rating = (float)($product['rating'] ?? 0);
                                        for ($star = 1; $star <= 5; $star++) {
                                            if ($star <= $rating) {
                                                echo '★';
                                            } else {
                                                echo '☆';
                                            }
                                        }
                                        ?>
                                    </span>
                                    <span><?= number_format($rating, 1) ?> (<?= (int) ($product['review_count'] ?? 0) ?>)</span>
                                </div>
                                <div class="product-price-row">
                                    <?php if ($percent > 0) : ?>
                                        <span class="price-now"><?= formatVnd($product['sale_price']) ?></span>
                                        <span class="price-old"><?= formatVnd($product['price']) ?></span>
                                    <?php else : ?>
                                        <span class="price-normal"><?= formatVnd($product['price']) ?></span>
                                    <?php endif; ?>
                                </div>
                                <div class="product-footer">
                                    <div class="product-sold">Đã bán <?= (int) ($product['sold'] ?? 0) ?></div>
                                </div>
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
                    <div class="eyebrow">Hàng mới cập bến</div>
                    <h2>Top 5 sản phẩm mới nhất</h2>
                </div>
                <a href="/products?sort=newest" class="see-all">
                    Xem tất cả
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                </a>
            </div>

            <?php if (!empty($top5ProductLatest)) : ?>
                <div class="latest-container">
                    <?php foreach ($top5ProductLatest as $index => $product) :
                        $percent = discountPercent($product['price'], $product['sale_price'] ?? null);
                        $displayPrice = $percent > 0 ? $product['sale_price'] : $product['price'];
                    ?>
                        <a href="/products/<?= htmlspecialchars($product['slug']) ?>" class="latest-row">
                            <div class="rank"><?= $index + 1 ?></div>
                            <div class="rank-thumb">
                                <img src="<?= htmlspecialchars($product['thumbnail'] ?? '') ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                            </div>
                            <div class="rank-info">
                                <div class="rank-name"><?= htmlspecialchars($product['name']) ?></div>
                                <?php if (!empty($product['created_at'])) : ?>
                                    <div class="rank-date">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                                        Đăng ngày <?= date('d/m/Y', strtotime($product['created_at'])) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="rank-price"><?= formatVnd($displayPrice) ?></div>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php else : ?>
                <div class="latest-container">
                    <div class="empty-note">Chưa có sản phẩm mới nào được đăng.</div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>