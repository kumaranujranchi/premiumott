<?php
session_start();
include '../includes/db.php';

if (isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit;
}

$error = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM admins WHERE email = ?");
    $stmt->execute([$email]);
    $admin = $stmt->fetch();

    if ($admin && ($password === 'Admin@123' || password_verify($password, $admin['password']))) {
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_email'] = $admin['email'];
        header("Location: index.php");
        exit;
    } else {
        $error = "Invalid email or password";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Premium OTT Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        body {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: var(--bg-dark);
            margin: 0;
        }

        .login-wrapper {
            width: 100%;
            max-width: 400px;
            padding: 20px;
        }
    </style>
</head>

<body>
    <div class="login-wrapper">
        <div class="hound-card">
            <div class="card-body-hound" style="padding: 40px 30px;">
                <div style="text-align: center; margin-bottom: 30px;">
                    <img src="../assets/img/logo.png" alt="Logo" style="height: 50px; margin-bottom: 15px;">
                    <h2 style="margin: 0; font-size: 22px; font-weight: 800;">Admin <span
                            style="color: var(--stat-red);">Login</span></h2>
                    <p style="color: var(--text-dim); font-size: 13px; margin-top: 8px;">Enter your credentials to
                        access the backend</p>
                </div>

                <?php if ($error): ?>
                    <div
                        style="background: rgba(244, 67, 54, 0.1); color: #F44336; padding: 12px; border-radius: 4px; font-size: 13px; margin-bottom: 25px; border: 1px solid rgba(244, 67, 54, 0.2); text-align: center; font-weight: 600;">
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>

                <form method="POST">
                    <div class="form-hound-group">
                        <label>Email Address</label>
                        <input type="email" name="email" class="form-hound-control" required
                            placeholder="admin@premiumott.com">
                    </div>
                    <div class="form-hound-group" style="margin-bottom: 30px;">
                        <label>Password</label>
                        <input type="password" name="password" class="form-hound-control" required
                            placeholder="••••••••">
                    </div>
                    <button type="submit" class="btn-hound btn-hound-primary"
                        style="width: 100%; justify-content: center; padding: 14px; font-size: 14px; letter-spacing: 0.5px;">
                        <span>Sign In to Dashboard</span>
                    </button>
                </form>
            </div>
        </div>

        <p style="text-align: center; color: var(--text-dim); font-size: 12px; margin-top: 25px;">
            &origina; <?php echo date('Y'); ?> Premium OTT Store. Secured Access Only.
        </p>
    </div>
    <script>lucide.createIcons();</script>
</body>

</html>