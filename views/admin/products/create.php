<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Thêm sản phẩm</title>
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
        .form-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
    </style>
</head>

<body>
    <div class="admin-header">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="mb-0">Admin - Thêm sản phẩm mới</h1>
                </div>
                <div>
                    <a href="<?= BASE_URL_ADMIN ?>?act=product-list" class="btn btn-secondary">← Quay lại</a>
                </div>
            </div>
        </div>
    </div>

    <div class="container mb-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="form-container">
                    <form method="POST" action="<?= BASE_URL_ADMIN ?>?act=product-store">
                        <div class="mb-3">
                            <label for="name" class="form-label">
                                <strong>Tên sản phẩm <span class="text-danger">*</span></strong>
                            </label>
                            <input type="text" class="form-control" id="name" name="name" required 
                                   placeholder="Nhập tên sản phẩm hoa quả">
                        </div>

                        <div class="mb-3">
                            <label for="type" class="form-label">
                                <strong>Loại <span class="text-danger">*</span></strong>
                            </label>
                            <input type="number" class="form-control" id="type" name="type" required 
                                   placeholder="Nhập loại (số)">
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success">
                                ✓ Thêm sản phẩm
                            </button>
                            <a href="<?= BASE_URL_ADMIN ?>?act=product-list" class="btn btn-secondary">
                                Hủy
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
