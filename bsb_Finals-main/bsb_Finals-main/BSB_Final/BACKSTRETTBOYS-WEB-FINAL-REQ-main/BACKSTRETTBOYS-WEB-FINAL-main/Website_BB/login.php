<?php
/**
 * Admin Login - Backstreet Boys Fan Website
 * Authenticates admins using username and password
 */

session_start();
include_once 'config/database.php';

// Redirect if already logged in
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($username) || empty($password)) {
        $error = 'Please enter both username and password.';
    } else {
        try {
            $database = new Database();
            $db = $database->getConnection();
            
            // Query admin by username
            $query = "SELECT admin_id, username, password FROM admins WHERE username = ?";
            $stmt = $db->prepare($query);
            $stmt->execute([$username]);
            $admin = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($admin && password_verify($password, $admin['password'])) {
                // Login successful
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_id'] = $admin['admin_id'];
                $_SESSION['admin_username'] = $admin['username'];
                
                header('Location: dashboard.php');
                exit;
            } else {
                $error = 'Invalid username or password.';
            }
        } catch (PDOException $e) {
            $error = 'Database error. Please try again later.';
            error_log($e->getMessage());
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Backstreet Boys Fan Club</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
            color: #e4e4e4;
        }

        .bg-gradient {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: 
                radial-gradient(circle at 30% 20%, rgba(76, 29, 149, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 70% 80%, rgba(59, 130, 246, 0.1) 0%, transparent 50%);
            pointer-events: none;
            z-index: 0;
        }

        .login-container {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 420px;
            padding: 20px;
        }

        .login-card {
            background: rgba(30, 30, 50, 0.7);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            padding: 40px;
            backdrop-filter: blur(20px);
            box-shadow: 
                0 4px 24px rgba(0, 0, 0, 0.3),
                0 0 40px rgba(76, 29, 149, 0.1);
            position: relative;
            overflow: hidden;
        }

        .logo-section {
            text-align: center;
            margin-bottom: 35px;
        }

        .logo {
            font-family: 'Poppins', sans-serif;
            font-size: 2rem;
            font-weight: 700;
            color: #ffffff;
            letter-spacing: -0.5px;
            margin-bottom: 8px;
        }

        .logo span {
            background: linear-gradient(135deg, #a78bfa 0%, #60a5fa 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .subtitle {
            font-family: 'Inter', sans-serif;
            color: #9ca3af;
            font-size: 0.95rem;
            font-weight: 400;
            letter-spacing: 0.5px;
        }

        .form-group {
            margin-bottom: 22px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-family: 'Inter', sans-serif;
            color: #d1d5db;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .input-wrapper {
            position: relative;
        }

        .form-group input {
            width: 100%;
            padding: 14px 16px 14px 44px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 10px;
            color: #ffffff;
            font-family: 'Inter', sans-serif;
            font-size: 0.95rem;
            transition: all 0.2s ease;
            outline: none;
        }

        .form-group input:focus {
            border-color: rgba(139, 92, 246, 0.5);
            background: rgba(255, 255, 255, 0.08);
            box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1);
        }

        .form-group input::placeholder {
            color: #6b7280;
        }

        .input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            font-size: 1rem;
            transition: color 0.2s ease;
        }

        .form-group input:focus + .input-icon {
            color: #a78bfa;
        }

        .login-btn {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #7c3aed 0%, #6366f1 100%);
            border: none;
            border-radius: 10px;
            color: #fff;
            font-family: 'Poppins', sans-serif;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            letter-spacing: 0.3px;
            box-shadow: 0 2px 8px rgba(124, 58, 237, 0.3);
        }

        .login-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(124, 58, 237, 0.4);
            background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
        }

        .login-btn:active {
            transform: translateY(0);
        }

        .error-message {
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.3);
            border-radius: 10px;
            padding: 12px 16px;
            margin-bottom: 20px;
            color: #fca5a5;
            font-family: 'Inter', sans-serif;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .extra-links {
            margin-top: 28px;
            text-align: center;
        }

        .extra-links a {
            color: #a78bfa;
            text-decoration: none;
            font-family: 'Inter', sans-serif;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .extra-links a:hover {
            color: #c4b5fd;
            text-decoration: underline;
        }

        .divider {
            color: #4b5563;
            margin: 0 12px;
        }

        .back-home {
            position: absolute;
            top: 24px;
            left: 24px;
            color: #9ca3af;
            text-decoration: none;
            font-family: 'Inter', sans-serif;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.2s ease;
            z-index: 20;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .back-home:hover {
            color: #ffffff;
        }

        @media (max-width: 480px) {
            .login-card {
                padding: 32px 24px;
            }

            .logo {
                font-size: 1.75rem;
            }

            .subtitle {
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <div class="bg-gradient"></div>

    <a href="home.php" class="back-home">
        <i class="fas fa-arrow-left"></i> Back to Home
    </a>

    <div class="login-container">
        <div class="login-card">
            <div class="logo-section">
                <h1 class="logo"><span>Backstreet Boys</span></h1>
                <p class="subtitle">Welcome back! Please login to continue.</p>
            </div>

            <?php if (!empty($error)): ?>
                <div class="error-message">
                    <i class="fas fa-exclamation-circle"></i>
                    <span><?php echo htmlspecialchars($error); ?></span>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="username">Username</label>
                    <div class="input-wrapper">
                        <input type="text" id="username" name="username" placeholder="Enter your username" required value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>">
                        <i class="fas fa-user input-icon"></i>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-wrapper">
                        <input type="password" id="password" name="password" placeholder="Enter your password" required>
                        <i class="fas fa-lock input-icon"></i>
                    </div>
                </div>

                <button type="submit" class="login-btn">
                    Sign In
                </button>
            </form>

            <div class="extra-links">
                <p style="color: #6b7280; font-size: 0.85rem;">Default login: <strong style="color: #a78bfa;">username: admin</strong> | <strong style="color: #a78bfa;">password: password</strong></p>
            </div>
        </div>
    </div>
</body>
</html>
