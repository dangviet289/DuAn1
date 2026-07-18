<?php
$navCategories = isset($categories) && is_array($categories) ? $categories : [];
$activeCategorySlug = $category['slug'] ?? ($product['category_slug'] ?? '');
$currentAct = $_GET['act'] ?? '/';
?>
<style>
    .store-nav {
        position: sticky;
        top: 0;
        z-index: 1000;
        background: rgba(255, 255, 255, .96);
        border-bottom: 1px solid #e8edf5;
        backdrop-filter: blur(14px);
    }
    .store-nav__inner {
        width: min(1180px, calc(100% - 32px));
        min-height: 70px;
        margin: 0 auto;
        display: flex;
        align-items: center;
        gap: 22px;
    }
    .store-nav__brand {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        color: #0f172a;
        text-decoration: none;
        font-weight: 800;
        white-space: nowrap;
    }
    .store-nav__mark {
        width: 38px;
        height: 38px;
        display: grid;
        place-items: center;
        border-radius: 12px;
        color: #fff;
        background: linear-gradient(135deg, #4f46e5, #7c3aed);
        box-shadow: 0 8px 18px rgba(99, 102, 241, .25);
    }
    .store-nav__links {
        display: flex;
        align-items: center;
        gap: 4px;
        flex: 1;
        overflow-x: auto;
        scrollbar-width: none;
    }
    .store-nav__links::-webkit-scrollbar { display: none; }
    .store-nav__link {
        display: inline-flex;
        align-items: center;
        min-height: 38px;
        padding: 0 13px;
        border-radius: 10px;
        color: #475569;
        text-decoration: none;
        font-size: 14px;
        font-weight: 700;
        white-space: nowrap;
    }
    .store-nav__link:hover,
    .store-nav__link.is-active {
        color: #4f46e5;
        background: #eef2ff;
    }
    .store-nav__admin {
        padding: 9px 14px;
        border: 1px solid #dfe4ec;
        border-radius: 10px;
        color: #334155;
        text-decoration: none;
        font-size: 13px;
        font-weight: 700;
        white-space: nowrap;
    }
    .store-nav__admin:hover { color: #fff; background: #0f172a; border-color: #0f172a; }
    @media (max-width: 720px) {
        .store-nav__inner { min-height: 62px; gap: 12px; }
        .store-nav__brand span:last-child { display: none; }
        .store-nav__admin { display: none; }
    }
</style>

<nav class="store-nav" aria-label="Điều hướng chính">
    <div class="store-nav__inner">
        <a class="store-nav__brand" href="<?= BASE_URL ?>">
            <span class="store-nav__mark">T</span>
            <span>Tech Store</span>
        </a>

        <div class="store-nav__links">
            <a class="store-nav__link <?= $currentAct === '/' ? 'is-active' : '' ?>" href="<?= BASE_URL ?>">
                Trang chủ
            </a>
            <?php foreach ($navCategories as $navCategory) : ?>
                <a
                    class="store-nav__link <?= $activeCategorySlug === $navCategory['slug'] ? 'is-active' : '' ?>"
                    href="<?= BASE_URL ?>?act=category&amp;slug=<?= urlencode($navCategory['slug']) ?>"
                >
                    <?= e($navCategory['name']) ?>
                </a>
            <?php endforeach; ?>
            <a class="store-nav__link <?= $currentAct === 'products' ? 'is-active' : '' ?>" href="<?= BASE_URL ?>?act=products">
                Tất cả sản phẩm
            </a>
        </div>

        <a class="store-nav__admin" href="<?= BASE_URL_ADMIN ?>?act=product-list">Quản trị</a>
    </div>
</nav>
