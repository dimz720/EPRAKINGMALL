<?php
include_once 'Connection.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$isLoggedIn = isset($_SESSION['user_id']);
$fullname = $isLoggedIn ? $_SESSION['fullname'] : null;
$role = $isLoggedIn ? $_SESSION['role'] : null;

function getHeaderHtml() {
    global $isLoggedIn, $role;
    ob_start();
    ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }

        .header {
            background-color: #00855D;
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            flex-wrap: wrap;
        }

        .logo {
            color: white;
            font-size: 24px;
            font-weight: bold;
            text-decoration: none;
            transition: color 0.3s;
            margin-right: 20px;
        }

        .logo:hover {
            color: #00b09b;
        }

        .nav-icons {
            display: flex;
            gap: 15px;
            align-items: center;
            flex-wrap: wrap;
        }

        .icon-btn {
            background: none;
            border: none;
            color: white;
            cursor: pointer;
            font-size: 24px;
            padding: 10px;
            border-radius: 5px;
            transition: background 0.3s, transform 0.3s;
        }

        .icon-btn:hover {
            background-color: rgba(255, 255, 255, 0.1);
            transform: scale(1.1);
        }

        .get-started-btn {
            background: linear-gradient(135deg, #00b09b, #96c93d);
            padding: 10px 20px;
            border-radius: 50px;
            color: white;
            font-size: 16px;
            font-weight: bold;
            text-decoration: none;
            text-align: center;
            transition: all 0.3s ease;
        }

        .get-started-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 15px rgba(0, 176, 155, 0.3);
        }

        .get-started-btn:active {
            transform: translateY(0);
            box-shadow: none;
        }

        .dropdown {
            position: relative;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            background-color: white;
            min-width: 160px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            border-radius: 5px;
            z-index: 1;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        .dropdown-content a {
            color: #333;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            transition: background 0.3s;
        }

        .dropdown-content a:hover {
            background-color: #f5f5f5;
        }

        @media (max-width: 600px) {
            .header {
                flex-direction: column;
                align-items: flex-start;
                padding: 15px 20px;
            }

            .nav-icons {
                flex-direction: column;
                width: 100%;
                gap: 10px;
                margin-top: 10px;
            }

            .icon-btn,
            .get-started-btn {
                width: 100%;
                text-align: left;
                padding: 12px 20px;
            }

            .logo {
                margin-bottom: 10px;
            }
        }
    </style>
</head>

<body>
    <header class="header">
        <a href="./index.php" class="logo">eParking Mall</a>
        <div class="nav-icons">
            <?php if ($isLoggedIn): ?>
                <?php if ($role == 1): ?>
                    <div class="dropdown">
                        <button class="icon-btn">
                            <i class="uil uil-apps"></i>
                        </button>
                        <div class="dropdown-content">
                            <a href="./booking.php"><i class="uil uil-car"></i> Pemesanan</a>
                            <a href="./car.php"><i class="uil uil-plus-circle"></i> Tambah Mobil</a>
                            <a href="reservation.php?user_id=<?php echo $_SESSION['user_id']; ?>"><i class="uil uil-calendar-alt"></i> Reservasi</a>
                        </div>
                    </div>
                <?php endif; ?>
                <a href="logout.php" class="icon-btn">
                    <i class="uil uil-signout"></i>
                </a>
            <?php else: ?>
                <a href="signin.php" class="get-started-btn">Get Started</a>
            <?php endif; ?>
        </div>
    </header>
</body>

</html>
<?php
    return ob_get_clean();
}
?>
