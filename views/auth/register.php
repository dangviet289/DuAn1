<?php
// Session đã được khởi tạo trong index.php

// ==== LOAD CONFIG ====
require_once dirname(dirname(__DIR__)) . '/commons/env.php';
require_once dirname(dirname(__DIR__)) . '/commons/function.php';

// Nếu đã đăng nhập, chuyển hướng về trang chủ
if (isset($_SESSION['user_id'])) {
    header("Location: " . BASE_URL);
    exit;
}

$errors = [];
$success = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $fullname = trim($_POST["fullname"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $username = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";
    $confirm_password = $_POST["confirm_password"] ?? "";

    // Validation
    if (empty($fullname)) {
        $errors[] = "Vui lòng nhập họ và tên.";
    }
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Vui lòng nhập email hợp lệ.";
    }
    if (empty($username) || strlen($username) < 3) {
        $errors[] = "Tên đăng nhập phải có ít nhất 3 ký tự.";
    }
    if (empty($password) || strlen($password) < 6) {
        $errors[] = "Mật khẩu phải có ít nhất 6 ký tự.";
    }
    if ($password !== $confirm_password) {
        $errors[] = "Mật khẩu xác nhận không trùng khớp.";
    }

    if (empty($errors)) {
        try {
            // Kết nối database
            $pdo = new PDO(
                "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
                DB_USERNAME,
                DB_PASSWORD,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );

            // Kiểm tra email hoặc username đã tồn tại
            $checkStmt = $pdo->prepare("SELECT id FROM users WHERE email = ? OR username = ?");
            $checkStmt->execute([$email, $username]);

            if ($checkStmt->rowCount() > 0) {
                $errors[] = "Email hoặc tên đăng nhập đã được sử dụng.";
            } else {
                // Hash password
                $hashed_password = password_hash($password, PASSWORD_BCRYPT);

                // Insert user
                $insertStmt = $pdo->prepare("INSERT INTO users (fullname, email, username, password, role, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
                $insertStmt->execute([$fullname, $email, $username, $hashed_password, 'user']);

                $success = true;
                $successMsg = "Đăng ký thành công! Vui lòng đăng nhập.";

                // Clear form
                $_POST = [];

                // Redirect sau 2 giây
                echo '<meta http-equiv="refresh" content="2; url=' . BASE_URL . '?act=login">';
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
    <title>Đăng ký tài khoản - VietPhone</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    
    <!-- CSS & Styling -->
    <style>
        :root {
            /* Palette trẻ trung, cao cấp và năng động giống homepage */
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

        /* Register Container & Glass Card */
        .register-container {
            width: 100%;
            max-width: 480px;
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

        .register-card {
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

        .register-card:hover {
            box-shadow: 
                0 4px 30px rgba(79, 70, 229, 0.05),
                0 35px 70px rgba(79, 70, 229, 0.12),
                inset 0 1px 1px rgba(255, 255, 255, 0.8);
        }

        /* Header Styles */
        .register-header {
            text-align: center;
            margin-bottom: 36px;
        }

        .register-brand {
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

        .register-brand:hover .brand-logo-glow {
            transform: rotate(10deg) scale(1.05);
        }

        .brand-text {
            font-size: 26px;
            font-weight: 700;
            color: var(--navy);
            font-family: 'Space Grotesk', system-ui, sans-serif;
            letter-spacing: -0.5px;
        }

        .register-header h1 {
            font-size: 28px;
            font-weight: 700;
            color: var(--navy);
            margin: 8px 0;
            font-family: 'Space Grotesk', system-ui, sans-serif;
            letter-spacing: -0.5px;
        }

        .register-header p {
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

        /* Password Strength Meter */
        .strength-meter {
            height: 4px;
            width: 100%;
            background-color: rgba(15, 23, 42, 0.05);
            border-radius: 2px;
            margin-top: 6px;
            overflow: hidden;
            display: flex;
            gap: 2px;
        }

        .strength-bar {
            height: 100%;
            flex: 1;
            background-color: transparent;
            transition: var(--transition);
        }

        .strength-text {
            font-size: 11px;
            color: var(--ink-soft);
            margin-top: 4px;
            display: block;
            text-align: right;
            font-weight: 500;
            min-height: 15px;
            transition: var(--transition);
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

        .custom-alert-success {
            background: rgba(240, 253, 244, 0.9);
            border: 1px solid rgba(16, 185, 129, 0.2);
            color: var(--mint);
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
        .btn-register {
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

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(99, 102, 241, 0.3);
        }

        .btn-register:active {
            transform: translateY(0);
        }

        /* Button shimmer effect */
        .btn-register::after {
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

        .btn-register:hover::after {
            left: 125%;
        }

        .register-footer {
            text-align: center;
            margin-top: 32px;
            padding-top: 24px;
            border-top: 1px solid var(--line);
        }

        .register-footer p {
            color: var(--ink-soft);
            font-size: 14px;
            font-weight: 500;
        }

        .register-footer a {
            color: var(--electric);
            text-decoration: none;
            font-weight: 600;
            transition: var(--transition);
            display: inline-block;
            margin-left: 2px;
        }

        .register-footer a:hover {
            color: var(--electric-dark);
            transform: translateX(2px);
        }

        /* Responsiveness */
        @media (max-width: 576px) {
            .register-card {
                padding: 36px 24px;
                border-radius: var(--radius-md);
            }

            .register-header h1 {
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

    <div class="register-container">
        <div class="register-card">
            <!-- Brand & Header -->
            <div class="register-header">
                <div class="register-brand">
                    <div class="brand-logo-glow">VP</div>
                    <span class="brand-text">VietPhone</span>
                </div>
                <h1>Đăng ký</h1>
                <p>Tạo tài khoản để khám phá thế giới công nghệ</p>
            </div>

            <!-- Alerts -->
            <?php if ($success) : ?>
                <div class="alert-wrap">
                    <div class="custom-alert custom-alert-success">
                        <span class="alert-icon">✓</span>
                        <div class="alert-content">
                            <strong><?= htmlspecialchars($successMsg) ?></strong>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

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
            <form method="POST" action="" id="registerForm">
                
                <!-- Họ & Tên -->
                <div class="form-group">
                    <div class="form-control-wrapper">
                        <input type="text" id="fullname" name="fullname" placeholder=" " value="<?= htmlspecialchars($_POST['fullname'] ?? '') ?>" required autocomplete="name">
                        <label for="fullname">Họ và tên</label>
                    </div>
                </div>

                <!-- Email -->
                <div class="form-group">
                    <div class="form-control-wrapper">
                        <input type="email" id="email" name="email" placeholder=" " value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required autocomplete="email">
                        <label for="email">Email</label>
                    </div>
                </div>

                <!-- Tên đăng nhập -->
                <div class="form-group">
                    <div class="form-control-wrapper">
                        <input type="text" id="username" name="username" placeholder=" " value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required autocomplete="username">
                        <label for="username">Tên đăng nhập</label>
                    </div>
                </div>

                <!-- Mật khẩu -->
                <div class="form-group password-container">
                    <div class="form-control-wrapper">
                        <input type="password" id="password" name="password" placeholder=" " required autocomplete="new-password">
                        <label for="password">Mật khẩu</label>
                        <button type="button" class="toggle-password-btn" onclick="togglePassword('password', this)" title="Hiện/Ẩn mật khẩu">
                            <!-- SVG Eye Icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="eye-open-icon"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                        </button>
                    </div>
                    <!-- Strength meter -->
                    <div class="strength-meter">
                        <div class="strength-bar" id="bar-1"></div>
                        <div class="strength-bar" id="bar-2"></div>
                        <div class="strength-bar" id="bar-3"></div>
                    </div>
                    <span class="strength-text" id="strength-label"></span>
                </div>

                <!-- Xác nhận mật khẩu -->
                <div class="form-group password-container">
                    <div class="form-control-wrapper">
                        <input type="password" id="confirm_password" name="confirm_password" placeholder=" " required autocomplete="new-password">
                        <label for="confirm_password">Xác nhận mật khẩu</label>
                        <button type="button" class="toggle-password-btn" onclick="togglePassword('confirm_password', this)" title="Hiện/Ẩn mật khẩu">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="eye-open-icon"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn-register">
                    <span>Đăng ký tài khoản</span>
                </button>
            </form>

            <!-- Footer -->
            <div class="register-footer">
                <p>Đã có tài khoản? <a href="<?= BASE_URL ?>?act=login">Đăng nhập ngay</a></p>
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

        // Live Password Strength Meter
        const passwordInput = document.getElementById('password');
        const confirmInput = document.getElementById('confirm_password');
        const bars = [
            document.getElementById('bar-1'),
            document.getElementById('bar-2'),
            document.getElementById('bar-3')
        ];
        const strengthLabel = document.getElementById('strength-label');

        passwordInput.addEventListener('input', () => {
            const val = passwordInput.value;
            let score = 0;
            
            if (val.length >= 6) score++;
            if (/[A-Z]/.test(val) || /[0-9]/.test(val)) score++;
            if (/[^A-Za-z0-9]/.test(val)) score++;

            // Clear strengths
            bars.forEach(bar => bar.style.backgroundColor = 'transparent');

            if (val.length === 0) {
                strengthLabel.textContent = '';
                return;
            }

            if (score === 1) {
                bars[0].style.backgroundColor = 'var(--coral)'; // Red
                strengthLabel.textContent = 'Mật khẩu yếu';
                strengthLabel.style.color = 'var(--coral)';
            } else if (score === 2) {
                bars[0].style.backgroundColor = '#fb923c'; // Amber Orange
                bars[1].style.backgroundColor = '#fb923c';
                strengthLabel.textContent = 'Mật khẩu trung bình';
                strengthLabel.style.color = '#fb923c';
            } else if (score === 3) {
                bars[0].style.backgroundColor = 'var(--mint)'; // Emerald Green
                bars[1].style.backgroundColor = 'var(--mint)';
                bars[2].style.backgroundColor = 'var(--mint)';
                strengthLabel.textContent = 'Mật khẩu mạnh';
                strengthLabel.style.color = 'var(--mint)';
            }
        });

        // Real-time client-side checks for passwords matching
        confirmInput.addEventListener('input', () => {
            if (confirmInput.value === passwordInput.value && passwordInput.value.length > 0) {
                confirmInput.style.borderColor = 'var(--mint)';
                confirmInput.style.boxShadow = '0 0 0 4px rgba(16, 185, 129, 0.15)';
            } else if (confirmInput.value.length > 0) {
                confirmInput.style.borderColor = 'var(--coral)';
                confirmInput.style.boxShadow = '0 0 0 4px rgba(239, 68, 68, 0.15)';
            } else {
                confirmInput.style.borderColor = 'var(--line)';
                confirmInput.style.boxShadow = 'none';
            }
        });
    </script>
</body>

</html>