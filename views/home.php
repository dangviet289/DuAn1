<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang chủ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <?php include('./views/components/navbar.php'); ?>

    <div class="container">
        <div class="row">
            <h1 class="mb-5">Trang chủ</h1>

            <h3>Top 5 sản phẩm mới nhất</h3>
            <ul>
                <?php foreach ($top5ProductLatest as $product) : ?>
                    <li><?= $product['name'] ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</body>

</html>