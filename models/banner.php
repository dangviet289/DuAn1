<?php

class Banner
{
    // Banner đang hoạt động, sắp xếp theo thứ tự hiển thị
    public static function getActive()
    {
        $conn = connectDB();
        $stmt = $conn->prepare(
            "SELECT * FROM banners
             WHERE is_active = 1
             ORDER BY sort_order ASC"
        );
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function getAll()
    {
        $conn = connectDB();
        $stmt = $conn->prepare("SELECT * FROM banners ORDER BY sort_order ASC");
        $stmt->execute();
        return $stmt->fetchAll();
    }
}