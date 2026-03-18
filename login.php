<?php
include 'includes/db.php';
include 'includes/user_auth.php';

// Already logged in → go home
if (isUserLoggedIn()) {
    header('Location: index.php');
    exit;
}

$error    = '';
$redirect = $_GET['redirect'] ?? 'index.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email']    ?? '');
    $password =       $_POST['password'] ?? '';

    if (!$email || !$password) {
        $error = 'Please fill in all fields.';
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id']    = $user['id'];
            $_SESSION['user_name']  = $user['name'];
            $_SESSION['user_email'] = $user['email'];

            header('Location: ' . $redirect);
            exit;
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
    <title>Sign In – Premium OTT Store</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="stylesheet" href="assets/css/styles.css?v=1.2">
    <link rel="icon" type="image/png" href="assets/img/favicon.png">
    <style>
        body { min-height: 100vh; display: flex; flex-direction: column; align-items: center; justify-content: center; background: var(--bg-secondary); padding: 20px; }
        .auth-card { background: var(--bg-primary); border: 1px solid var(--border); border-radius: var(--radius-lg); padding: 40px 36px; width: 100%; max-width: 420px; box-shadow: var(--shadow-lg); }
        .auth-logo { text-align: center; margin-bottom: 28px; }
        .auth-logo img { height: 50px; }
        .auth-title { font-size: 22px; font-weight: 800; text-align: center; margin-bottom: 6px; }
        .auth-subtitle { font-size: 14px; color: var(--text-muted); text-align: center; margin-bottom: 28px; }
        .auth-error { background: rgba(239,68,68,.1); color: #f87171; border: 1px solid rgba(239,68,68,.2); padding: 12px 16px; border-radius: var(--radius-sm); font-size: 13px; font-weight: 600; margin-bottom: 20px; text-align: center; }
        .form-group { margin-bottom: 18px; }
        .form-group label { display: flex; align-items: center; gap: 6px; font-size: 13px; font-weight: 600; color: var(--text-secondary); margin-bottom: 8px; }
        .form-group label i { width: 14px; height: 14px; }
        .form-group input { width: 100%; background: #1a1a1a; border: 1px solid var(--border); border-radius: var(--radius-sm); color: var(--text-primary); font-size: 14px; font-family: inherit; padding: 12px 14px; outline: none; transition: border-color .2s; }
        .form-group input:focus { border-color: var(--primary); }
        .auth-submit { width: 100%; margin-top: 8px; }
        .auth-footer { text-align: center; font-size: 13px; color: var(--text-muted); margin-top: 20px; }
        .auth-footer a { color: var(--primary); font-weight: 600; }
    </style>
</head>
<body>
    <div class="auth-card">
        <div class="auth-logo">
            <a href="index.php"><img src="assets/img/logo.png" alt="Premium OTT Store"></a>
        </div>
        <h2 class="auth-title">Welcome Back</h2>
        <p class="auth-subtitle">Sign in to access your deals</p>

        <?php if ($error): ?>
            <div class="auth-error"><i data-lucide="alert-circle" style="width:14px;height:14px;display:inline;margin-right:6px;"></i><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST" action="login.php?redirect=<?php echo urlencode($redirect); ?>">
            <div class="form-group">
                <label><i data-lucide="mail"></i> Email Address</label>
                <input type="email" name="email" placeholder="you@example.com" required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label><i data-lucide="lock"></i> Password</label>
                <input type="password" name="password" placeholder="••••••••" required>
            </div>
            <button type="submit" class="btn-primary auth-submit">
                <span>Sign In</span>
                <i data-lucide="log-in" style="width:18px;height:18px;"></i>
            </button>
        </form>

        <div class="auth-footer">
            Don't have an account? <a href="register.php?redirect=<?php echo urlencode($redirect); ?>">Create one free</a>
        </div>
    </div>
    <script>lucide.createIcons();</script>
</body>
</html>
