<?php
// Session đã được khởi tạo trong index.php

// ==== LOAD CONFIG ====
require_once dirname(dirname(__DIR__)) . '/commons/env.php';
require_once dirname(dirname(__DIR__)) . '/commons/function.php';

$errors = [];
$success = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";

    if (empty($username) || empty($password)) {
        $errors[] = "Vui lòng nhập đầy đủ tên đăng nhập và mật khẩu.";
    } else {
        try {
            // Kết nối database
            $pdo = new PDO(
                "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
                DB_USERNAME,
                DB_PASSWORD,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );

            $stmt = $pdo->prepare("SELECT id, username, email, password, role FROM users WHERE username = ? OR email = ?");
            $stmt->execute([$username, $username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user["password"])) {
                // Lưu thông tin đăng nhập vào session
                $_SESSION["user_id"]  = $user["id"];
                $_SESSION["username"] = $user["username"];
                $_SESSION["email"]    = $user["email"];
                $_SESSION["role"]     = $user["role"];

                $success = true;

                // ==== ĐIỀU HƯỚNG THEO VAI TRÒ ====
                if ($user["role"] === "admin") {
                    header("Location: " . BASE_URL_ADMIN . "?act=product-list");
                    exit;
                } else {
                    header("Location: " . BASE_URL);
                    exit;
                }
            } else {
                $errors[] = "Tên đăng nhập hoặc mật khẩu không đúng.";
            }
        } catch (PDOException $e) {
            $errors[] = "Lỗi kết nối cơ sở dữ liệu.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập tài khoản - VietPhone</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    
    <!-- CSS & Styling -->
    <style>
        :root {
            /* Palette trẻ trung, cao cấp và năng động đồng bộ với homepage */
            --primary-grad: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            --secondary-grad: linear-gradient(135deg, #06b6d4 0%, #3b82f6 100%);
            --coral-grad: linear-gradient(135deg, #ff4e50 0%, #f9d423 100%);
            --accent-glow: rgba(124, 58, 237, 0.15);
            
            --navy: #0f172a; /* Slate 900 */
            --navy-soft: #1e293b; /* Slate 800 */
            --electric: #6366f1; /* Indigo 500 */
            --electric-dark: #4f46e5; /* Indigo 600 */
            --coral: #ef4444; /* Red 500 */
            --mint: #10b981; /* Emerald 500 */
            --bg: #f8fafc; /* Slate 50 */
            --paper: rgba(255, 255, 255, 0.85);
            --ink: #0f172a;
            --ink-soft: #64748b; /* Slate 500 */
            --line: rgba(226, 232, 240, 0.8); /* Slate 200 */
            --radius-lg: 24px;
            --radius-md: 16px;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background-color: var(--bg);
            color: var(--ink);
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            position: relative;
            overflow-x: hidden;
        }

        /* Animated Blob Background */
        .bg-blobs {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 0;
            overflow: hidden;
            pointer-events: none;
        }

        .blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(100px);
            opacity: 0.12;
            animation: floatBlob 20s infinite alternate ease-in-out;
        }

        .blob-1 {
            background: var(--primary-grad);
            width: 500px;
            height: 500px;
            top: -100px;
            right: -100px;
        }

        .blob-2 {
            background: var(--secondary-grad);
            width: 450px;
            height: 450px;
            bottom: -150px;
            left: -100px;
            animation-delay: -5s;
        }

        .blob-3 {
            background: var(--coral-grad);
            width: 300px;
            height: 300px;
            top: 50%;
            left: 30%;
            animation-delay: -10s;
        }

        @keyframes floatBlob {
            0% {
                transform: translate(0, 0) scale(1);
            }
            100% {
                transform: translate(60px, 40px) scale(1.15);
            }
        }

        /* Login Container & Glass Card */
        .login-container {
            width: 100%;
            max-width: 440px;
            z-index: 1;
            animation: cardEntrance 0.8s cubic-bezier(0.16, 1, 0.3, 1) both;
        }

        @keyframes cardEntrance {
            from {
                opacity: 0;
                transform: translateY(40px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-card {
            background: var(--paper);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--line);
            border-radius: var(--radius-lg);
            padding: 48px 40px;
            box-shadow: 
                0 4px 30px rgba(79, 70, 229, 0.03),
                0 30px 60px rgba(79, 70, 229, 0.08),
                inset 0 1px 1px rgba(255, 255, 255, 0.6);
            transition: var(--transition);
        }

        .login-card:hover {
            box-shadow: 
                0 4px 30px rgba(79, 70, 229, 0.05),
                0 35px 70px rgba(79, 70, 229, 0.12),
                inset 0 1px 1px rgba(255, 255, 255, 0.8);
        }

        /* Header Styles */
        .login-header {
            text-align: center;
            margin-bottom: 36px;
        }

        .login-brand {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 16px;
            position: relative;
        }

        .brand-logo-glow {
            width: 48px;
            height: 48px;
            background: var(--primary-grad);
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 16px rgba(124, 58, 237, 0.25);
            color: #fff;
            font-size: 22px;
            font-weight: 700;
            font-family: 'Space Grotesk', system-ui, sans-serif;
            margin-right: 12px;
            transition: var(--transition);
        }

        .login-brand:hover .brand-logo-glow {
            transform: rotate(10deg) scale(1.05);
        }

        .brand-text {
            font-size: 26px;
            font-weight: 700;
            color: var(--navy);
            font-family: 'Space Grotesk', system-ui, sans-serif;
            letter-spacing: -0.5px;
        }

        .login-header h1 {
            font-size: 28px;
            font-weight: 700;
            color: var(--navy);
            margin: 8px 0;
            font-family: 'Space Grotesk', system-ui, sans-serif;
            letter-spacing: -0.5px;
        }

        .login-header p {
            color: var(--ink-soft);
            font-size: 14px;
            font-weight: 400;
        }

        /* Form Group and Inputs (Floating Labels style) */
        .form-group {
            position: relative;
            margin-bottom: 24px;
        }

        .form-control-wrapper {
            position: relative;
            width: 100%;
        }

        input {
            width: 100%;
            height: 52px;
            padding: 16px 16px 4px 16px;
            border: 1px solid var(--line);
            border-radius: 12px;
            font-size: 15px;
            font-weight: 500;
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
            color: var(--ink);
            background: rgba(255, 255, 255, 0.6);
            transition: var(--transition);
            outline: none;
        }

        /* Align text input values properly to let label float */
        input:focus,
        input:not(:placeholder-shown) {
            border-color: var(--electric);
            background: #fff;
            box-shadow: 0 0 0 4px var(--accent-glow);
        }

        label {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 14px;
            font-weight: 500;
            color: var(--ink-soft);
            transition: var(--transition);
            pointer-events: none;
            transform-origin: left top;
        }

        input:focus ~ label,
        input:not(:placeholder-shown) ~ label {
            transform: translateY(-90%) scale(0.85);
            color: var(--electric);
            font-weight: 600;
        }

        /* Password input specific layouts */
        .password-container input {
            padding-right: 48px;
        }

        .toggle-password-btn {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: var(--ink-soft);
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: var(--transition);
        }

        .toggle-password-btn:hover {
            background-color: rgba(99, 102, 241, 0.08);
            color: var(--electric);
        }

        /* Alerts design */
        .alert-wrap {
            animation: slideDown 0.4s cubic-bezier(0.16, 1, 0.3, 1) both;
            margin-bottom: 24px;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .custom-alert {
            border-radius: 12px;
            padding: 16px;
            font-size: 14px;
            line-height: 1.5;
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }

        .custom-alert-error {
            background: rgba(254, 242, 242, 0.9);
            border: 1px solid rgba(239, 68, 68, 0.2);
            color: var(--coral);
        }

        .alert-icon {
            font-size: 18px;
            flex-shrink: 0;
            line-height: 1;
        }

        .alert-content ul {
            list-style: none;
            padding: 0;
        }

        .alert-content li {
            position: relative;
            padding-left: 14px;
            margin-bottom: 4px;
        }

        .alert-content li:last-child {
            margin-bottom: 0;
        }

        .alert-content li::before {
            content: "•";
            position: absolute;
            left: 0;
            font-weight: bold;
        }

        /* Buttons & Footer */
        .btn-login {
            width: 100%;
            height: 52px;
            background: var(--primary-grad);
            color: #fff;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            font-size: 15px;
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
            cursor: pointer;
            transition: var(--transition);
            margin-top: 12px;
            box-shadow: 0 6px 20px rgba(99, 102, 241, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            position: relative;
            overflow: hidden;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(99, 102, 241, 0.3);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        /* Button shimmer effect */
        .btn-login::after {
            content: '';
            position: absolute;
            top: 0;
            left: -50%;
            width: 200%;
            height: 100%;
            background: linear-gradient(
                to right,
                rgba(255, 255, 255, 0) 0%,
                rgba(255, 255, 255, 0.2) 30%,
                rgba(255, 255, 255, 0) 100%
            );
            transform: skewX(-25deg);
            transition: 0.75s ease;
        }

        .btn-login:hover::after {
            left: 125%;
        }

        .login-footer {
            text-align: center;
            margin-top: 32px;
            padding-top: 24px;
            border-top: 1px solid var(--line);
        }

        .login-footer p {
            color: var(--ink-soft);
            font-size: 14px;
            font-weight: 500;
        }

        .login-footer a {
            color: var(--electric);
            text-decoration: none;
            font-weight: 600;
            transition: var(--transition);
            display: inline-block;
            margin-left: 2px;
        }

        .login-footer a:hover {
            color: var(--electric-dark);
            transform: translateX(2px);
        }

        /* Responsiveness */
        @media (max-width: 576px) {
            .login-card {
                padding: 36px 24px;
                border-radius: var(--radius-md);
            }

            .login-header h1 {
                font-size: 24px;
            }
            
            body {
                padding: 16px;
            }
        }
    </style>
</head>

<body>
    <!-- Animated backdrop blobs -->
    <div class="bg-blobs">
        <div class="blob blob-1"></div>
        <div class="blob blob-2"></div>
        <div class="blob blob-3"></div>
    </div>

    <div class="login-container">
        <div class="login-card">
            <!-- Brand & Header -->
            <div class="login-header">
                <div class="login-brand">
                    <div class="brand-logo-glow">VP</div>
                    <span class="brand-text">VietPhone</span>
                </div>
                <h1>Đăng nhập</h1>
                <p>Chào mừng quay lại gian hàng của chúng tôi</p>
            </div>

            <!-- Alerts -->
            <?php if (!empty($errors)) : ?>
                <div class="alert-wrap">
                    <div class="custom-alert custom-alert-error">
                        <span class="alert-icon">✕</span>
                        <div class="alert-content">
                            <ul>
                                <?php foreach ($errors as $error) : ?>
                                    <li><?= htmlspecialchars($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Form -->
            <form method="POST" action="" id="loginForm">
                
                <!-- Tên đăng nhập hoặc Email -->
                <div class="form-group">
                    <div class="form-control-wrapper">
                        <input type="text" id="username" name="username" placeholder=" " value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required autocomplete="username">
                        <label for="username">Tên đăng nhập hoặc Email</label>
                    </div>
                </div>

                <!-- Mật khẩu -->
                <div class="form-group password-container">
                    <div class="form-control-wrapper">
                        <input type="password" id="password" name="password" placeholder=" " required autocomplete="current-password">
                        <label for="password">Mật khẩu</label>
                        <button type="button" class="toggle-password-btn" onclick="togglePassword('password', this)" title="Hiện/Ẩn mật khẩu">
                            <!-- SVG Eye Icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="eye-open-icon"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn-login">
                    <span>Đăng nhập</span>
                </button>
            </form>

            <!-- Footer -->
            <div class="login-footer">
                <p>Chưa có tài khoản? <a href="<?= BASE_URL ?>?act=register">Đăng ký ngay</a></p>
            </div>
        </div>
    </div>

    <!-- Client-side Interactive JavaScript -->
    <script>
        // Toggle password view
        function togglePassword(inputId, btn) {
            const input = document.getElementById(inputId);
            const isPassword = input.type === 'password';
            input.type = isPassword ? 'text' : 'password';
            
            // Toggle SVG icon representation
            if (isPassword) {
                btn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="eye-closed-icon"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>`;
            } else {
                btn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="eye-open-icon"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>`;
            }
        }
    </script>
</body>

</html>