<?php
include 'header.php';
include 'connection.php';

$spotData = []; 

if (isset($_GET['garage_id'])) {
    $garageId = $_GET['garage_id'];

    $stmt = $con->prepare("
        SELECT s.total_spots1, s.price1, s.total_spots2, s.price2
        FROM Spots s
        WHERE s.garage_id = ?");
    $stmt->bind_param("i", $garageId);
    $stmt->execute();
    $stmt->bind_result($total_spots1, $price1, $total_spots2, $price2);
    $stmt->fetch();
    $stmt->close();

    if ($total_spots1 !== null && $total_spots2 !== null) {
        $spotData = [
            'total_spots1' => $total_spots1,
            'price1' => $price1,
            'total_spots2' => $total_spots2,
            'price2' => $price2
        ];
    } else {
        echo "No spot data found!";
        exit();
    }
} else {
    echo "Garage ID is required.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_POST['field'])) {
        echo "Field parameter is required.";
        exit();
    }

    $field = $_POST['field'];

    if ($field == 'price1' || $field == 'price2') {
        if (!isset($_POST['value']) || !is_numeric($_POST['value'])) {
            echo "Invalid price value.";
            exit();
        }

        $value = floatval($_POST['value']);

        $stmt = $con->prepare("UPDATE Spots SET $field = ? WHERE garage_id = ?");
        $stmt->bind_param("di", $value, $garageId);
        $stmt->execute();
        $stmt->close();

        $stmt = $con->prepare("SELECT price1, price2 FROM Spots WHERE garage_id = ?");
        $stmt->bind_param("i", $garageId);
        $stmt->execute();
        $stmt->bind_result($price1, $price2);
        $stmt->fetch();
        $stmt->close();

        $spotData['price1'] = $price1;
        $spotData['price2'] = $price2;
    } else {
        if (!isset($_POST['action'])) {
            echo "Action parameter is required.";
            exit();
        }

        if ($field == 'total_spots1') {
            if ($_POST['action'] == 'add') {
                $spotData['total_spots1']++;
            } elseif ($_POST['action'] == 'remove' && $spotData['total_spots1'] > 0) {
                $spotData['total_spots1']--;
            }
        } elseif ($field == 'total_spots2') {
            if ($_POST['action'] == 'add') {
                $spotData['total_spots2']++;
            } elseif ($_POST['action'] == 'remove' && $spotData['total_spots2'] > 0) {
                $spotData['total_spots2']--;
            }
        }

        $stmt = $con->prepare("UPDATE Spots SET $field = ? WHERE garage_id = ?");
        $stmt->bind_param("ii", $spotData[$field], $garageId);
        $stmt->execute();
        $stmt->close();
    }
}

