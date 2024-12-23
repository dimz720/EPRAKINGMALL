<?php
session_start();
session_destroy(); // Menghapus sesi
header('Location: signin.php'); // Arahkan kembali ke halaman login
exit;
?>
