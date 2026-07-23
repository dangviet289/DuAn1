<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin · Chỉnh sửa banner</title>
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

        .card-panel {
            background: var(--paper);
            border: 1px solid var(--line);
            border-radius: var(--radius);
            overflow: hidden;
        }

        .form-card {
            background: var(--paper);
            border-radius: var(--radius);
            border: 1px solid var(--line);
            padding: 28px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        }

        .form-card h2 {
            margin-bottom: 1rem;
            color: var(--crate-green);
        }

        .panel-footer {
            margin-top: 24px;
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }

        .img-preview {
            width: 100%;
            max-width: 360px;
            border-radius: 14px;
            border: 1px solid var(--line);
            object-fit: cover;
            margin-top: 12px;
        }

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
                    <div class="eyebrow">Chỉnh sửa banner</div>
                    <h1>Banner hiện tại</h1>
                    <p>Cập nhật nội dung hoặc trạng thái banner hiển thị trên trang chủ.</p>
                </div>
                <a href="<?= BASE_URL_ADMIN ?>banners.php?act=list" class="btn btn-outline-secondary">← Quay lại danh sách</a>
            </div>

            <div class="form-card">
                <h2>Thông tin banner</h2>
                <form method="POST" action="<?= BASE_URL_ADMIN ?>banners.php?act=update">
                    <input type="hidden" name="id" value="<?= (int) $banner['id'] ?>">

                    <div class="row g-4">
                        <div class="col-lg-8">
                            <label class="form-label">URL ảnh banner <span class="text-danger">*</span></label>
                            <input type="text" name="image" id="imageUrl" class="form-control" required value="<?= e($banner['image']) ?>">
                        </div>
                        <div class="col-lg-4">
                            <label class="form-label">Thứ tự hiển thị</label>
                            <input type="number" min="0" name="sort_order" class="form-control" value="<?= (int) $banner['sort_order'] ?>">
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label">Tiêu đề</label>
                            <input type="text" name="title" class="form-control" value="<?= e($banner['title'] ?? '') ?>">
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label">Link</label>
                            <input type="text" name="link" class="form-control" value="<?= e($banner['link'] ?? '') ?>">
                        </div>
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" <?= (int) $banner['is_active'] === 1 ? 'checked' : '' ?> >
                                <label class="form-check-label" for="is_active">Hiển thị banner</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div>
                                <div class="text-muted">Xem trước ảnh banner</div>
                                <img id="previewImage" class="img-preview" src="<?= e($banner['image']) ?>" alt="Preview banner">
                            </div>
                        </div>
                    </div>

                    <div class="panel-footer">
                        <button type="submit" class="btn btn-warning">Cập nhật banner</button>
                        <a href="<?= BASE_URL_ADMIN ?>banners.php?act=list" class="btn btn-outline-secondary">Hủy</a>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <script>
        const imageUrl = document.getElementById('imageUrl');
        const previewImage = document.getElementById('previewImage');

        imageUrl.addEventListener('input', function () {
            const url = this.value.trim();
            previewImage.src = url || '<?= e($banner['image']) ?>';
            previewImage.style.display = url ? 'block' : 'none';
        });
    </script>
</body>

</html>
