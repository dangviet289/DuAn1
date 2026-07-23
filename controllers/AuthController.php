<?php

class AuthController
{
    /**
     * Hiển thị trang đăng nhập
     */
    public function showLogin()
    {
        require_once './views/auth/login.php';
    }

    /**
     * Xử lý đăng nhập
     */
    public function login()
    {
        require_once './views/auth/login.php';
    }

    /**
     * Hiển thị trang đăng ký
     */
    public function showRegister()
    {
        require_once './views/auth/register.php';
    }

    /**
     * Xử lý đăng ký
     */
    public function register()
    {
        require_once './views/auth/register.php';
    }

    /**
     * Đăng xuất
     */
    public function logout()
    {
        session_start();
        session_destroy();
        header("Location: " . BASE_URL);
        exit;
    }
}
