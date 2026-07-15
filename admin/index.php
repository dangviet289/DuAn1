<?php

// Redirect to the main admin route with the act parameter
$act = $_GET['act'] ?? 'product-list';
header('Location: ../index.php?act=' . urlencode($act) . (isset($_GET['product_id']) ? '&product_id=' . urlencode($_GET['product_id']) : ''));
exit();
