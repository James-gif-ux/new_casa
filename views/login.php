<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$_SESSION['active_menu'] = 'dashboard';
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <style>
        :root {
            --primary-color: #ac7a61;
            --secondary-color: #8b5e3c;
            --error-color: #ff4757;
            --input-bg: #f8f9fa;
            --input-border: #e9ecef;
            --text-color: #495057;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', system-ui, sans-serif;
        }

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: url('../images/menu-bg.png') center center;
            background-size: cover;
            padding: 1.25rem;
        }

        .container {
            width: 100%;
            max-width: 56.25rem;
            min-height: 31.25rem;
            display: flex;
            border-radius: 1.25rem;
            overflow: hidden;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(0.625rem);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 0.5rem 2rem rgba(31, 38, 135, 0.2);
            position: relative;
        }

        .left-panel {
            flex: 1;
            padding: 2.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            background: url('../images/villas.jpg') center center;
            background-size: cover;
        }

        .brand-logo {
            position: absolute;
            top: 1.25rem;
            left: 1.25rem;
            width: 5.625rem;
            height: 5.625rem;
            border-radius: 50%;
            object-fit: cover;
        }

        .form-container {
            flex: 1;
            display: flex;
            flex-direction: column;
            background: rgba(255, 255, 255, 0.9);
            position: relative;
        }

        .login-form {
            padding: clamp(1.25rem, 4vw, 3.125rem);
            display: flex;
            flex-direction: column;
            gap: 1.5625rem;
        }

        .form-header {
            margin-bottom: 1.25rem;
        }

        .form-title {
            font-family: Impact, sans-serif;
            color: var(--primary-color);
            font-size: clamp(1.5rem, 3vw, 2rem);
        }

        .input-group {
            position: relative;
        }

        .form-input {
            width: 100%;
            padding: 0.9375rem;
            border: 0.125rem solid var(--input-border);
            background: var(--input-bg);
            border-radius: 0.75rem;
            font-size: 1rem;
            color: var(--text-color);
            transition: all 0.3s ease;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--primary-color);
            transform: translateY(-0.125rem);
            box-shadow: 0 0.375rem 0.75rem rgba(0, 0, 0, 0.15);
        }

        .login-button {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 0.9375rem;
            border: none;
            border-radius: 0.75rem;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 0.25rem 0.9375rem rgba(172, 122, 97, 0.3);
            margin-bottom: 3.125rem;
        }

        .login-button:hover {
            transform: translateY(-0.125rem);
            box-shadow: 0 0.375rem 0.75rem rgba(172, 122, 97, 0.4);
        }

        .home-link {
            position: fixed;
            bottom: 1.25rem;
            right: 1.25rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 3.125rem;
            height: 3.125rem;
            color: var(--primary-color);
            background: rgba(172, 122, 97, 0.1);
            border-radius: 50%;
            text-decoration: none;
            transition: all 0.3s ease;
            border: 1px solid rgba(172, 122, 97, 0.2);
            box-shadow: 0 0.125rem 0.5rem rgba(172, 122, 97, 0.1);
            z-index: 1000;
        }

        .home-link i {
            font-size: clamp(1rem, 2vw, 1.25rem);
        }

        .home-link:hover {
            background: rgba(172, 122, 97, 0.2);
            transform: translateY(-0.125rem);
        }

        .error-message {
            background: rgba(255, 71, 87, 0.1);
            border: 1px solid rgba(255, 71, 87, 0.2);
            color: var(--error-color);
            padding: 0.75rem;
            border-radius: 0.5rem;
            text-align: center;
            font-size: 0.875rem;
        }

        @media screen and (max-width: 48rem) {
            .container {
                flex-direction: column;
                margin: 1rem;
            }

            .left-panel {
                min-height: 12.5rem;
                padding: 1.25rem;
            }

            .brand-logo {
                width: 4.375rem;
                height: 4.375rem;
            }

            .login-form {
                padding: 1.875rem 1.25rem;
            }

            .home-link {
                width: 2.5rem;
                height: 2.5rem;
                bottom: 3.90rem;
                right: 14rem;
            }
        }

        @media screen and (max-width: 30rem) {
            body {
                padding: 0.625rem;
            }

            .container {
                margin: 0.625rem;
                border-radius: 0.9375rem;
            }

            .form-input,
            .login-button {
                padding: 0.75rem;
            }

            .home-link {
                width: 2.25rem;
                height: 2.25rem;
                bottom: 0.75rem;
                right: 0.75rem;
            }
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <div class="left-panel">
            <img src="../images/logo.jpg" alt="Logo" class="brand-logo">
        </div>
        <div class="form-container">
            <form action="../pages/authentication.php?function=login&&sub_page=loggedin" method="POST" class="login-form">
                <div class="form-header">
                    <h1 class="form-title">CASA MARCOS ADMIN</h1>
                </div>
                <div class="input-group">
                    <input type="text" 
                           name="username" 
                           class="form-input" 
                           id="username" 
                           aria-label="username"
                           placeholder="Username" 
                           required>
                </div>
                <div class="input-group">
                    <input type="password" 
                           name="password" 
                           class="form-input" 
                           id="password" 
                           aria-label="password" 
                           placeholder="Password" 
                           required>
                </div>
                <button type="submit" class="login-button">Login</button>
                <?php if(isset($msg)): ?>
                    <div class="error-message">
                        <?php echo htmlspecialchars($msg); ?>
                    </div>
                <?php endif; ?>
            </form>
        </div>
    </div>
    <a href="../../index.php" class="home-link">
        <i class="fas fa-home"></i>
    </a>
</body>
</html>
