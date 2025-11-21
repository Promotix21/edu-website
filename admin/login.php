<?php
/**
 * EDU Career India - Admin Login Page
 */

require_once __DIR__ . '/includes/config.php';

// If already logged in, redirect to dashboard
if (isLoggedIn()) {
    redirect(ADMIN_URL . '/index.php');
}

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        setErrorMessage('Please enter both username and password');
    } else {
        // Get user from database
        $stmt = $pdo->prepare("SELECT * FROM admin_users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if (!$user) {
            setErrorMessage('Invalid username or password');
        } else {
            // Check if account is locked
            if ($user['locked_until'] && strtotime($user['locked_until']) > time()) {
                $minutesLeft = ceil((strtotime($user['locked_until']) - time()) / 60);
                setErrorMessage("Account locked due to too many failed attempts. Try again in {$minutesLeft} minutes.");
            } else {
                // Verify password
                if (password_verify($password, $user['password'])) {
                    // Reset login attempts
                    $stmt = $pdo->prepare("UPDATE admin_users SET login_attempts = 0, locked_until = NULL, last_login = NOW() WHERE id = ?");
                    $stmt->execute([$user['id']]);

                    // Set session
                    $_SESSION['admin_id'] = $user['id'];
                    $_SESSION['admin_username'] = $user['username'];
                    $_SESSION['admin_email'] = $user['email'];
                    generateCSRFToken();

                    setSuccessMessage('Login successful! Welcome back.');
                    redirect(ADMIN_URL . '/index.php');
                } else {
                    // Increment failed attempts
                    $attempts = $user['login_attempts'] + 1;
                    $lockedUntil = null;

                    if ($attempts >= 5) {
                        $lockedUntil = date('Y-m-d H:i:s', strtotime('+30 minutes'));
                        setErrorMessage('Too many failed login attempts. Account locked for 30 minutes.');
                    } else {
                        $remaining = 5 - $attempts;
                        setErrorMessage("Invalid username or password. {$remaining} attempts remaining.");
                    }

                    $stmt = $pdo->prepare("UPDATE admin_users SET login_attempts = ?, locked_until = ? WHERE id = ?");
                    $stmt->execute([$attempts, $lockedUntil, $user['id']]);
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - <?php echo SITE_NAME; ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-container {
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 400px;
        }

        .logo {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo h1 {
            color: #2563eb;
            font-size: 28px;
            margin-bottom: 5px;
        }

        .logo p {
            color: #6b7280;
            font-size: 14px;
        }

        .alert {
            padding: 12px 16px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .alert-error {
            background-color: #fee2e2;
            color: #991b1b;
            border-left: 4px solid #dc2626;
        }

        .alert-success {
            background-color: #d1fae5;
            color: #065f46;
            border-left: 4px solid: #10b981;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
            color: #374151;
            font-size: 14px;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 6px;
            font-size: 15px;
            transition: border-color 0.3s;
        }

        input[type="text"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: #2563eb;
        }

        .btn {
            width: 100%;
            padding: 14px;
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
        }

        .btn:hover {
            background: #1d4ed8;
        }

        .footer-text {
            text-align: center;
            margin-top: 24px;
            color: #6b7280;
            font-size: 13px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo">
            <h1><?php echo SITE_NAME; ?></h1>
            <p>Admin Control Panel</p>
        </div>

        <?php
        $errorMsg = getErrorMessage();
        $successMsg = getSuccessMessage();

        if ($errorMsg): ?>
            <div class="alert alert-error"><?php echo escape($errorMsg); ?></div>
        <?php endif; ?>

        <?php if ($successMsg): ?>
            <div class="alert alert-success"><?php echo escape($successMsg); ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required autofocus>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>

            <button type="submit" class="btn">Login</button>
        </form>

        <div class="footer-text">
            <p>&copy; <?php echo date('Y'); ?> <?php echo SITE_NAME; ?>. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
