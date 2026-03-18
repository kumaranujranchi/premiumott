<?php
include 'includes/db.php';
include 'includes/user_auth.php';

// Already logged in → go home
if (isUserLoggedIn()) {
    header('Location: index');
    exit;
}

$error = '';
$success = '';
$redirect = $_GET['redirect'] ?? 'index';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name']     ?? '');
    $email    = trim($_POST['email']    ?? '');
    $password =       $_POST['password'] ?? '';
    $confirm  =       $_POST['confirm']  ?? '';

    if (!$name || !$email || !$password || !$confirm) {
        $error = 'Please fill in all fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif (strlen($password) < 8) {
        $error = 'Password must be at least 8 characters.';
    } elseif ($password !== $confirm) {
        $error = 'Passwords do not match.';
    } else {
        // Check for duplicate email
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $error = 'An account with that email already exists.';
        } else {
            $hash = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
            $stmt->execute([$name, $email, $hash]);

            // Auto-login after registration
            $userId = $pdo->lastInsertId();
            $_SESSION['user_id']    = $userId;
            $_SESSION['user_name']  = $name;
            $_SESSION['user_email'] = $email;

            header('Location: ' . $redirect);
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account – Premium OTT Store</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="stylesheet" href="assets/css/styles.css?v=1.2">
    <link rel="icon" type="image/png" href="assets/img/favicon.png">
    <style>
        body { min-height: 100vh; display: flex; flex-direction: column; align-items: center; justify-content: center; background: var(--bg-secondary); padding: 20px; }
        .auth-card { background: var(--bg-primary); border: 1px solid var(--border); border-radius: var(--radius-lg); padding: 40px 36px; width: 100%; max-width: 460px; box-shadow: var(--shadow-lg); }
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
            <a href="index"><img src="assets/img/logo.png" alt="Premium OTT Store"></a>
        </div>
        <h2 class="auth-title">Create Your Account</h2>
        <p class="auth-subtitle">Join thousands of happy customers</p>

        <?php if ($error): ?>
            <div class="auth-error"><i data-lucide="alert-circle" style="width:14px;height:14px;display:inline;margin-right:6px;"></i><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST" action="register?redirect=<?php echo urlencode($redirect); ?>">
            <div class="form-group">
                <label><i data-lucide="user"></i> Full Name</label>
                <input type="text" name="name" placeholder="John Smith" required value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label><i data-lucide="mail"></i> Email Address</label>
                <input type="email" name="email" placeholder="you@example.com" required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label><i data-lucide="lock"></i> Password</label>
                <input type="password" name="password" placeholder="Min. 8 characters" required minlength="8">
            </div>
            <div class="form-group">
                <label><i data-lucide="lock"></i> Confirm Password</label>
                <input type="password" name="confirm" placeholder="Repeat your password" required minlength="8">
            </div>
            <button type="submit" class="btn-primary auth-submit">
                <span>Create Account</span>
                <i data-lucide="arrow-right" style="width:18px;height:18px;"></i>
            </button>
        </form>

        <div class="auth-footer">
            Already have an account? <a href="login?redirect=<?php echo urlencode($redirect); ?>">Sign in</a>
        </div>
    </div>
    <script>lucide.createIcons();</script>
</body>
</html>
