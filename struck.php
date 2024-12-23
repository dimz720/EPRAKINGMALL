<?php
include('connection.php');

function getPaymentStatus($status)
{
    $status_class = '';
    $status_text = '';

    switch ($status) {
        case 'Pending':
            $status_class = 'pending';
            $status_text = 'Pending';
            break;
        case 'Completed':
            $status_class = 'completed';
            $status_text = 'Completed';
            break;
        case 'Canceled':
            $status_class = 'canceled';
            $status_text = 'Canceled';
            break;
        default:
            $status_class = 'unknown';
            $status_text = 'Unknown Status';
            break;
    }

    return ['class' => $status_class, 'text' => $status_text];
}

if (isset($_GET['payment_id'])) {
    $payment_id = $_GET['payment_id'];

    if (is_numeric($payment_id)) {
        $sql = "SELECT * FROM payments WHERE payment_id = '$payment_id'";
        $result = mysqli_query($con, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            $payment = mysqli_fetch_assoc($result);

            $booking_number = $payment['booking_number'];

            $date = date('d-m-Y', strtotime($payment['created_at']));
            $time = date('H:i', strtotime($payment['created_at']));

            $start_time = date('d-m-Y, H:i', strtotime($payment['start_time']));
            $end_time = date('d-m-Y, H:i', strtotime($payment['end_time']));
            $parking_duration = $start_time . ' - ' . $end_time;

            $payment_method = $payment['bank_name'];
            $account_number = $payment['payer_account_number'];
            $amount_paid = number_format($payment['amount'], 0, ',', '.');

            if (isset($payment['payment_status'])) {
                $status = getPaymentStatus($payment['payment_status']);
                $status_class = $status['class'];
                $status_text = $status['text'];
            } else {
                $status_class = 'unknown';
                $status_text = 'Status not available';
            }
        } else {
            echo "Payment not found for the given ID.";
            exit();
        }
    } else {
        echo "Invalid payment ID.";
        exit();
    }
} else {
    echo "No payment ID provided.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Receipt - eParking Mall</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
        }

        body {
            background-color: #f5f5f5;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
            padding: 1rem;
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

        .back-button,
        .download-button {
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

        .download-button {
            background: #1a73e8;
        }

        .receipt-container {
            background: white;
            width: 100%;
            border-radius: 16px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 2rem;
        }

        .header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .logo {
            width: 60px;
            height: 60px;
            background: #00855D;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            font-weight: bold;
            margin: 0 auto 1rem;
        }

        .payment-status {
            display: inline-block;
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.9rem;
            margin-top: 1rem;
        }

        .payment-status.pending {
            background: #f4b400;
        }

        .payment-status.completed {
            background: #00855D;
        }

        .payment-status.canceled {
            background: #ea4335;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 1rem 0;
            border-bottom: 1px solid #eee;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            color: #666;
        }

        .info-value {
            color: #333;
            font-weight: 500;
        }

        .amount-box {
            background: #00855D;
            color: white;
            padding: 1.5rem;
            border-radius: 12px;
            text-align: center;
            margin: 2rem 0;
        }

        .amount-label {
            margin-bottom: 0.5rem;
        }

        .amount-value {
            font-size: 2rem;
            font-weight: bold;
        }

        .receipt-footer {
            text-align: center;
            color: #666;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px dashed #ccc;
        }

        .qr-section {
            text-align: center;
            margin: 2rem 0;
        }

        .qr-code {
            width: 120px;
            height: 120px;
            margin: 0 auto 0.5rem;
        }

        .qr-label {
            color: #666;
            font-size: 0.9rem;
        }

        @media print {

            .download-button,
            .back-button {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="button-container">
            <button class="back-button" onclick="window.location.href='index.php';">
                <i class="fas fa-arrow-left"></i> Kembali ke Beranda
            </button>
            <button class="download-button">
                <i class="fas fa-download"></i>
                Unduh PDF
            </button>
        </div>

        <div class="receipt-container">
            <div class="header">
                <div class="logo">eP</div>
                <h1>Struk Pembayaran</h1>
                <p>Konfirmasi Pembayaran eParking Mall</p>
                <div class="payment-status <?= $status_class ?>">
                    <?= $status_text ?>
                </div>
            </div>

            <div class="info-row">
                <div class="info-label">Nomor Pemesanan</div>
                <div class="info-value"><?= $booking_number ?></div>
            </div>

            <div class="info-row">
                <div class="info-label">Tanggal</div>
                <div class="info-value"><?= $date ?></div>
            </div>

            <div class="info-row">
                <div class="info-label">Waktu</div>
                <div class="info-value"><?= $time ?></div>
            </div>

            <div class="info-row">
                <div class="info-label">Metode Pembayaran</div>
                <div class="info-value"><?= $payment_method ?></div>
            </div>

            <div class="info-row">
                <div class="info-label">Nomor Rekening</div>
                <div class="info-value"><?= $account_number ?></div>
            </div>

            <div class="info-row">
                <div class="info-label">Durasi Parkir</div>
                <div class="info-value"><?= $parking_duration ?></div>
            </div>

            <div class="amount-box">
                <div class="amount-label">Total Pembayaran</div>
                <div class="amount-value"><?= $amount_paid ?></div>
            </div>

            <div class="qr-section">
                <canvas id="qrCode" class="qr-code"></canvas>
                <div class="qr-label">Pindai untuk Kunjungi</div>
            </div>

            <div class="receipt-footer">
                <p>Terima kasih atas pembayaran Anda!</p>
                <p>Ini adalah struk resmi dari eParking Mall.</p>
                <p>Simpan struk ini untuk catatan Anda.</p>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrious/4.0.2/qrious.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script>
        window.onload = function() {
            var qr = new QRious({
                element: document.getElementById('qrCode'),
                value: 'http://localhost/eParkingMall1/',
                size: 120
            });
        }

        document.querySelector('.download-button').addEventListener('click', function() {
            const element = document.querySelector('.receipt-container');
            const opt = {
                margin: 1,
                filename: 'parking-receipt.pdf',
                image: {
                    type: 'jpeg',
                    quality: 0.98
                },
                html2canvas: {
                    scale: 2
                },
                jsPDF: {
                    unit: 'mm',
                    format: 'a4',
                    orientation: 'portrait'
                }
            };

            html2pdf().set(opt).from(element).save();
        });
    </script>
</body>

</html>