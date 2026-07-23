<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Không tìm thấy trang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f8fafc; color: #0f172a; }
        .not-found { min-height: 80vh; display: grid; place-items: center; padding: 24px; }
        .not-found-card { max-width: 560px; padding: 48px; border-radius: 24px; background: #fff; border: 1px solid #e2e8f0; text-align: center; box-shadow: 0 20px 50px rgba(15, 23, 42, .08); }
        .code { color: #6366f1; font-size: 72px; font-weight: 800; line-height: 1; }
    </style>
</head>
<body>
    <?php include './views/components/navbar.php'; ?>
    <main class="not-found">
        <div class="not-found-card">
            <div class="code">404</div>
            <h1 class="h3 mt-3">Không tìm thấy nội dung</h1>
            <p class="text-secondary mt-3 mb-4"><?= e($message) ?></p>
            <a class="btn btn-primary" href="<?= BASE_URL ?>">Quay về trang chủ</a>
        </div>
    </main>
</body>
</html>
