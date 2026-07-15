<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Quản lý sản phẩm</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            background-color: #f5f5f5;
        }
        .admin-header {
            background-color: #fff;
            padding: 20px 0;
            margin-bottom: 30px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .btn-sm {
            padding: 5px 10px;
            font-size: 12px;
        }
        .action-buttons {
            display: flex;
            gap: 5px;
        }
    </style>
</head>

<body>
    <div class="admin-header">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="mb-0">Admin - Quản lý sản phẩm hoa quả</h1>
                </div>
                <div>
                    <a href="<?= BASE_URL ?>" class="btn btn-secondary me-2">← Về trang chủ</a>
                </div>
            </div>
        </div>
    </div>

    <div class="container mb-5">
        <div class="row mb-3">
            <div class="col-md-12">
                <a href="<?= BASE_URL_ADMIN ?>?act=product-create" class="btn btn-success">
                    ➕ Thêm sản phẩm mới
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Danh sách sản phẩm</h5>
                    </div>
                    <div class="card-body p-0">
                        <?php if (!empty($products)) : ?>
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 10%;">ID</th>
                                            <th style="width: 40%;">Tên sản phẩm</th>
                                            <th style="width: 20%;">Loại</th>
                                            <th style="width: 30%;">Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($products as $product) : ?>
                                            <tr>
                                                <td><?= $product['product_id'] ?></td>
                                                <td><strong><?= htmlspecialchars($product['name']) ?></strong></td>
                                                <td>
                                                    <span class="badge bg-info">
                                                        <?= $product['type'] ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="action-buttons">
                                                        <a href="<?= BASE_URL_ADMIN ?>?act=product-edit&product_id=<?= $product['product_id'] ?>" 
                                                           class="btn btn-primary btn-sm">✏️ Sửa</a>
                                                        <a href="<?= BASE_URL_ADMIN ?>?act=product-delete&product_id=<?= $product['product_id'] ?>" 
                                                           class="btn btn-danger btn-sm"
                                                           onclick="return confirm('Bạn có chắc muốn xóa?')">🗑️ Xóa</a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else : ?>
                            <div class="alert alert-info m-3">
                                Không có sản phẩm nào. <a href="<?= BASE_URL_ADMIN ?>?act=product-create">Thêm sản phẩm mới</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