$con->close();
?>
<?php
include_once 'header.php';
echo getHeaderHtml();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dasbor Pemilik - eParking Mall</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
        }

        body {
            background-color: #f5f5f5;
        }
        .content {
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .page-title {
            font-size: 32px;
            margin-bottom: 20px;
            color: #333;
        }

        .analysis-section {
            background-color: white;
            padding: 2rem;
            border-radius: 12px;
            margin-bottom: 2rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .analysis-section h3 {
            color: #333;
            margin-bottom: 2rem;
            font-size: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .analysis-section h3 i {
            color: #00855D;
        }

        .spots-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 2rem;
        }

        .spot-box {
            text-align: center;
            flex: 1;
            padding: 20px;
        }

        .spot-box h4 {
            color: #666;
            margin-bottom: 1rem;
            font-size: 0.9rem;
        }

        .spot-number {
            font-size: 3rem;
            font-weight: bold;
            margin-bottom: 1rem;
            transition: all 0.3s;
        }

        .spot-number.available {
            color: #00855D;
        }

        .spot-controls {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            max-width: 300px;
            margin: 0 auto;
        }

        .control-btn {
            padding: 0.8rem 1.5rem;
            border: 2px solid #00855D;
            border-radius: 8px;
            background: white;
            color: #00855D;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 500;
            transition: all 0.3s;
            justify-content: center;
            width: 100%;
        }

        .control-btn:hover {
            background: #00855D;
            color: white;
        }

        .control-btn.remove {
            border-color: #ff4444;
            color: #ff4444;
        }

        .control-btn.remove:hover {
            background: #ff4444;
            color: white;
        }

        .price-section {
            background-color: white;
            padding: 30px;
            border-radius: 12px;
            margin-top: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .price-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 30px;
        }

        .price-card {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 12px;
            border: 1px solid #e9ecef;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .price-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .price-card h4 {
            color: #333;
            margin-bottom: 20px;
            font-size: 18px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .price-card h4 i {
            color: #00855D;
            font-size: 20px;
        }

        .current-price {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
            padding: 15px;
            background: white;
            border-radius: 8px;
            border: 1px solid #e9ecef;
        }

        .current-price label {
            color: #666;
            white-space: nowrap;
            font-weight: 500;
        }

        .current-price span {
            color: #00855D;
            font-weight: bold;
            font-size: 18px;
        }

        .price-input-group {
            display: flex;
            gap: 10px;
            margin-bottom: 15px;
        }

        .price-input {
            flex: 1;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s;
        }

        .price-input:focus {
            outline: none;
            border-color: #00855D;
            box-shadow: 0 0 0 2px rgba(0,132,92,0.1);
        }

        .change-btn {
            background-color: #00855D;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
            font-weight: 500;
        }

        .change-btn:active {
            transform: translateY(0);
        }

        @media (max-width: 768px) {
            .price-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="content">
        <h2 class="page-title">Owner Dashboard</h2>

        <div class="analysis-section">
            <h3><i class="fas fa-clock"></i> Analisis Spot Per Jam</h3>
            <div class="spots-container">
                <div class="spot-box">
                    <h4>Jumlah Spot Tersedia</h4>
                    <div class="spot-number available"><?= $spotData['total_spots1'] ?></div>
                </div>
            </div>
            <div class="spot-controls">
                <form method="POST">
                    <input type="hidden" name="field" value="total_spots1">
                    <input type="hidden" name="action" value="add">
                    <button type="submit" class="control-btn">
                        <i class="fas fa-plus"></i>
                        Tambah Spot
                    </button>
                </form>
                <form method="POST">
                    <input type="hidden" name="field" value="total_spots1">
                    <input type="hidden" name="action" value="remove">
                    <button type="submit" class="control-btn remove">
                        <i class="fas fa-minus"></i>
                        Hapus Spot
                    </button>
                </form>
            </div>
        </div>

        <div class="analysis-section">
            <h3><i class="fas fa-calendar-alt"></i> Analisis Spot Harian</h3>
            <div class="spots-container">
                <div class="spot-box">
                    <h4>Jumlah Spot Tersedia</h4>
                    <div class="spot-number available"><?= $spotData['total_spots2'] ?></div>
                </div>
            </div>
            <div class="spot-controls">
                <form method="POST">
                    <input type="hidden" name="field" value="total_spots2">
                    <input type="hidden" name="action" value="add">
                    <button type="submit" class="control-btn">
                        <i class="fas fa-plus"></i>
                        Tambah Spot
                    </button>
                </form>
                <form method="POST">
                    <input type="hidden" name="field" value="total_spots2">
                    <input type="hidden" name="action" value="remove">
                    <button type="submit" class="control-btn remove">
                        <i class="fas fa-minus"></i>
                        Hapus Spot
                    </button>
                </form>
            </div>
        </div>

        <div class="price-section">
            <div class="price-grid">
                <div class="price-card">
                    <h4><i class="fas fa-clock"></i> Tarif Per Jam</h4>
                    <div class="current-price">
                        <label>Harga Saat Ini:</label>
                        <span>Rp <?= number_format($spotData['price1'], 0, ',', '.') ?></span>
                    </div>
                    <form method="POST" class="price-input-group">
                        <input type="hidden" name="field" value="price1">
                        <input type="number" name="value" class="price-input" placeholder="Masukkan harga baru per jam" required>
                        <button type="submit" class="change-btn">Perbarui</button>
                    </form>
                </div>

                <div class="price-card">
                    <h4><i class="fas fa-calendar-alt"></i> Tarif Per Hari</h4>
                    <div class="current-price">
                        <label>Harga Saat Ini:</label>
                        <span>Rp <?= number_format($spotData['price2'], 0, ',', '.') ?></span>
                    </div>
                    <form method="POST" class="price-input-group">
                        <input type="hidden" name="field" value="price2">
                        <input type="number" name="value" class="price-input" placeholder="Masukkan harga baru per hari" required>
                        <button type="submit" class="change-btn">Perbarui</button>
                    </form>
                </div>
            </div>
        </div>
        <?php include 'footer.php'; ?>
    <script>
        document.querySelectorAll('.control-btn').forEach(button => {
            button.addEventListener('mouseover', function() {
                this.style.transform = 'translateY(-2px)';
            });
            
            button.addEventListener('mouseout', function() {
                this.style.transform = 'translateY(0)';
            });
        });

        document.querySelectorAll('.change-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                let ripple = document.createElement('span');
                ripple.classList.add('ripple');
                this.appendChild(ripple);
                
                let rect = this.getBoundingClientRect();
                let x = e.clientX - rect.left;
                let y = e.clientY - rect.top;
                
                ripple.style.left = x + 'px';
                ripple.style.top = y + 'px';
                
                setTimeout(() => {
                    ripple.remove();
                }, 1000);
            });
        });
    </script>
</body>
</html>