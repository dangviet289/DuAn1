<?php
$discount = discountPercent($product['price'], $product['sale_price'] ?? null);
$displayPrice = $discount > 0 ? $product['sale_price'] : $product['price'];
$galleryImages = [];

if (!empty($product['thumbnail'])) {
    $galleryImages[] = $product['thumbnail'];
}
foreach ($images as $image) {
    if (!empty($image['image_url']) && !in_array($image['image_url'], $galleryImages, true)) {
        $galleryImages[] = $image['image_url'];
    }
}

$specLabels = [
    'screen' => 'Màn hình',
    'chip' => 'Bộ vi xử lý',
    'battery' => 'Pin',
    'camera' => 'Camera',
    'ram' => 'RAM',
    'storage' => 'Bộ nhớ',
    'graphics' => 'Card đồ họa',
    'weight' => 'Khối lượng',
    'os' => 'Hệ điều hành',
];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($product['name']) ?> - Tech Store</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root { --primary: #4f46e5; --primary-soft: #eef2ff; --ink: #0f172a; --muted: #64748b; --line: #e5eaf1; --paper: #fff; --bg: #f7f9fc; --danger: #ef4444; --success: #059669; }
        * { box-sizing: border-box; }
        body { margin: 0; color: var(--ink); background: var(--bg); font-family: 'Plus Jakarta Sans', system-ui, sans-serif; }
        a { color: inherit; }
        button, input { font: inherit; }
        .detail-shell { width: min(1180px, calc(100% - 32px)); margin: 0 auto; padding: 26px 0 70px; }
        .breadcrumb { display: flex; flex-wrap: wrap; align-items: center; gap: 8px; margin-bottom: 22px; color: var(--muted); font-size: 13px; }
        .breadcrumb a { text-decoration: none; }
        .breadcrumb a:hover { color: var(--primary); }
        .product-main { display: grid; grid-template-columns: minmax(0, 1.05fr) minmax(360px, .95fr); gap: 28px; align-items: start; }
        .panel { border: 1px solid var(--line); border-radius: 22px; background: var(--paper); box-shadow: 0 10px 30px rgba(15, 23, 42, .04); }
        .gallery { padding: 22px; }
        .gallery__main { position: relative; display: grid; place-items: center; aspect-ratio: 1 / .82; overflow: hidden; border-radius: 17px; background: linear-gradient(145deg, #fff, #f5f7fb); }
        .gallery__main img { width: 100%; height: 100%; padding: 30px; object-fit: contain; transition: opacity .15s ease; }
        .gallery__discount { position: absolute; top: 16px; left: 16px; z-index: 1; padding: 7px 11px; border-radius: 999px; color: #dc2626; background: #fee2e2; font-size: 12px; font-weight: 800; }
        .gallery__thumbs { display: grid; grid-template-columns: repeat(5, 1fr); gap: 10px; margin-top: 14px; }
        .gallery__thumb { aspect-ratio: 1; padding: 7px; overflow: hidden; border: 1px solid var(--line); border-radius: 12px; background: #fff; cursor: pointer; }
        .gallery__thumb.is-active { border: 2px solid var(--primary); background: var(--primary-soft); }
        .gallery__thumb img { width: 100%; height: 100%; object-fit: contain; }
        .product-info { padding: 28px; }
        .product-info__meta { display: flex; flex-wrap: wrap; align-items: center; gap: 8px; margin-bottom: 14px; }
        .pill { display: inline-flex; align-items: center; min-height: 28px; padding: 0 10px; border-radius: 999px; background: var(--primary-soft); color: var(--primary); font-size: 11px; font-weight: 800; }
        .product-info h1 { margin: 0; font-size: clamp(25px, 3vw, 36px); line-height: 1.2; letter-spacing: -1px; }
        .rating { display: flex; align-items: center; gap: 9px; margin: 15px 0 20px; color: var(--muted); font-size: 13px; }
        .rating__stars { color: #f59e0b; letter-spacing: 1px; }
        .price-row { display: flex; align-items: baseline; gap: 12px; flex-wrap: wrap; padding: 18px 20px; border-radius: 15px; background: #f8fafc; }
        .price-current { color: var(--danger); font-size: 28px; font-weight: 800; letter-spacing: -.8px; }
        .price-old { color: #94a3b8; font-size: 15px; text-decoration: line-through; }
        .price-save { color: #dc2626; font-size: 12px; font-weight: 800; }
        .short-description { margin: 20px 0; color: #475569; font-size: 14px; line-height: 1.75; }
        .section-label { margin: 20px 0 10px; font-size: 13px; font-weight: 800; }
        .variant-list { display: grid; gap: 9px; }
        .variant-card { display: flex; align-items: center; justify-content: space-between; gap: 14px; width: 100%; padding: 12px 14px; border: 1px solid var(--line); border-radius: 12px; color: #334155; background: #fff; text-align: left; cursor: pointer; }
        .variant-card:hover, .variant-card.is-active { color: var(--primary); border-color: #a5b4fc; background: var(--primary-soft); }
        .variant-card strong { display: block; font-size: 13px; }
        .variant-card small { display: block; margin-top: 3px; color: var(--muted); font-size: 11px; }
        .variant-card__price { font-size: 13px; font-weight: 800; white-space: nowrap; }
        .availability { display: flex; align-items: center; gap: 10px; margin-top: 18px; padding: 13px 15px; border-radius: 12px; color: var(--success); background: #ecfdf5; font-size: 13px; font-weight: 700; }
        .availability.is-empty { color: #dc2626; background: #fef2f2; }
        .detail-actions { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-top: 16px; }
        .detail-action { min-height: 46px; display: inline-flex; align-items: center; justify-content: center; border: 1px solid var(--primary); border-radius: 12px; color: var(--primary); background: #fff; text-decoration: none; font-size: 13px; font-weight: 800; }
        .detail-action.is-primary { color: #fff; background: var(--primary); }
        .detail-action:hover { filter: brightness(.96); }
        .content-grid { display: grid; grid-template-columns: 1.15fr .85fr; gap: 24px; margin-top: 26px; align-items: start; }
        .content-panel { padding: 26px; }
        .content-panel h2 { margin: 0 0 18px; font-size: 21px; }
        .description { color: #475569; line-height: 1.8; font-size: 14px; }
        .spec-table { width: 100%; border-collapse: collapse; overflow: hidden; border-radius: 14px; }
        .spec-table tr { border-bottom: 1px solid var(--line); }
        .spec-table tr:last-child { border-bottom: 0; }
        .spec-table th, .spec-table td { padding: 13px 14px; text-align: left; font-size: 13px; line-height: 1.5; }
        .spec-table th { width: 38%; color: #475569; background: #f8fafc; font-weight: 700; }
        .spec-table td { color: var(--ink); background: #fff; }
        .related { margin-top: 34px; }
        .related__head { display: flex; align-items: end; justify-content: space-between; gap: 16px; margin-bottom: 18px; }
        .related__head h2 { margin: 0; font-size: 23px; }
        .related__head a { color: var(--primary); font-size: 13px; font-weight: 800; text-decoration: none; }
        .related-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 18px; }
        .related-card { overflow: hidden; border: 1px solid var(--line); border-radius: 17px; background: #fff; text-decoration: none; transition: transform .2s, box-shadow .2s; }
        .related-card:hover { transform: translateY(-4px); box-shadow: 0 15px 30px rgba(15, 23, 42, .08); }
        .related-card__image { aspect-ratio: 1; padding: 20px; background: linear-gradient(145deg, #fff, #f7f9fc); }
        .related-card__image img { width: 100%; height: 100%; object-fit: contain; }
        .related-card__body { padding: 14px; border-top: 1px solid #f0f3f8; }
        .related-card h3 { min-height: 40px; margin: 0 0 10px; font-size: 14px; line-height: 1.45; }
        .related-card__price { color: var(--danger); font-size: 15px; font-weight: 800; }
        .empty-copy { color: var(--muted); font-size: 14px; }
        @media (max-width: 900px) {
            .product-main, .content-grid { grid-template-columns: 1fr; }
            .related-grid { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 560px) {
            .detail-shell { width: calc(100% - 24px); padding-top: 18px; }
            .gallery, .product-info, .content-panel { padding: 16px; }
            .gallery__main { aspect-ratio: 1; }
            .gallery__main img { padding: 16px; }
            .gallery__thumbs { grid-template-columns: repeat(4, 1fr); }
            .detail-actions { grid-template-columns: 1fr; }
            .price-current { font-size: 23px; }
            .related-grid { gap: 12px; }
        }
    </style>
</head>
<body>
    <?php include './views/components/navbar.php'; ?>

    <main class="detail-shell">
        <nav class="breadcrumb" aria-label="Đường dẫn">
            <a href="<?= BASE_URL ?>">Trang chủ</a>
            <span>/</span>
            <a href="<?= BASE_URL ?>?act=category&amp;slug=<?= urlencode($product['category_slug']) ?>"><?= e($product['category_name']) ?></a>
            <span>/</span>
            <span><?= e($product['name']) ?></span>
        </nav>

        <section class="product-main">
            <div class="panel gallery">
                <div class="gallery__main">
                    <?php if ($discount > 0) : ?><span class="gallery__discount">Giảm <?= $discount ?>%</span><?php endif; ?>
                    <img id="mainProductImage" src="<?= e($galleryImages[0] ?? '') ?>" alt="<?= e($product['name']) ?>">
                </div>
                <?php if (count($galleryImages) > 1) : ?>
                    <div class="gallery__thumbs">
                        <?php foreach ($galleryImages as $index => $imageUrl) : ?>
                            <button class="gallery__thumb <?= $index === 0 ? 'is-active' : '' ?>" type="button" data-image="<?= e($imageUrl) ?>" aria-label="Xem ảnh <?= $index + 1 ?>">
                                <img src="<?= e($imageUrl) ?>" alt="">
                            </button>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="panel product-info" id="product-info">
                <div class="product-info__meta">
                    <span class="pill"><?= e($product['category_name']) ?></span>
                    <?php if (!empty($product['brand_name'])) : ?><span class="pill"><?= e($product['brand_name']) ?></span><?php endif; ?>
                    <span class="pill">Mã SP #<?= (int) $product['id'] ?></span>
                </div>
                <h1><?= e($product['name']) ?></h1>
                <div class="rating">
                    <span class="rating__stars">★★★★★</span>
                    <strong><?= number_format((float) ($product['rating'] ?? 0), 1) ?></strong>
                    <span><?= (int) ($product['review_count'] ?? 0) ?> đánh giá · Đã bán <?= (int) ($product['sold'] ?? 0) ?></span>
                </div>

                <div class="price-row">
                    <span class="price-current" id="displayPrice"><?= formatVnd($displayPrice) ?></span>
                    <?php if ($discount > 0) : ?>
                        <span class="price-old"><?= formatVnd($product['price']) ?></span>
                        <span class="price-save">Tiết kiệm <?= formatVnd($product['price'] - $product['sale_price']) ?></span>
                    <?php endif; ?>
                </div>

                <?php if (!empty($product['description'])) : ?>
                    <p class="short-description"><?= e($product['description']) ?></p>
                <?php endif; ?>

                <?php if (!empty($variants)) : ?>
                    <div class="section-label">Lựa chọn phiên bản</div>
                    <div class="variant-list">
                        <?php foreach ($variants as $index => $variant) : ?>
                            <button class="variant-card <?= $index === 0 ? 'is-active' : '' ?>" type="button" data-price="<?= (float) $variant['price'] ?>" data-stock="<?= (int) $variant['stock'] ?>">
                                <span>
                                    <strong><?= e(trim(($variant['color'] ?? '') . ' · ' . ($variant['storage'] ?? ''), ' ·')) ?></strong>
                                    <small>SKU: <?= e($variant['sku'] ?? 'Đang cập nhật') ?> · Còn <?= (int) $variant['stock'] ?></small>
                                </span>
                                <span class="variant-card__price"><?= formatVnd($variant['price']) ?></span>
                            </button>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <div class="availability <?= (int) $product['stock'] <= 0 ? 'is-empty' : '' ?>" id="availability">
                    <span><?= (int) $product['stock'] > 0 ? '✓' : '!' ?></span>
                    <span><?= (int) $product['stock'] > 0 ? 'Còn hàng · ' . (int) $product['stock'] . ' sản phẩm trong kho' : 'Sản phẩm đang tạm hết hàng' ?></span>
                </div>

                <div class="detail-actions">
                    <a class="detail-action is-primary" href="#specifications">Xem thông số</a>
                    <a class="detail-action" href="<?= BASE_URL ?>?act=category&amp;slug=<?= urlencode($product['category_slug']) ?>">Xem <?= e($product['category_name']) ?> khác</a>
                </div>
            </div>
        </section>

        <section class="content-grid">
            <article class="panel content-panel">
                <h2>Mô tả sản phẩm</h2>
                <?php if (!empty($product['description'])) : ?>
                    <div class="description"><?= nl2br(e($product['description'])) ?></div>
                <?php else : ?>
                    <p class="empty-copy">Thông tin mô tả đang được cập nhật.</p>
                <?php endif; ?>
            </article>

            <article class="panel content-panel" id="specifications">
                <h2>Thông số kỹ thuật</h2>
                <?php if (!empty($specifications)) : ?>
                    <table class="spec-table">
                        <tbody>
                            <?php foreach ($specifications as $spec) :
                                $key = mb_strtolower(trim($spec['spec_name']), 'UTF-8');
                                $label = $specLabels[$key] ?? $spec['spec_name'];
                            ?>
                                <tr>
                                    <th><?= e($label) ?></th>
                                    <td><?= e($spec['spec_value']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else : ?>
                    <p class="empty-copy">Thông số kỹ thuật đang được cập nhật.</p>
                <?php endif; ?>
            </article>
        </section>

        <?php if (!empty($relatedProducts)) : ?>
            <section class="related">
                <div class="related__head">
                    <h2>Sản phẩm cùng danh mục</h2>
                    <a href="<?= BASE_URL ?>?act=category&amp;slug=<?= urlencode($product['category_slug']) ?>">Xem tất cả →</a>
                </div>
                <div class="related-grid">
                    <?php foreach ($relatedProducts as $related) :
                        $relatedDiscount = discountPercent($related['price'], $related['sale_price'] ?? null);
                        $relatedPrice = $relatedDiscount > 0 ? $related['sale_price'] : $related['price'];
                    ?>
                        <a class="related-card" href="<?= BASE_URL ?>?act=product-detail&amp;slug=<?= urlencode($related['slug']) ?>">
                            <div class="related-card__image"><img src="<?= e($related['thumbnail'] ?? '') ?>" alt="<?= e($related['name']) ?>"></div>
                            <div class="related-card__body">
                                <h3><?= e($related['name']) ?></h3>
                                <div class="related-card__price"><?= formatVnd($relatedPrice) ?></div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endif; ?>
    </main>

    <script>
        document.querySelectorAll('.gallery__thumb').forEach(function (button) {
            button.addEventListener('click', function () {
                document.querySelectorAll('.gallery__thumb').forEach(function (item) { item.classList.remove('is-active'); });
                this.classList.add('is-active');
                document.getElementById('mainProductImage').src = this.dataset.image;
            });
        });

        const priceFormatter = new Intl.NumberFormat('vi-VN');
        document.querySelectorAll('.variant-card').forEach(function (button) {
            button.addEventListener('click', function () {
                document.querySelectorAll('.variant-card').forEach(function (item) { item.classList.remove('is-active'); });
                this.classList.add('is-active');
                document.getElementById('displayPrice').textContent = priceFormatter.format(Number(this.dataset.price)) + 'đ';

                const stock = Number(this.dataset.stock);
                const availability = document.getElementById('availability');
                availability.classList.toggle('is-empty', stock <= 0);
                availability.innerHTML = '<span>' + (stock > 0 ? '✓' : '!') + '</span><span>' + (stock > 0 ? 'Phiên bản này còn ' + stock + ' sản phẩm' : 'Phiên bản này đang tạm hết hàng') + '</span>';
            });
        });
    </script>
</body>
</html>
