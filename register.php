<?php
// register.php
session_start();
require_once 'config/database.php';

 $error = '';
 $success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    if (empty($email) || empty($password) || empty($full_name)) {
        $error = "Semua field wajib diisi.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Format email tidak valid.";
    } elseif (strlen($password) < 8) {
        $error = "Password minimal 8 karakter.";
    } elseif ($password !== $confirm_password) {
        $error = "Konfirmasi password tidak cocok.";
    } else {
        // Cek email sudah ada atau belum
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        
        if ($stmt->rowCount() > 0) {
            $error = "Email sudah terdaftar. Silakan login.";
        } else {
            // Hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Insert ke database
            $sql = "INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)";
            $conn->prepare($sql)->execute([$full_name, $email, $hashed_password]);
            
            $success = "Registrasi berhasil! Silakan login.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - NexaTech AI</title>
    <!-- Menggunakan aset yang sama dengan index agar desain konsisten -->
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
                    <!-- Logo Back to Home -->
                    <div class="text-center mb-4">
                        <a href="index.php" class="navbar-brand text-white text-decoration-none">
                            <i class="fa-solid fa-brain text-gradient me-2"></i>NexaTech<span class="text-primary">AI</span>
                        </a>
                        <h4 class="mt-3 fw-bold">Buat Akun Baru</h4>
                        <p class="text-muted small">Isi data di bawah untuk bergabung</p>
                    </div>

                    <!-- Pesan Error / Sukses -->
                    <?php if(!empty($error)): ?>
                        <div class="alert alert-danger py-2 small">
                            <i class="fa-solid fa-circle-exclamation me-2"></i><?= $error ?>
                        </div>
                    <?php endif; ?>
                    <?php if(!empty($success)): ?>
                        <div class="alert alert-success py-2 small">
                            <i class="fa-solid fa-check-circle me-2"></i><?= $success ?>
                        </div>
                    <?php endif; ?>

                    <form action="register.php" method="POST">
                        <div class="mb-3">
                            <label class="form-label text-muted small">Full Name</label>
                            <input type="text" name="full_name" class="form-control bg-dark text-white border-secondary" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small">Email</label>
                            <input type="email" name="email" class="form-control bg-dark text-white border-secondary" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small">Password (Min. 8 karakter)</label>
                            <input type="password" name="password" class="form-control bg-dark text-white border-secondary" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label text-muted small">Confirm Password</label>
                            <input type="password" name="confirm_password" class="form-control bg-dark text-white border-secondary" required>
                        </div>
                        <button type="submit" class="btn btn-primary-custom w-100 mb-3">Register</button>
                        <p class="text-center text-muted small mb-0">Sudah punya akun? <a href="login.php" class="text-primary text-decoration-none">Login di sini</a></p>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>