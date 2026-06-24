<?php
// login.php
session_start();
require_once 'config/database.php';

// Jika sudah login, langsung lempar ke index
if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true){
    header("Location: index.php");
    exit;
}

 $error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        $error = "Email dan Password wajib diisi.";
    } else {
        $stmt = $conn->prepare("SELECT id, full_name, password FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            // Buat Session
            $_SESSION['logged_in'] = true;
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['full_name'];
            
            // Redirect ke index
            header("Location: index.php");
            exit;
        } else {
            $error = "Email atau password salah.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - NexaTech AI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="d-flex align-items-center" style="min-height: 100vh; background-color: var(--bg-dark);">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="glass-card p-4 p-md-5">
                    <div class="text-center mb-4">
                        <a href="index.php" class="navbar-brand text-white text-decoration-none">
                            <i class="fa-solid fa-brain text-gradient me-2"></i>NexaTech<span class="text-primary">AI</span>
                        </a>
                        <h4 class="mt-3 fw-bold">Selamat Datang Kembali</h4>
                        <p class="text-muted small">Masuk ke akun NexaTech AI Anda</p>
                    </div>

                    <?php if(!empty($error)): ?>
                        <div class="alert alert-danger py-2 small">
                            <i class="fa-solid fa-circle-exclamation me-2"></i><?= $error ?>
                        </div>
                    <?php endif; ?>

                    <!-- Notifikasi jika baru saja register -->
                    <?php if(isset($_GET['register_success'])): ?>
                        <div class="alert alert-success py-2 small">
                            <i class="fa-solid fa-check-circle me-2"></i>Registrasi berhasil! Silakan login.
                        </div>
                    <?php endif; ?>

                    <form action="login.php" method="POST">
                        <div class="mb-3">
                            <label class="form-label text-muted small">Email</label>
                            <input type="email" name="email" class="form-control bg-dark text-white border-secondary" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label text-muted small">Password</label>
                            <input type="password" name="password" class="form-control bg-dark text-white border-secondary" required>
                        </div>
                        <button type="submit" class="btn btn-primary-custom w-100 mb-3">Login</button>
                        <p class="text-center text-muted small mb-0">Belum punya akun? <a href="register.php" class="text-primary text-decoration-none">Register di sini</a></p>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>