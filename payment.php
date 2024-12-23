<?php
session_start();
include('connection.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$booking_number = strtoupper(uniqid("BOOK"));

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $payer_name = $_POST['payer_name'];
    $payer_account_number = $_POST['payer_account_number'];
    $bank_name = $_POST['bank_name'];
    $mall_name = $_POST['mall_name'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $amount = 0;
    $payment_reference = $_FILES['payment_reference']['name'];
    $payment_status = 'pending';

    if (!preg_match('/^\d{6}$/', $payer_account_number)) {
        die("Error: Account number must be exactly 6 digits.");
    }

    $upload_dir = 'uploads/';
    $upload_file = $upload_dir . basename($_FILES['payment_reference']['name']);
    move_uploaded_file($_FILES['payment_reference']['tmp_name'], $upload_file);

    $mall_query = "SELECT id FROM malls WHERE mall_name = ? LIMIT 1";
    $stmt = mysqli_prepare($con, $mall_query);
    mysqli_stmt_bind_param($stmt, "s", $mall_name);
    mysqli_stmt_execute($stmt);
    $mall_result = mysqli_stmt_get_result($stmt);
    $mall_row = mysqli_fetch_assoc($mall_result);
    $mall_id = $mall_row['id'];

    $start_timestamp = strtotime($start_time);
    $end_timestamp = strtotime($end_time);
    $duration = ($end_timestamp - $start_timestamp) / 3600;

    $rate_query = "SELECT rate_type, rate FROM parking_rates WHERE mall_id = ? LIMIT 1";
    $stmt = mysqli_prepare($con, $rate_query);
    mysqli_stmt_bind_param($stmt, "i", $mall_id);
    mysqli_stmt_execute($stmt);
    $rate_result = mysqli_stmt_get_result($stmt);
    $rate_row = mysqli_fetch_assoc($rate_result);

    if ($rate_row) {
        if ($rate_row['rate_type'] == 'hourly') {
            $amount = $rate_row['rate'] * $duration;
        } elseif ($rate_row['rate_type'] == 'monthly') {
            $amount = $rate_row['rate'];
        }
    }

    $sql = "INSERT INTO payments (booking_number, user_id, payer_name, payer_account_number, bank_name, mall_name, amount, start_time, end_time, payment_reference, payment_status, created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";

    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param(
        $stmt,
        "sissssdssss",
        $booking_number,
        $user_id,
        $payer_name,
        $payer_account_number,
        $bank_name,
        $mall_name,
        $amount,
        $start_time,
        $end_time,
        $payment_reference,
        $payment_status
    );

    if (mysqli_stmt_execute($stmt)) {
        $payment_id = mysqli_insert_id($con);
        header("Location: struck.php?payment_id=" . $payment_id);
        exit();
    } else {
        echo "Error: " . mysqli_error($con);
    }
}

$user_query = "SELECT fullname, email, phone FROM users WHERE id = ?";
$stmt = mysqli_prepare($con, $user_query);
if ($stmt) {
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $user_result = mysqli_stmt_get_result($stmt);
    $user_data = mysqli_fetch_assoc($user_result);
    mysqli_stmt_close($stmt);
} else {
    $user_data = null;
    error_log("Failed to prepare user query: " . mysqli_error($con));
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment - eParking Mall</title>
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
            padding: 1rem;
        }

        .payment-container {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #333;
            margin-bottom: 1.5rem;
            font-size: 1.8rem;
        }

        .payment-amount {
            background: #e8f5e9;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            text-align: center;
        }

        .amount-label {
            color: #333;
            font-size: 0.9rem;
        }

        .amount-value {
            color: #00855D;
            font-size: 1.8rem;
            font-weight: bold;
            margin-top: 0.5rem;
        }

        .section-title {
            font-size: 1.2rem;
            color: #333;
            margin: 1.5rem 0 1rem 0;
        }

        select {
            width: 100%;
            padding: 1rem;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            margin-bottom: 1rem;
            background: white;
            cursor: pointer;
        }

        .bank-details {
            background-color: #e8f5e9;
            padding: 1.5rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
        }

        .bank-info {
            margin-bottom: 1rem;
        }

        .bank-info:last-child {
            margin-bottom: 0;
        }

        .bank-info-label {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 0.3rem;
        }

        .bank-info-value {
            color: #333;
            font-size: 1.1rem;
            font-weight: bold;
        }

        .input-group,
        .select-group {
            margin-bottom: 1.5rem;
        }

        .input-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #333;
        }

        .input-group input:not([type="file"]) {
            width: 100%;
            padding: 1rem;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
        }

        .time-input {
            display: flex;
            gap: 1rem;
            align-items: center;
            flex-wrap: wrap;
        }

        .time-input-group {
            flex: 1;
            min-width: 200px;
        }

        .datetime-input {
            width: 100%;
            padding: 1rem;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
        }

        .file-input-container {
            border: 2px dashed #ccc;
            border-radius: 8px;
            padding: 15px;
            transition: all 0.3s ease;
        }

        .file-input-content {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .choose-file-btn {
            background: #fff;
            border: 1px solid #ccc;
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 14px;
            cursor: pointer;
        }

        .file-name {
            color: #666;
            font-size: 14px;
        }

        .submit-btn {
            width: 100%;
            padding: 1rem;
            background: #00855D;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            cursor: pointer;
            transition: background 0.3s ease;
            margin-bottom: 1rem;
        }

        .note {
            font-size: 0.85rem;
            color: #666;
            text-align: center;
        }

        .back-home-btn {
            display: inline-block;
            padding: 0.8rem 1.5rem;
            background-color: #00855D;
            color: white;
            font-size: 1rem;
            border-radius: 8px;
            text-decoration: none;
            margin-bottom: 1.5rem;
            text-align: center;
            width: 100%;
            transition: background-color 0.3s;
        }

        .wrapper {
            width: 100%;
            max-width: 600px;
            display: flex;
            flex-direction: column;
        }

        .button-container {
            display: flex;
            justify-content: flex-start;
            gap: 1rem;
            margin-bottom: 1rem;
            width: 100%;
        }

        .back-button {
            background: #00855D;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 1rem;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
    </style>
</head>

<body>
<div class="wrapper">
        <div class="button-container">
            <button class="back-button" onclick="window.location.href='index.php';">
                <i class="fas fa-arrow-left"></i> Kembali ke Beranda
            </button>
        </div>
    <div class="payment-container">
        <h1>Pembayaran</h1>
        <form action="payment.php" method="POST" enctype="multipart/form-data">
            <div class="payment-amount">
                <div class="amount-label">Jumlah Total</div>
                <div class="amount-value">Rp 0</div>
            </div>

            <div class="section-title">Pilih Metode Pembayaran</div>
            <select name="bank_name">
                <option value="" disabled selected>Pilih bank Anda</option>
                <option value="mandiri">Bank Mandiri</option>
                <option value="bri">Bank BRI</option>
                <option value="bca">Bank BCA</option>
                <option value="bni">Bank BNI</option>
            </select>

            <div class="bank-details">
                <div class="bank-info">
                    <div class="bank-info-label">Nama Rekening</div>
                    <div class="bank-info-value">eParking Mall</div>
                </div>
                <div class="bank-info">
                    <div class="bank-info-label">Nomor Rekening</div>
                    <div class="bank-info-value">1234 5678 9012 3456</div>
                </div>
            </div>

            <div class="section-title">Kirim Pembayaran</div>

            <div class="input-group">
                <label>Nomor Pemesanan</label>
                <input type="text" name="booking_number" value="<?php echo $booking_number; ?>" readonly>
            </div>

            <div class="input-group">
                <label>Nama Pembayar</label>
                <input type="text" name="payer_name" placeholder="Enter your name" required>
            </div>

            <div class="input-group">
                <label>Nomor Rekening Pembayar</label>
                <input type="text" name="payer_account_number" placeholder="Enter your account number" required pattern="\d{6}" title="Account number must be exactly 6 digits">
            </div>

            <div class="input-group">
                <label>Pilih Mall</label>
                <select name="mall_name" required>
                    <option value="" disabled selected>Pilih mall Anda</option>
                    <option value="Tunjungan Plaza">Tunjungan Plaza</option>
                    <option value="Grand City">Grand City</option>
                    <option value="Galaxy Mall">Galaxy Mall</option>
                    <option value="Delta Plaza">Delta Plaza</option>
                    <option value="Pakuwon Mall">Pakuwon Mall</option>
                    <option value="BG Junction">BG Junction</option>
                    <option value="Ciputra World">Ciputra World</option>
                    <option value="Plaza Marina">Plaza Marina</option>
                    <option value="Royal Plaza">Royal Plaza</option>
                    <option value="East Coast Center">East Coast Center</option>
                </select>
            </div>

            <div class="input-group">
                <label>Durasi Parkir</label>
                <div class="time-input">
                    <div class="time-input-group">
                        <input type="datetime-local" name="start_time" class="datetime-input" id="startTime" required>
                    </div>
                    <div class="time-input-group">
                        <input type="datetime-local" name="end_time" class="datetime-input" id="endTime" required>
                    </div>
                </div>
            </div>

            <div class="input-group">
                <label>Bukti Pembayaran</label>
                <div class="file-input-container">
                    <div class="file-input-content">
                        <button type="button" class="choose-file-btn" onclick="document.getElementById('fileInput').click()">Pilih File</button>
                        <span class="file-name">Belum ada file yang dipilih</span>
                        <input type="file" id="fileInput" name="payment_reference" accept="image/*" style="display: none;">
                    </div>
                </div>
            </div>

            <button type="submit" class="submit-btn">Kirim Pembayaran</button>

            <div class="note">
            Pastikan semua informasi yang Anda masukkan sudah benar sebelum mengirim pembayaran.
            </div>
        </form>
    </div>

    <script>
        document.getElementById('fileInput').addEventListener('change', function(e) {
            const fileName = e.target.files[0] ? e.target.files[0].name : 'No file chosen';
            e.target.closest('.file-input-container').querySelector('.file-name').textContent = fileName;
        });

        const now = new Date();
        now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
        document.getElementById('startTime').min = now.toISOString().slice(0, 16);

        document.getElementById('startTime').addEventListener('change', function(e) {
            document.getElementById('endTime').min = e.target.value;
        });

        document.querySelector('form').addEventListener('submit', function(e) {
            const accountNumber = document.querySelector('input[name="payer_account_number"]').value;
            if (!/^\d{6}$/.test(accountNumber)) {
                e.preventDefault();
                alert('Account number must be exactly 6 digits.');
            }
        });

        document.querySelector('select[name="mall_name"]').addEventListener('change', function() {
            updateTotalAmount();
        });

        document.querySelector('input[name="start_time"]').addEventListener('change', function() {
            updateTotalAmount();
        });

        document.querySelector('input[name="end_time"]').addEventListener('change', function() {
            updateTotalAmount();
        });

        function updateTotalAmount() {
            var mallName = document.querySelector('select[name="mall_name"]').value;
            var startTime = document.querySelector('input[name="start_time"]').value;
            var endTime = document.querySelector('input[name="end_time"]').value;

            if (mallName && startTime && endTime) {
                var startTimestamp = new Date(startTime).getTime() / 1000;
                var endTimestamp = new Date(endTime).getTime() / 1000;
                var duration = (endTimestamp - startTimestamp) / 3600;

                var xhr = new XMLHttpRequest();
                xhr.open('GET', 'getrate.php?mall_name=' + mallName, true);
                xhr.onload = function() {
                    if (xhr.status == 200) {
                        var response = JSON.parse(xhr.responseText);
                        var rateType = response.rate_type;
                        var rate = response.rate;

                        var totalAmount = 0;
                        if (rateType == 'hourly') {
                            totalAmount = rate * duration;
                        } else if (rateType == 'monthly') {
                            totalAmount = rate;
                        }

                        document.querySelector('.amount-value').textContent = 'Rp ' + totalAmount.toFixed(2);
                    }
                };
                xhr.send();
            }
        }
    </script>
</body>

</html>