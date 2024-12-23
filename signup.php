<?php
require_once 'connection.php';
$message = "";
$fullname = $email = $phone = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = mysqli_real_escape_string($con, $_POST['fullname']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $phone = mysqli_real_escape_string($con, $_POST['phone']);
    $password = mysqli_real_escape_string($con, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($con, $_POST['confirm_password']);

    if (!preg_match('/^\+62[0-9]{9,15}$/', $phone)) {
        $message = "Nomor telepon harus diawali dengan +62 dan mengandung 9-15 digit.";
    } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/', $password)) {
        $message = "Kata sandi harus minimal 8 karakter dan mengandung huruf besar, huruf kecil, angka, serta karakter khusus.";
    } elseif ($password !== $confirm_password) {
        $message = "Kata sandi tidak cocok!";
    } else {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $sql = "INSERT INTO users (fullname, email, phone, password, status, role) 
                VALUES ('$fullname', '$email', '$phone', '$hashed_password', 'active', 1)";
        if (mysqli_query($con, $sql)) {
            header('Location: signin.php');
            exit;
        } else {
            $message = "Error: " . mysqli_error($con);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Daftar - eParking Mall</title>
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

        .signup-container {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 400px;
            position: relative;
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

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 2rem;
            font-size: 1.8rem;
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

        .signup-btn {
            width: 100%;
            padding: 1rem;
            background: #00855D;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            cursor: pointer;
            margin-bottom: 1rem;
        }

        .login-link {
            text-align: center;
            font-size: 0.9rem;
            color: #666;
        }

        .login-link a {
            color: #00855D;
            text-decoration: none;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="signup-container">
        <button class="close-btn" onclick="window.location.href='index.php'">×</button>
        <h1>Sign Up</h1>
        <form method="POST">
            <div class="input-group">
                <i class="uil uil-user"></i>
                <input type="text" name="fullname" placeholder="Masukkan nama lengkap" value="<?= htmlspecialchars($fullname) ?>" required>
            </div>
            <div class="input-group">
                <i class="uil uil-envelope-alt"></i>
                <input type="email" name="email" placeholder="Masukkan email" value="<?= htmlspecialchars($email) ?>" required>
            </div>
            <div class="input-group">
                <i class="uil uil-phone-alt"></i>
                <input type="tel" name="phone" placeholder="Masukkan nomor telepon" value="<?= htmlspecialchars($phone) ?>" required>
            </div>
            <div class="input-group">
                <i class="uil uil-lock"></i>
                <input type="password" name="password" placeholder="Buat kata sandi" required>
            </div>
            <div class="input-group">
                <i class="uil uil-lock"></i>
                <input type="password" name="confirm_password" placeholder="Konfirmasi kata sandi" required>
            </div>
            <button type="submit" class="signup-btn">Daftar Sekarang</button>
            <p class="login-link">Sudah punya akun? <a href="./signin.php">Login</a></p>
        </form>
    </div>
    <?php if ($message): ?>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Kesalahan Validasi',
                text: '<?= $message ?>',
            });
        </script>
    <?php endif; ?>
</body>

</html>