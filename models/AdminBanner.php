<?php

class AdminBanner
{
    public static function getAll()
    {
        $conn = connectDB();
        $stmt = $conn->prepare(
            "SELECT * FROM banners ORDER BY sort_order ASC, id DESC"
        );
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function getById($id)
    {
        $conn = connectDB();
        $stmt = $conn->prepare("SELECT * FROM banners WHERE id = ? LIMIT 1");
        $stmt->execute([(int) $id]);
        return $stmt->fetch();
    }

    public static function create($data)
    {
        $conn = connectDB();
        $stmt = $conn->prepare(
            "INSERT INTO banners (image, link, title, is_active, sort_order)
             VALUES (:image, :link, :title, :is_active, :sort_order)"
        );

        return $stmt->execute([
            ':image' => $data['image'],
            ':link' => $data['link'],
            ':title' => $data['title'],
            ':is_active' => $data['is_active'],
            ':sort_order' => $data['sort_order'],
        ]);
    }

    public static function update($id, $data)
    {
        $conn = connectDB();
        $stmt = $conn->prepare(
            "UPDATE banners SET
                 image = :image,
                 link = :link,
                 title = :title,
                 is_active = :is_active,
                 sort_order = :sort_order
             WHERE id = :id"
        );

        return $stmt->execute([
            ':id' => (int) $id,
            ':image' => $data['image'],
            ':link' => $data['link'],
            ':title' => $data['title'],
            ':is_active' => $data['is_active'],
            ':sort_order' => $data['sort_order'],
        ]);
    }

    public static function delete($id)
    {
        $conn = connectDB();
        $stmt = $conn->prepare("DELETE FROM banners WHERE id = ?");
        return $stmt->execute([(int) $id]);
    }
}
