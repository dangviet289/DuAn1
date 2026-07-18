<?php

// Hỗ trợ show bất kỳ data nào
function debug($data)
{
    echo "<pre>";

    print_r($data);

    die;
}

// Kết nối CSDL qua PDO
function connectDB() {
    // Kết nối CSDL
    $host = DB_HOST;
    $port = DB_PORT;
    $dbname = DB_NAME;

    try {
        $conn = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4", DB_USERNAME, DB_PASSWORD);

        // cài đặt chế độ báo lỗi là xử lý ngoại lệ
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // cài đặt chế độ trả dữ liệu
        $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
        return $conn;
    } catch (PDOException $e) {
        debug("Connection failed: " . $e->getMessage());
    }
}

// Escape dữ liệu trước khi hiển thị ra HTML
if (!function_exists('e')) {
    function e($value)
    {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }
}

// Định dạng tiền Việt Nam
if (!function_exists('formatVnd')) {
    function formatVnd($value)
    {
        return number_format((float) $value, 0, ',', '.') . 'đ';
    }
}

// Tính phần trăm giảm giá của sản phẩm
if (!function_exists('discountPercent')) {
    function discountPercent($price, $salePrice)
    {
        $price = (float) $price;
        $salePrice = (float) $salePrice;

        if ($price <= 0 || $salePrice <= 0 || $salePrice >= $price) {
            return 0;
        }

        return (int) round((($price - $salePrice) / $price) * 100);
    }
}

// Tạo slug đơn giản cho form quản trị
if (!function_exists('makeSlug')) {
    function makeSlug($value)
    {
        $value = trim((string) $value);
        $map = [
            'à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ' => 'a',
            'è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ' => 'e',
            'ì|í|ị|ỉ|ĩ' => 'i',
            'ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ' => 'o',
            'ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ' => 'u',
            'ỳ|ý|ỵ|ỷ|ỹ' => 'y',
            'đ' => 'd',
        ];

        $value = mb_strtolower($value, 'UTF-8');
        foreach ($map as $pattern => $replacement) {
            $value = preg_replace('/(' . $pattern . ')/u', $replacement, $value);
        }
        $value = preg_replace('/[^a-z0-9]+/', '-', $value);

        return trim($value, '-');
    }
}
