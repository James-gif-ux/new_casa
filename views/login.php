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
    <link rel="stylesheet" href="../assets/css/login.css">
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
    <a href="home.php" class="home-link">
        <i class="fas fa-home"></i>
    </a>
</body>
</html>
