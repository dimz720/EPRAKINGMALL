<?php
require_once 'connection.php';

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = mysqli_real_escape_string($con, $_POST['password']);
    $role = mysqli_real_escape_string($con, $_POST['role']);

    $roles = [
        '1' => 'User',
        '2' => 'Owner',
        '3' => 'Admin'
    ];

    if (!array_key_exists($role, $roles)) {
        $message = "Peran tidak valid!";
    } else {
        if ($role == 1) {
            $sql = "SELECT * FROM users WHERE email = '$email' AND role = '$role'";
        } elseif ($role == 2) {
            $sql = "SELECT o.*, g.id as garage_id 
                    FROM owners o 
                    LEFT JOIN garages g ON o.id = g.owner_id 
                    WHERE o.email = '$email' AND o.role = '$role'";
        } else {
            $sql = "SELECT * FROM admin WHERE email = '$email' AND role = '$role'";
        }

        $result = mysqli_query($con, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);

            if ($user['status'] == 'suspended') {
                $message = "Akun Anda sedang dalam status suspended. Silakan hubungi administrator 0812 1119 9999";
            } else {
                if ($role == 3) {
                    if ($password === $user['password']) {
                        session_start();
                        $_SESSION['user_id'] = $user['id'];
                        $_SESSION['fullname'] = $user['fullname'];
                        $_SESSION['role'] = $user['role'];

                        header('Location: adminhome.php');
                        exit;
                    } else {
                        $message = "Password salah!";
                    }
                } else {
                    if (password_verify($password, $user['password'])) {
                        session_start();
                        $_SESSION['user_id'] = $user['id'];
                        $_SESSION['fullname'] = $user['fullname'];
                        $_SESSION['role'] = $user['role'];

                        switch ($roles[$role]) {
                            case 'User':
                                header('Location: car.php');
                                break;
                            case 'Owner':
                                if ($user['garage_id']) {
                                    header("Location: ownerhome.php?garage_id=" . $user['garage_id']);
                                } else {
                                    header("Location: addgarage.php");
                                }
                                break;
                        }
                        exit;
                    } else {
                        $message = "Password salah!";
                    }
                }
            }
        } else {
            $message = "Email tidak ditemukan!";
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
    <title>Login - eParking Mall</title>
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

        .login-container {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            width: 90%;
            max-width: 400px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
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

        select {
            width: 100%;
            padding: 1rem;
            margin-bottom: 1rem;
            border: 1px solid #ddd;
            border-radius: 8px;
            color: #666;
            font-size: 1rem;
            appearance: none;
            background: white;
            cursor: pointer;
        }

        .checkbox-group {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #666;
        }

        .forgot-password {
            color: #666;
            text-decoration: none;
        }

        .login-btn {
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

        .signup-link {
            text-align: center;
            font-size: 0.9rem;
            color: #666;
        }

        .signup-link a {
            color: #00855D;
            text-decoration: none;
            font-weight: bold;
        }

        .error-message {
            color: red;
            font-size: 0.9rem;
            text-align: center;
            margin-bottom: 1rem;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <button class="close-btn" onclick="window.location.href='index.php'">×</button>
        <h1>Login</h1>
        <form method="POST">
            <?php if ($message): ?>
                <div class="error-message"><?= $message ?></div>
            <?php endif; ?>
            <div class="input-group">
                <i class="uil uil-envelope-alt email"></i>
                <input type="text" name="email" placeholder="Masukkan email" required value="<?= isset($_POST['email']) ? $_POST['email'] : '' ?>">
            </div>
            <div class="input-group">
                <i class="uil uil-lock password"></i>
                <input type="password" name="password" placeholder="Masukkan password" required>
            </div>
            <select name="role" required>
                <option value="1" <?= isset($_POST['role']) && $_POST['role'] == '1' ? 'selected' : '' ?>>User</option>
                <option value="2" <?= isset($_POST['role']) && $_POST['role'] == '2' ? 'selected' : '' ?>>Owner</option>
                <option value="3" <?= isset($_POST['role']) && $_POST['role'] == '3' ? 'selected' : '' ?>>Admin</option>
            </select>
            <div class="checkbox-group">
                <label class="remember-me">
                    <input type="checkbox">
                    Ingat saya
                </label>
                <a href="./resetpassword.php" class="forgot-password">Lupa kata sandi?</a>
            </div>
            <button type="submit" class="login-btn">Masuk Sekarang</button>
            <div class="signup-link">
                Belum punya akun? <a href="./signup.php">Signup</a>
            </div>
        </form>
    </div>
</body>

</html>