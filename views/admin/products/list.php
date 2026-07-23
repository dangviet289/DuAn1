<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin · Quản lý sản phẩm</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700&family=Manrope:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
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
            background: var(--cream);
            color: var(--ink);
            font-family: 'Manrope', system-ui, sans-serif;
            margin: 0;
        }

        h1, h2, h3, .brand-word {
            font-family: 'Fraunces', Georgia, serif;
        }

        .mono { font-family: 'JetBrains Mono', monospace; }

        /* ---------- Shell layout ---------- */
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

        /* ---------- Top bar ---------- */
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

        /* ---------- Stat crates ---------- */
        .stat-row {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
            margin-bottom: 28px;
        }

        .stat-crate {
            background: var(--paper);
            border: 1px solid var(--line);
            border-radius: var(--radius);
            padding: 18px 20px;
            position: relative;
            overflow: hidden;
        }

        .stat-crate::before {
            content: "";
            position: absolute;
            top: 0; left: 0;
            width: 5px; height: 100%;
            background: var(--lime);
        }

        .stat-crate.papaya-accent::before { background: var(--papaya); }
        .stat-crate.green-accent::before { background: var(--crate-green); }

        .stat-crate .stat-label {
            font-size: 12.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: var(--ink-soft);
            margin-bottom: 8px;
        }

        .stat-crate .stat-value {
            font-family: 'Fraunces', serif;
            font-size: 30px;
            font-weight: 600;
            color: var(--crate-green);
        }

        /* ---------- Card / table ---------- */
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

        .search-box {
            position: relative;
        }

        .search-box input {
            border: 1px solid var(--line);
            border-radius: 9px;
            padding: 8px 14px 8px 34px;
            font-size: 13.5px;
            width: 230px;
            background: var(--cream);
        }

        .search-box input:focus {
            outline: none;
            border-color: var(--lime);
            background: #fff;
        }

        .search-box .icon {
            position: absolute;
            left: 11px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--ink-soft);
            font-size: 13px;
        }

        table.product-table {
            width: 100%;
            border-collapse: collapse;
            margin: 0;
        }

        .product-table thead th {
            background: var(--cream);
            font-size: 11.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.7px;
            color: var(--ink-soft);
            padding: 12px 22px;
            border-bottom: 1px solid var(--line);
            text-align: left;
        }

        .product-table tbody tr {
            border-bottom: 1px solid var(--line);
            transition: background .1s ease;
        }

        .product-table tbody tr:last-child { border-bottom: none; }

        .product-table tbody tr:hover { background: #FCFAF4; }

        .product-table tbody td {
            padding: 14px 22px;
            font-size: 14.5px;
            vertical-align: middle;
        }

        .product-id {
            font-family: 'JetBrains Mono', monospace;
            font-size: 12.5px;
            color: var(--ink-soft);
        }

        .product-name {
            font-weight: 600;
            color: var(--ink);
        }

        .type-badge {
            display: inline-block;
            padding: 4px 11px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
            background: rgba(139, 174, 82, 0.16);
            color: #4C6B2C;
        }

        .row-actions {
            display: flex;
            gap: 8px;
        }

        .row-actions a {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 13px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            border: 1px solid transparent;
            transition: all .12s ease;
        }

        .action-edit {
            color: var(--crate-green);
            border-color: var(--line);
            background: var(--cream);
        }
        .action-edit:hover { background: var(--crate-green); color: #fff; }

        .action-delete {
            color: #B23A3A;
            border-color: rgba(178, 58, 58, 0.2);
            background: rgba(178, 58, 58, 0.06);
        }
        .action-delete:hover { background: #B23A3A; color: #fff; }

        /* ---------- Empty state ---------- */
        .empty-state {
            padding: 70px 20px;
            text-align: center;
        }

        .empty-state .stamp {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            border: 2px dashed var(--line);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            margin: 0 auto 16px;
            color: var(--ink-soft);
        }

        .empty-state p {
            color: var(--ink-soft);
            font-size: 14.5px;
            margin-bottom: 16px;
        }

        @media (max-width: 900px) {
            .sidebar { display: none; }
            .main { padding: 24px; }
            .stat-row { grid-template-columns: 1fr; }
        }
    </style>
</head>

<body>
    <div class="shell">
        <aside class="sidebar">
            <div class="brand">
                <span class="brand-word">VietPhone</span>
            </div>
            <nav>
                <a href="#" class="active">🧺 Sản phẩm</a>
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
                    <h1>Quản lý sản phẩm</h1>
                    <p>Theo dõi, chỉnh sửa và bổ sung sản phẩm đang bán trên gian hàng.</p>
                </div>
                <a href="<?= BASE_URL_ADMIN ?>?act=product-create" class="btn-add">
                    + Thêm sản phẩm mới
                </a>
            </div>

            <?php
            
                $totalProducts = !empty($products) ? count($products) : 0;
                $typeCount = 0;
                if (!empty($products)) {
                    $types = [];
                    foreach ($products as $p) {
                        $types[$p['type'] ?? ''] = true;
                    }
                    $typeCount = count($types);
                }
            ?>

            <div class="stat-row">
                <div class="stat-crate green-accent">
                    <div class="stat-label">Tổng sản phẩm</div>
                    <div class="stat-value"><?= $totalProducts ?></div>
                </div>
                <div class="stat-crate papaya-accent">
                    <div class="stat-label">Loại sản phẩm</div>
                    <div class="stat-value"><?= $typeCount ?></div>
                </div>
                <div class="stat-crate">
                    <div class="stat-label">Cập nhật gần nhất</div>
                    <div class="stat-value" style="font-size: 18px;"><?= date('d/m/Y') ?></div>
                </div>
            </div>

            <div class="card-panel">
                <div class="card-panel-head">
                    <h2>Danh sách sản phẩm</h2>
                    <div class="search-box">
                        <span class="icon">🔍</span>
                        <input type="text" id="productSearch" placeholder="Tìm theo tên sản phẩm...">
                    </div>
                </div>

                <?php if (!empty($products)) : ?>
                    <div class="table-responsive">
                        <table class="product-table" id="productTable">
                            <thead>
                                <tr>
                                    <th style="width: 12%;">Mã SP</th>
                                    <th style="width: 40%;">Tên sản phẩm</th>
                                    <th style="width: 20%;">Loại</th>
                                    <th style="width: 28%;">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($products as $product) : ?>
                                    <tr>
                                        <td class="product-id">#<?= $product['product_id'] ?? $product['id'] ?? '' ?></td>
                                        <td class="product-name"><?= htmlspecialchars($product['name'] ?? '') ?></td>
                                        <td><span class="type-badge"><?= htmlspecialchars($product['type'] ?? '') ?></span></td>
                                        <td>
                                            <div class="row-actions">
                                                <a href="<?= BASE_URL_ADMIN ?>?act=product-edit&product_id=<?= $product['product_id'] ?? $product['id'] ?? '' ?>"
                                                   class="action-edit">✏️ Sửa</a>
                                                <a href="<?= BASE_URL_ADMIN ?>?act=product-delete&product_id=<?= $product['product_id'] ?? $product['id'] ?? '' ?>"
                                                   class="action-delete"
                                                   onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này?')">🗑️ Xóa</a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else : ?>
                    <div class="empty-state">
                        <div class="stamp">🧺</div>
                        <p>Chưa có sản phẩm nào trong gian hàng.</p>
                        <a href="<?= BASE_URL_ADMIN ?>?act=product-create" class="btn-add">+ Thêm sản phẩm đầu tiên</a>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <script>
        const searchInput = document.getElementById('productSearch');
        if (searchInput) {
            searchInput.addEventListener('input', function () {
                const term = this.value.trim().toLowerCase();
                document.querySelectorAll('#productTable tbody tr').forEach(function (row) {
                    const name = row.querySelector('.product-name').textContent.toLowerCase();
                    row.style.display = name.includes(term) ? '' : 'none';
                });
            });
        }
    </script>
</body>

</html>