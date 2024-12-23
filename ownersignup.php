<?php
session_start();
require_once 'connection.php';

$message = "";
$redirect = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = mysqli_real_escape_string($con, $_POST['fullname']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $phone = mysqli_real_escape_string($con, $_POST['phone']);
    $password = mysqli_real_escape_string($con, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($con, $_POST['confirmPassword']);
    $license = mysqli_real_escape_string($con, $_POST['license']);
    $check_email = mysqli_query($con, "SELECT * FROM owners WHERE email = '$email'");
    if (mysqli_num_rows($check_email) > 0) {
        $message = "Email sudah terdaftar!";
    }
    elseif (!preg_match('/^\+62[0-9]{9,15}$/', $phone)) {
        $message = "Nomor telepon harus diawali dengan +62 dan mengandung 9-15 digit.";
    } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/', $password)) {
        $message = "Kata sandi harus minimal 8 karakter dan mengandung huruf besar, huruf kecil, angka, serta karakter khusus.";
    } elseif ($password !== $confirm_password) {
        $message = "Kata sandi tidak cocok!";
    } else {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $sql = "INSERT INTO owners (fullname, email, phone, password, license, status, role) 
                VALUES ('$fullname', '$email', '$phone', '$hashed_password', '$license', 'active', 2)";
        if (mysqli_query($con, $sql)) {
            $_SESSION['owner_id'] = mysqli_insert_id($con);
            $_SESSION['fullname'] = $fullname;
            $_SESSION['email'] = $email;
            $_SESSION['role'] = 2;
            $redirect = true;
        } else {
            $message = "Error: " . mysqli_error($con);
        }
    }
}

if ($redirect) {
    header('Location: addgarage.php');
    exit;
}
?>
<?php
include_once 'header.php';
echo getHeaderHtml();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Daftar - eParking Mall</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen, Ubuntu, Cantarell, sans-serif; }
        body { background-color: #f5f5f5; }
        .container { max-width: 1200px; margin: 2rem auto; padding: 2rem; background-color: white; border-radius: 12px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); }
        h2 { color: #333; margin-bottom: 1.5rem; font-size: 1.8rem; padding-bottom: 0.5rem; }
        .form-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; margin-bottom: 2rem; }
        .form-group { margin-bottom: 1rem; }
        .form-group label { display: block; margin-bottom: 0.5rem; color: #555; }
        .form-group input { width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 8px; background-color: #f0f4ff; font-size: 1rem; color: #333; }
        .form-group input:focus { outline: none; box-shadow: 0 0 0 2px rgba(45, 90, 39, 0.2); }
        .submit-btn { background-color: #00855D; color: white; padding: 1rem 2rem; border: none; border-radius: 8px; font-size: 1rem; cursor: pointer; width: 100%; max-width: 200px; margin: 1.5rem auto; display: block; transition: background-color 0.3s; }
        .submit-btn:hover { background-color: #00855D; }
        .license-section { grid-column: span 3; }
        .swal2-popup { border: none !important; outline: none !important; }
    </style>
</head>
<body>
    <div class="container">
        <form method="POST" action="ownersignup.php">
            <h2>Data Pribadi</h2>
            <div class="form-grid">
                <div class="form-group">
                    <label for="fullname">Nama Lengkap</label>
                    <input type="text" id="fullname" name="fullname" value="<?= htmlspecialchars($fullname ?? '') ?>" placeholder="Masukkan nama lengkap" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($email ?? '') ?>" placeholder="Masukkan email" required>
                </div>
                <div class="form-group">
                    <label for="phone">Nomor Telepon</label>
                    <input type="tel" id="phone" name="phone" value="<?= htmlspecialchars($phone ?? '') ?>" placeholder="Masukkan nomor telepon" required>
                </div>
                <div class="form-group">
                    <label for="password">Buat Kata Sandi</label>
                    <input type="password" id="password" name="password" value="<?= htmlspecialchars($password ?? '') ?>" placeholder="Masukkan kata sandi" required>
                </div>
                <div class="form-group">
                    <label for="confirmPassword">Konfirmasi Kata Sandi</label>
                    <input type="password" id="confirmPassword" name="confirmPassword" value="<?= htmlspecialchars($confirm_password ?? '') ?>" placeholder="Konfirmasi kata sandi" required>
                </div>
            </div>

            <h2>Data Garasi</h2>
            <div class="form-grid">
                <div class="form-group license-section">
                    <label for="license">Nomor Lisensi</label>
                    <input type="text" id="license" name="license" value="<?= htmlspecialchars($license ?? '') ?>" placeholder="Masukkan nomor lisensi" required>
                </div>
            </div>

            <button type="submit" class="submit-btn">DAFTAR</button>
        </form>
    </div>
    <?php include 'footer.php'; ?>

    <?php if (!empty($message)): ?>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: '<?= $message ?>',
        });
    </script>
    <?php endif; ?>
</body>
</html>
