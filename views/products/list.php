<?php
$isCategoryPage = !empty($category);
$selectedSort = $_GET['sort'] ?? 'newest';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle) ?> - Tech Store</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #4f46e5;
            --primary-soft: #eef2ff;
            --ink: #0f172a;
            --muted: #64748b;
            --line: #e7edf5;
            --paper: #fff;
            --bg: #f7f9fc;
            --danger: #ef4444;
        }
        * { box-sizing: border-box; }
        body { margin: 0; background: var(--bg); color: var(--ink); font-family: 'Plus Jakarta Sans', system-ui, sans-serif; }
        a { color: inherit; }
        .catalog-shell { width: min(1180px, calc(100% - 32px)); margin: 0 auto; padding: 28px 0 70px; }
        .breadcrumb { display: flex; gap: 8px; align-items: center; margin-bottom: 22px; color: var(--muted); font-size: 13px; }
        .breadcrumb a { text-decoration: none; }
        .breadcrumb a:hover { color: var(--primary); }
        .catalog-hero {
            position: relative;
            overflow: hidden;
            padding: 38px 40px;
            border-radius: 24px;
            color: #fff;
            background: linear-gradient(120deg, #111827 0%, #3730a3 60%, #6366f1 100%);
            box-shadow: 0 20px 50px rgba(49, 46, 129, .18);
        }
        .catalog-hero::after {
            content: '';
            position: absolute;
            width: 260px;
            height: 260px;
            right: -70px;
            top: -120px;
            border-radius: 50%;
            border: 48px solid rgba(255, 255, 255, .08);
        }
        .catalog-hero__eyebrow { margin-bottom: 8px; color: #c7d2fe; font-size: 12px; font-weight: 800; letter-spacing: 1.4px; text-transform: uppercase; }
        .catalog-hero h1 { position: relative; z-index: 1; margin: 0; font-size: clamp(28px, 4vw, 44px); letter-spacing: -1.4px; }
        .catalog-hero p { position: relative; z-index: 1; max-width: 650px; margin: 12px 0 0; color: #e0e7ff; line-height: 1.7; }
        .category-tabs { display: flex; gap: 10px; margin: 26px 0; overflow-x: auto; padding-bottom: 3px; }
        .category-tab { display: inline-flex; align-items: center; gap: 8px; min-height: 42px; padding: 0 16px; border: 1px solid var(--line); border-radius: 12px; background: var(--paper); color: #475569; font-size: 13px; font-weight: 700; text-decoration: none; white-space: nowrap; }
        .category-tab img { width: 24px; height: 24px; object-fit: contain; }
        .category-tab:hover, .category-tab.is-active { color: var(--primary); border-color: #c7d2fe; background: var(--primary-soft); }
        .catalog-toolbar { display: flex; align-items: center; justify-content: space-between; gap: 18px; margin: 28px 0 18px; }
        .catalog-toolbar h2 { margin: 0; font-size: 20px; }
        .catalog-toolbar p { margin: 5px 0 0; color: var(--muted); font-size: 13px; }
        .sort-form select { min-height: 42px; padding: 0 38px 0 13px; border: 1px solid var(--line); border-radius: 11px; background: #fff; color: #334155; font: inherit; font-size: 13px; font-weight: 600; cursor: pointer; }
        .product-grid { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 20px; }
        .catalog-card { position: relative; overflow: hidden; display: flex; flex-direction: column; border: 1px solid var(--line); border-radius: 18px; background: var(--paper); text-decoration: none; transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease; }
        .catalog-card:hover { transform: translateY(-5px); border-color: #c7d2fe; box-shadow: 0 18px 35px rgba(15, 23, 42, .09); }
        .catalog-card__image { position: relative; display: grid; place-items: center; aspect-ratio: 1 / 1; padding: 24px; background: linear-gradient(145deg, #fff, #f7f9fc); }
        .catalog-card__image img { width: 100%; height: 100%; object-fit: contain; transition: transform .3s ease; }
        .catalog-card:hover .catalog-card__image img { transform: scale(1.05); }
        .discount { position: absolute; top: 14px; left: 14px; padding: 5px 9px; border-radius: 999px; background: #fee2e2; color: #dc2626; font-size: 11px; font-weight: 800; }
        .catalog-card__body { display: flex; flex: 1; flex-direction: column; padding: 17px; border-top: 1px solid #f0f3f8; }
        .catalog-card__meta { margin-bottom: 7px; color: var(--primary); font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: .7px; }
        .catalog-card__name { min-height: 44px; margin: 0; font-size: 15px; line-height: 1.45; }
        .rating { display: flex; align-items: center; gap: 6px; margin: 10px 0; color: var(--muted); font-size: 12px; }
        .rating__stars { color: #f59e0b; letter-spacing: 1px; }
        .price { display: flex; align-items: baseline; gap: 8px; margin-top: auto; flex-wrap: wrap; }
        .price__current { color: var(--danger); font-size: 17px; font-weight: 800; }
        .price__old { color: #94a3b8; font-size: 12px; text-decoration: line-through; }
        .stock { margin-top: 12px; padding-top: 11px; border-top: 1px dashed var(--line); color: #059669; font-size: 12px; font-weight: 700; }
        .stock.is-empty { color: #dc2626; }
        .empty-state { grid-column: 1 / -1; padding: 70px 24px; border: 1px dashed #cbd5e1; border-radius: 20px; background: #fff; text-align: center; }
        .empty-state__icon { font-size: 42px; }
        .empty-state h3 { margin: 14px 0 8px; }
        .empty-state p { margin: 0; color: var(--muted); }
        @media (max-width: 980px) { .product-grid { grid-template-columns: repeat(3, 1fr); } }
        @media (max-width: 720px) {
            .catalog-shell { width: min(100% - 24px, 1180px); padding-top: 18px; }
            .catalog-hero { padding: 28px 24px; border-radius: 18px; }
            .catalog-toolbar { align-items: flex-start; flex-direction: column; }
            .sort-form, .sort-form select { width: 100%; }
            .product-grid { grid-template-columns: repeat(2, 1fr); gap: 12px; }
            .catalog-card__image { padding: 15px; }
            .catalog-card__body { padding: 13px; }
        }
        @media (max-width: 420px) { .catalog-card__name { font-size: 13px; } .price__current { font-size: 14px; } }
    </style>
</head>
<body>
    <?php include './views/components/navbar.php'; ?>

    <main class="catalog-shell">
        <nav class="breadcrumb" aria-label="Đường dẫn">
            <a href="<?= BASE_URL ?>">Trang chủ</a>
            <span>/</span>
            <span><?= e($pageTitle) ?></span>
        </nav>

        <section class="catalog-hero">
            <div class="catalog-hero__eyebrow"><?= $isCategoryPage ? 'Danh mục sản phẩm' : 'Cửa hàng công nghệ' ?></div>
            <h1><?= e($pageTitle) ?></h1>
            <p>
                <?php if ($isCategoryPage) : ?>
                    Khám phá các sản phẩm <?= e(mb_strtolower($category['name'], 'UTF-8')) ?> mới nhất, xem giá bán và mở từng sản phẩm để xem đầy đủ thông số.
                <?php else : ?>
                    Khám phá điện thoại, laptop và các sản phẩm công nghệ đang có tại cửa hàng.
                <?php endif; ?>
            </p>
        </section>

        <nav class="category-tabs" aria-label="Danh mục sản phẩm">
            <a class="category-tab <?= !$isCategoryPage ? 'is-active' : '' ?>" href="<?= BASE_URL ?>?act=products">Tất cả</a>
            <?php foreach ($categories as $item) : ?>
                <a class="category-tab <?= $isCategoryPage && $category['id'] == $item['id'] ? 'is-active' : '' ?>" href="<?= BASE_URL ?>?act=category&amp;slug=<?= urlencode($item['slug']) ?>">
                    <?php if (!empty($item['image'])) : ?>
                        <img src="<?= e($item['image']) ?>" alt="">
                    <?php endif; ?>
                    <?= e($item['name']) ?>
                </a>
            <?php endforeach; ?>
        </nav>

        <div class="catalog-toolbar">
            <div>
                <h2>Danh sách sản phẩm</h2>
                <p>Tìm thấy <?= count($products) ?> sản phẩm</p>
            </div>
            <form class="sort-form" method="get" action="<?= BASE_URL ?>">
                <input type="hidden" name="act" value="<?= $isCategoryPage ? 'category' : 'products' ?>">
                <?php if ($isCategoryPage) : ?>
                    <input type="hidden" name="slug" value="<?= e($category['slug']) ?>">
                <?php endif; ?>
                <?php if (($_GET['featured'] ?? '') === '1') : ?>
                    <input type="hidden" name="featured" value="1">
                <?php endif; ?>
                <select name="sort" aria-label="Sắp xếp sản phẩm" onchange="this.form.submit()">
                    <option value="newest" <?= $selectedSort === 'newest' ? 'selected' : '' ?>>Mới nhất</option>
                    <option value="price-asc" <?= $selectedSort === 'price-asc' ? 'selected' : '' ?>>Giá thấp đến cao</option>
                    <option value="price-desc" <?= $selectedSort === 'price-desc' ? 'selected' : '' ?>>Giá cao đến thấp</option>
                </select>
            </form>
        </div>

        <section class="product-grid">
            <?php if (empty($products)) : ?>
                <div class="empty-state">
                    <div class="empty-state__icon">📦</div>
                    <h3>Danh mục chưa có sản phẩm</h3>
                    <p>Bạn có thể thêm sản phẩm trong trang quản trị hoặc chọn một danh mục khác.</p>
                </div>
            <?php else : ?>
                <?php foreach ($products as $item) :
                    $discount = discountPercent($item['price'], $item['sale_price'] ?? null);
                    $currentPrice = $discount > 0 ? $item['sale_price'] : $item['price'];
                    $rating = (float) ($item['rating'] ?? 0);
                ?>
                    <a class="catalog-card" href="<?= BASE_URL ?>?act=product-detail&amp;slug=<?= urlencode($item['slug']) ?>">
                        <div class="catalog-card__image">
                            <?php if ($discount > 0) : ?><span class="discount">-<?= $discount ?>%</span><?php endif; ?>
                            <img src="<?= e($item['thumbnail'] ?? '') ?>" alt="<?= e($item['name']) ?>">
                        </div>
                        <div class="catalog-card__body">
                            <div class="catalog-card__meta"><?= e($item['category_name'] ?? 'Sản phẩm') ?></div>
                            <h3 class="catalog-card__name"><?= e($item['name']) ?></h3>
                            <div class="rating">
                                <span class="rating__stars">★</span>
                                <span><?= number_format($rating, 1) ?> · <?= (int) ($item['review_count'] ?? 0) ?> đánh giá</span>
                            </div>
                            <div class="price">
                                <span class="price__current"><?= formatVnd($currentPrice) ?></span>
                                <?php if ($discount > 0) : ?><span class="price__old"><?= formatVnd($item['price']) ?></span><?php endif; ?>
                            </div>
                            <div class="stock <?= (int) $item['stock'] <= 0 ? 'is-empty' : '' ?>">
                                <?= (int) $item['stock'] > 0 ? 'Còn ' . (int) $item['stock'] . ' sản phẩm' : 'Tạm hết hàng' ?>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>
