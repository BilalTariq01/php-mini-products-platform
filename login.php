<?php
require_once 'config/session.php';
require_once 'models/User.php';

// Redirect if already logged in
if (SessionManager::isLoggedIn()) {
    header('Location: dashboard.php');
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    if (empty($email) || empty($password)) {
        $error = 'Please fill in all fields.';
    } else {
        $user = new User();
        if ($user->login($email, $password)) {
            SessionManager::login($user->id, $user->email);
            header('Location: dashboard.php');
            exit();
        } else {
            $error = 'Invalid email or password.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Mini Platform</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="login-container">
        <form class="login-form" method="POST">
            <h2>Login to Mini Platform</h2>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required 
                       value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
            </div>
            
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <button type="submit" class="btn btn-primary btn-full">Login</button>
            
            <div style="margin-top: 20px; text-align: center; color: #7f8c8d;">
                <small>Demo credentials: admin@example.com / admin123</small>
            </div>
        </form>
    </div>
</body>
</html>
