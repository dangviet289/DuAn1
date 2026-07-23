<?php

require_once __DIR__ . '/../models/AdminBanner.php';

class AdminBannerController
{
    public function list()
    {
        $banners = AdminBanner::getAll();
        require_once __DIR__ . '/../views/admin/banners/list.php';
    }

    public function showCreate()
    {
        require_once __DIR__ . '/../views/admin/banners/create.php';
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL_ADMIN . 'banners.php?act=list');
            exit();
        }

        $data = $this->bannerDataFromRequest();

        if (empty($data['image'])) {
            die('Vui lòng nhập URL ảnh banner.');
        }

        AdminBanner::create($data);
        header('Location: ' . BASE_URL_ADMIN . 'banners.php?act=list');
        exit();
    }

    public function showEdit()
    {
        $bannerId = $_GET['id'] ?? null;
        if (!$bannerId) {
            header('Location: ' . BASE_URL_ADMIN . 'banners.php?act=list');
            exit();
        }

        $banner = AdminBanner::getById($bannerId);
        if (!$banner) {
            header('Location: ' . BASE_URL_ADMIN . 'banners.php?act=list');
            exit();
        }

        require_once __DIR__ . '/../views/admin/banners/edit.php';
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL_ADMIN . 'banners.php?act=list');
            exit();
        }

        $bannerId = $_POST['id'] ?? null;
        $data = $this->bannerDataFromRequest();

        if (!$bannerId || empty($data['image'])) {
            die('Vui lòng nhập URL ảnh banner.');
        }

        AdminBanner::update($bannerId, $data);
        header('Location: ' . BASE_URL_ADMIN . 'banners.php?act=list');
        exit();
    }

    public function delete()
    {
        $bannerId = $_GET['id'] ?? null;
        if (!$bannerId) {
            header('Location: ' . BASE_URL_ADMIN . 'banners.php?act=list');
            exit();
        }

        AdminBanner::delete($bannerId);
        header('Location: ' . BASE_URL_ADMIN . 'banners.php?act=list');
        exit();
    }

    private function bannerDataFromRequest()
    {
        return [
            'image' => trim((string) ($_POST['image'] ?? '')),
            'link' => trim((string) ($_POST['link'] ?? '')) ?: null,
            'title' => trim((string) ($_POST['title'] ?? '')) ?: null,
            'is_active' => isset($_POST['is_active']) ? 1 : 0,
            'sort_order' => max(0, (int) ($_POST['sort_order'] ?? 0)),
        ];
    }
}
