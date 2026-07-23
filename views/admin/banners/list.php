<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin · Quản lý banner</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:wght@500;600;700&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        :root {
            --crate-green: #1F3D2B;
            --crate-green-light: #2C5039;
            --papaya: #E8743B;
            --papaya-dark: #C85A26;
            --lime: #8BAE52;
            --cream: #FAF7F0;
            --paper: #FFFFFF;
            --ink: #24302A;
            --ink-soft: #6B7A70;
            --line: #E7E1D4;
            --radius: 14px;
        }

        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            background: var(--cream);
            color: var(--ink);
            font-family: 'Manrope', system-ui, sans-serif;
        }

        .shell {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 240px;
            flex-shrink: 0;
            background: var(--crate-green);
            color: #F1EFE6;
            padding: 28px 20px;
            display: flex;
            flex-direction: column;
            gap: 32px;
        }

        .sidebar .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 21px;
            font-weight: 600;
            letter-spacing: 0.2px;
        }

        .sidebar .brand-mark {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: var(--papaya);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 17px;
        }

        .sidebar nav {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .sidebar nav a {
            color: #CFE0D4;
            text-decoration: none;
            padding: 10px 12px;
            border-radius: 10px;
            font-size: 14.5px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: background .15s ease, color .15s ease;
        }

        .sidebar nav a:hover { background: rgba(255,255,255,0.06); color: #fff; }
        .sidebar nav a.active {
            background: var(--crate-green-light);
            color: #fff;
            box-shadow: inset 3px 0 0 var(--papaya);
        }

        .sidebar .back-home {
            margin-top: auto;
            border-top: 1px solid rgba(255,255,255,0.12);
            padding-top: 16px;
        }

        .sidebar .back-home a {
            color: #CFE0D4;
            text-decoration: none;
            font-size: 13.5px;
        }

        .sidebar .back-home a:hover { color: #fff; }

        .main {
            flex: 1;
            padding: 32px 40px 60px;
            max-width: 1180px;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 28px;
            gap: 20px;
            flex-wrap: wrap;
        }

        .topbar .eyebrow {
            font-size: 12.5px;
            font-weight: 700;
            letter-spacing: 1.4px;
            text-transform: uppercase;
            color: var(--papaya-dark);
            margin-bottom: 6px;
        }

        .topbar h1 {
            font-size: 30px;
            font-weight: 600;
            margin: 0;
            color: var(--crate-green);
        }

        .topbar p {
            margin: 6px 0 0;
            color: var(--ink-soft);
            font-size: 14.5px;
        }

        .btn-add {
            background: var(--papaya);
            border: none;
            color: #fff;
            font-weight: 600;
            padding: 12px 20px;
            border-radius: 10px;
            font-size: 14.5px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            box-shadow: 0 6px 14px rgba(232, 116, 59, 0.28);
            transition: transform .12s ease, box-shadow .12s ease;
        }

        .btn-add:hover {
            background: var(--papaya-dark);
            color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 8px 18px rgba(200, 90, 38, 0.32);
        }

        .card-panel {
            background: var(--paper);
            border: 1px solid var(--line);
            border-radius: var(--radius);
            overflow: hidden;
        }

        .card-panel-head {
            padding: 18px 22px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            border-bottom: 1px solid var(--line);
            flex-wrap: wrap;
        }

        .card-panel-head h2 {
            font-size: 18px;
            font-weight: 600;
            margin: 0;
            color: var(--crate-green);
        }

        .table-banner {
            width: 100%;
            border-collapse: collapse;
        }

        .table-banner thead {
            background: #f8faf8;
        }

        .table-banner th,
        .table-banner td {
            padding: 16px 14px;
            border-bottom: 1px solid #ECEAE6;
            vertical-align: middle;
        }

        .table-banner th {
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: var(--ink-soft);
            font-weight: 700;
        }

        .banner-thumb {
            width: 100px;
            height: 58px;
            object-fit: cover;
            border-radius: 12px;
            background: #f2f2f2;
        }

        .badge-status {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 12px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 700;
        }

        .badge-active { background: #d4f6d6; color: #176a2d; }
        .badge-hidden { background: #f9d8d8; color: #8f1a1a; }

        .empty-state {
            padding: 64px 24px;
            text-align: center;
        }

        .empty-state .stamp {
            font-size: 48px;
            margin-bottom: 16px;
        }

        .row-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .action-btn {
            font-size: 13px;
            padding: 8px 12px;
            border-radius: 10px;
            text-decoration: none;
            color: #fff;
        }

        .action-edit { background: #f0a500; }
        .action-delete { background: #dc3545; }

        @media (max-width: 900px) {
            .sidebar { display: none; }
            .main { padding: 24px; }
            .topbar { flex-direction: column; align-items: stretch; }
        }
    </style>
</head>

<body>
    <div class="shell">
        <aside class="sidebar">
            <div class="brand">
                <span class="brand-mark">T</span>
                <span class="brand-word">Tech Store</span>
            </div>
            <nav>
                <a href="<?= BASE_URL_ADMIN ?>?act=product-list">💻 Sản phẩm</a>
                <a href="<?= BASE_URL_ADMIN ?>banners.php?act=list" class="active">🖼️ Banner</a>
                <a href="#">📦 Đơn hàng</a>
                <a href="#">👤 Khách hàng</a>
                <a href="#">🏷️ Khuyến mãi</a>
            </nav>
            <div class="back-home">
                <a href="<?= BASE_URL ?>">← Về trang chủ</a>
            </div>
        </aside>

        <main class="main">
            <div class="topbar">
                <div>
                    <div class="eyebrow">Quản trị cửa hàng</div>
                    <h1>Quản lý banner</h1>
                    <p>Quản lý banner hiển thị trên trang chủ, thay đổi thứ tự và trạng thái dễ dàng.</p>
                </div>
                <a href="<?= BASE_URL_ADMIN ?>banners.php?act=create" class="btn-add">+ Thêm banner mới</a>
            </div>

            <div class="card-panel">
                <div class="card-panel-head">
                    <h2>Danh sách banner</h2>
                    <p class="text-muted"><?= count($banners) ?> banner đang có trong hệ thống.</p>
                </div>

                <?php if (!empty($banners)) : ?>
                    <div class="table-responsive">
                        <table class="table-banner">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Ảnh</th>
                                    <th>Tiêu đề</th>
                                    <th>Link</th>
                                    <th>Trạng thái</th>
                                    <th>Thứ tự</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($banners as $banner) : ?>
                                    <tr>
                                        <td><?= (int) $banner['id'] ?></td>
                                        <td><img src="<?= e($banner['image']) ?>" alt="Banner" class="banner-thumb"></td>
                                        <td><?= e($banner['title'] ?: 'Không có tiêu đề') ?></td>
                                        <td><a href="<?= e($banner['link'] ?: '#') ?>" target="_blank"><?= e($banner['link'] ?: 'Không có') ?></a></td>
                                        <td>
                                            <span class="badge-status <?= (int) $banner['is_active'] === 1 ? 'badge-active' : 'badge-hidden' ?>">
                                                <?= (int) $banner['is_active'] === 1 ? 'Hoạt động' : 'Ẩn' ?>
                                            </span>
                                        </td>
                                        <td><?= (int) $banner['sort_order'] ?></td>
                                        <td>
                                            <div class="row-actions">
                                                <a href="<?= BASE_URL_ADMIN ?>banners.php?act=edit&id=<?= (int) $banner['id'] ?>" class="action-btn action-edit">Sửa</a>
                                                <a href="<?= BASE_URL_ADMIN ?>banners.php?act=delete&id=<?= (int) $banner['id'] ?>" class="action-btn action-delete" onclick="return confirm('Bạn có chắc muốn xóa banner này?');">Xóa</a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else : ?>
                    <div class="empty-state">
                        <div class="stamp">🖼️</div>
                        <h2>Chưa có banner nào</h2>
                        <p class="text-muted">Thêm banner mới để hiển thị nội dung quảng cáo trên trang chủ.</p>
                        <a href="<?= BASE_URL_ADMIN ?>banners.php?act=create" class="btn-add">Thêm banner đầu tiên</a>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>
</body>

</html>
