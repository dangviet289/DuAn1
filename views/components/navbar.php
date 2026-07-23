<?php
// Kiểm tra session đăng nhập
$isLoggedIn = isset($_SESSION['user_id']);
$userName = $_SESSION['username'] ?? null;
$userRole = $_SESSION['role'] ?? null;
?>

<nav class="navbar navbar-expand-lg bg-light sticky-top">
    <div class="container-fluid">
        <!-- Brand -->
        <a class="navbar-brand fw-bold" href="<?= BASE_URL ?>">
            VietPhone
        </a>
        
        <!-- Toggle Button (Mobile) -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL ?>">Trang chủ</a>
                </li>

                <?php if ($isLoggedIn && $userRole === 'admin') : ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>?act=product-list">
                            Quản lý sản phẩm
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>?act=user-list">
                             Quản lý người dùng
                        </a>
                    </li>
                <?php endif; ?>

                <li class="nav-item dropdown">
                    <?php if ($isLoggedIn) : ?>
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                             <?= htmlspecialchars($userName) ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="<?= BASE_URL ?>?act=profile">
                                    Thông tin cá nhân
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="<?= BASE_URL ?>?act=orders">
                                     Đơn hàng của tôi
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item text-danger" href="<?= BASE_URL ?>?act=logout">
                                     Đăng xuất
                                </a>
                            </li>
                        </ul>
                    <?php else : ?>
                        <a class="nav-link" href="<?= BASE_URL ?>?act=login">
                             Đăng nhập
                        </a>
                    <?php endif; ?>
                </li>

                <?php if (!$isLoggedIn) : ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>?act=register">
                             Đăng ký
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<style>
    .navbar {
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        border-bottom: 1px solid #e7e1d4;
    }
    
    .navbar-brand {
        font-size: 18px;
        color: #1F3D2B !important;
    }
    
    .nav-link {
        font-weight: 500;
        transition: color 0.2s ease;
    }
    
    .nav-link:hover {
        color: #1F3D2B !important;
    }
    
    .dropdown-menu {
        border: 1px solid #e7e1d4;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }
    
    .dropdown-item:hover {
        background-color: #f8fafc;
        color: #1F3D2B;
    }
</style>