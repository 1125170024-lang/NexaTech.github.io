<?php
// submit_testimonial.php
session_start();
require_once 'config/database.php';

// Cek apakah user sudah login
if(!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true){
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $job_title = trim($_POST['job_title']);
    $message = trim($_POST['message']);

    if (!empty($message) && !empty($job_title)) {
        $sql = "INSERT INTO testimonials (user_id, job_title, message) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        
        if($stmt->execute([$user_id, $job_title, $message])){
            // Set flash message sukses
            $_SESSION['testimonial_success'] = "Testimoni berhasil dikirim!";
            header("Location: index.php#testimonials");
            exit;
        } else {
            $_SESSION['testimonial_error'] = "Gagal mengirim testimoni.";
            header("Location: index.php#testimonials");
            exit;
        }
    } else {
        $_SESSION['testimonial_error'] = "Jabatan dan Isi Testimoni wajib diisi.";
        header("Location: index.php#testimonials");
        exit;
    }
} else {
    header("Location: index.php");
    exit;
}
?>