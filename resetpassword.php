<?php
require_once 'connection.php';

$message = "";
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $new_password = mysqli_real_escape_string($con, $_POST['new_password']);
    $confirm_password = mysqli_real_escape_string($con, $_POST['confirm_password']);

    $password_valid = preg_match('/[A-Z]/', $new_password) &&
        preg_match('/[a-z]/', $new_password) &&
        preg_match('/\d/', $new_password) &&
        preg_match('/[^a-zA-Z\d]/', $new_password) &&
        strlen($new_password) >= 8;

    if (!$password_valid) {
        $message = "Password harus minimal 8 karakter dan mengandung huruf besar, huruf kecil, angka, dan karakter khusus!";
    } elseif ($new_password !== $confirm_password) {
        $message = "Password tidak cocok!";
    } else {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        $sql_users = "SELECT id FROM users WHERE email = ?";
        $sql_owners = "SELECT id FROM owners WHERE email = ?";

        $email_found = false;

        if ($stmt = mysqli_prepare($con, $sql_users)) {
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            $result_users = mysqli_stmt_get_result($stmt);
            if (mysqli_num_rows($result_users) > 0) {
                $email_found = true;
                $sql_update = "UPDATE users SET password = ? WHERE email = ?";
                if ($stmt_update = mysqli_prepare($con, $sql_update)) {
                    mysqli_stmt_bind_param($stmt_update, "ss", $hashed_password, $email);
                    if (mysqli_stmt_execute($stmt_update)) {
                        $message = "Password berhasil diperbarui untuk pengguna!";
                        $success = true;
                    } else {
                        $message = "Gagal memperbarui password pengguna: " . mysqli_error($con);
                    }
                }
            }
        }

        if (!$email_found) {
            if ($stmt = mysqli_prepare($con, $sql_owners)) {
                mysqli_stmt_bind_param($stmt, "s", $email);
                mysqli_stmt_execute($stmt);
                $result_owners = mysqli_stmt_get_result($stmt);
                if (mysqli_num_rows($result_owners) > 0) {
                    $email_found = true;
                    $sql_update = "UPDATE owners SET password = ? WHERE email = ?";
                    if ($stmt_update = mysqli_prepare($con, $sql_update)) {
                        mysqli_stmt_bind_param($stmt_update, "ss", $hashed_password, $email);
                        if (mysqli_stmt_execute($stmt_update)) {
                            $message = "Password berhasil diperbarui untuk pemilik!";
                            $success = true;
                        } else {
                            $message = "Gagal memperbarui password pemilik: " . mysqli_error($con);
                        }
                    }
                }
            }
        }

        if (!$email_found) {
            $message = "Email tidak ditemukan dalam database kami!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background-color: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .reset-container {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            position: relative;
        }

        h2 {
            text-align: center;
            margin-bottom: 1.5rem;
            color: #333;
            font-size: 1.5rem;
        }

        .input-group {
            margin-bottom: 1rem;
            background-color: #f0f4ff;
            border-radius: 8px;
            padding: 0.8rem;
            display: flex;
            align-items: center;
        }

        .input-group input {
            border: none;
            background: none;
            width: 100%;
            padding: 0.5rem;
            font-size: 1rem;
            color: #333;
            outline: none;
        }

        .input-group i {
            color: #666;
            margin-right: 0.5rem;
        }

        .reset-btn {
            width: 100%;
            padding: 1rem;
            background-color: #00855D;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            cursor: pointer;
            margin-bottom: 1rem;
        }

        .login-link {
            text-align: center;
            color: #666;
        }

        .login-link a {
            color: #00855D;
            text-decoration: none;
            font-weight: 500;
        }

        .description {
            text-align: center;
            color: #666;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
        }

        .close-btn {
            position: absolute;
            right: 1.5rem;
            top: 1.5rem;
            font-size: 1.5rem;
            cursor: pointer;
            color: #666;
            background: none;
            border: none;
        }
    </style>
</head>

<body>
    <div class="reset-container">
        <button class="close-btn" onclick="window.location.href='index.php'">×</button>
        <h2>Reset Password</h2>
        <p class="description">Masukkan email Anda dan buat password baru untuk reset akun Anda.</p>

        <form method="POST">
            <div class="input-group">
                <i class="uil uil-envelope-alt email"></i>
                <input type="email" name="email" placeholder="Masukkan email Anda" required>
            </div>
            <div class="input-group">
                <i class="uil uil-lock password"></i>
                <input type="password" name="new_password" placeholder="Password baru" required>
            </div>
            <div class="input-group">
                <i class="uil uil-lock password"></i>
                <input type="password" name="confirm_password" placeholder="Konfirmasi password baru" required>
            </div>
            <button type="submit" class="reset-btn">Reset Password</button>
            <p class="login-link">Ingat password Anda? <a href="./signin.php">Login</a></p>
        </form>
    </div>

    <?php if ($message): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: '<?= $success ? "Berhasil!" : "Gagal!" ?>',
                    text: "<?= $message ?>",
                    icon: '<?= $success ? "success" : "error" ?>',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed && <?= $success ? "true" : "false" ?>) {
                        window.location.href = './signin.php';
                    }
                });
            });
        </script>
    <?php endif; ?>
</body>

</html>