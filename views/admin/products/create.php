<!DOCTYPE html>
<html lang="vi">

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
            <div class="col-lg-9">
                <div class="form-container">
                    <form method="POST" action="<?= BASE_URL ?>?act=product-store">
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label for="name" class="form-label"><strong>Tên sản phẩm <span class="text-danger">*</span></strong></label>
                                <input type="text" class="form-control" id="name" name="name" required placeholder="Ví dụ: iPhone 15 Pro Max">
                            </div>
                            <div class="col-md-4">
                                <label for="slug" class="form-label"><strong>Slug</strong></label>
                                <input type="text" class="form-control" id="slug" name="slug" placeholder="Để trống để tự tạo">
                            </div>

                            <div class="col-md-6">
                                <label for="category_id" class="form-label"><strong>Danh mục <span class="text-danger">*</span></strong></label>
                                <select class="form-select" id="category_id" name="category_id" required>
                                    <option value="">-- Chọn danh mục --</option>
                                    <?php foreach ($categories as $category) : ?>
                                        <option value="<?= (int) $category['id'] ?>"><?= e($category['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="brand_id" class="form-label"><strong>Thương hiệu <span class="text-danger">*</span></strong></label>
                                <select class="form-select" id="brand_id" name="brand_id" required>
                                    <option value="">-- Chọn thương hiệu --</option>
                                    <?php foreach ($brands as $brand) : ?>
                                        <option value="<?= (int) $brand['id'] ?>"><?= e($brand['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label for="price" class="form-label"><strong>Giá gốc <span class="text-danger">*</span></strong></label>
                                <input type="number" min="0" step="1000" class="form-control" id="price" name="price" required>
                            </div>
                            <div class="col-md-4">
                                <label for="sale_price" class="form-label"><strong>Giá khuyến mãi</strong></label>
                                <input type="number" min="0" step="1000" class="form-control" id="sale_price" name="sale_price">
                            </div>
                            <div class="col-md-4">
                                <label for="stock" class="form-label"><strong>Tồn kho</strong></label>
                                <input type="number" min="0" class="form-control" id="stock" name="stock" value="0">
                            </div>

                            <div class="col-12">
                                <label for="thumbnail" class="form-label"><strong>Ảnh đại diện (URL)</strong></label>
                                <input type="text" class="form-control" id="thumbnail" name="thumbnail" placeholder="https://... hoặc uploads/ten-anh.jpg">
                            </div>
                            <div class="col-12">
                                <label for="description" class="form-label"><strong>Mô tả</strong></label>
                                <textarea class="form-control" id="description" name="description" rows="4"></textarea>
                            </div>

                            <div class="col-12 d-flex flex-wrap gap-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" checked>
                                    <label class="form-check-label" for="is_active">Đang hiển thị</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1">
                                    <label class="form-check-label" for="is_featured">Sản phẩm nổi bật</label>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2 mt-4">
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
