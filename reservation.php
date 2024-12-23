<?php
include 'header.php';
include 'connection.php';

if (isset($_GET['user_id'])) {
    $user_id = mysqli_real_escape_string($con, $_GET['user_id']);
} else {
    die("User ID tidak ditemukan.");
}

if (isset($_POST['cancel']) && isset($_POST['payment_id'])) {
    $payment_id = mysqli_real_escape_string($con, $_POST['payment_id']);
    
    $cancel_sql = "UPDATE payments 
                   SET payment_status = 'Cancelled' 
                   WHERE payment_id = '$payment_id' 
                   AND payment_status = 'Completed'
                   AND user_id = '$user_id'";
    
    $cancel_result = mysqli_query($con, $cancel_sql);

    if ($cancel_result && mysqli_affected_rows($con) > 0) {
        echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Reservasi Berhasil Dibatalkan',
                text: 'Harap hubungi admin untuk pembatalan pembayaran.',
                confirmButtonText: 'OK'
            }).then((result) => {
                window.location.href = 'reservation.php?user_id=" . $user_id . "';
            });
        </script>";
    } else {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal Membatalkan Reservasi',
                text: 'Status pembayaran mungkin sudah berubah atau tidak ditemukan.',
                confirmButtonText: 'OK'
            }).then((result) => {
                window.location.href = 'reservation.php?user_id=" . $user_id . "';
            });
        </script>";
    }
}

$sql = "SELECT payment_id, amount, payment_status, mall_name, booking_number 
        FROM payments 
        WHERE user_id = '$user_id'
        ORDER BY payment_id DESC";
$result = mysqli_query($con, $sql);

if (!$result) {
    die("Query gagal: " . mysqli_error($con));
}
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
    <title>eParking Mall</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }
        .content {
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }
        .reservation-title {
            color: #00855D;
            text-align: center;
            margin: 30px 0;
            font-size: 24px;
        }
        .reservation-table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            border-radius: 8px;
            overflow: hidden;
        }
        .reservation-table th {
            background-color: #f8f9fa;
            padding: 15px;
            text-align: left;
            color: #333;
            font-weight: 600;
        }
        .reservation-table td {
            padding: 15px;
            border-top: 1px solid #f0f0f0;
            color: #444;
        }
        .cancel-btn {
            background-color: #00855D;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .cancel-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        .success-status {
            color: #00855D;
            font-weight: bold;
        }
        .cancelled-status {
            color: #dc3545;
            font-weight: bold;
        }
        .pending-status {
            color: #ffc107;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="content">
        <h2 class="reservation-title">Reservasi Saya</h2>
        <table class="reservation-table">
            <thead>
                <tr>
                    <th>Alamat Parkir</th>
                    <th>Nomor Pemesanan</th>
                    <th>Jumlah</th>
                    <th>Status Pembayaran</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <?php while ($payment = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($payment['mall_name']); ?></td>
                            <td>#<?php echo htmlspecialchars($payment['booking_number']); ?></td>
                            <td>Rp <?php echo number_format($payment['amount'], 0, ',', '.'); ?></td>
                            <td>
                                <?php
                                $statusClass = '';
                                $status = strtolower($payment['payment_status']);
                                
                                switch($status) {
                                    case 'completed':
                                        $statusClass = 'success-status';
                                        break;
                                    case 'cancelled':
                                    case 'canceled':
                                    case 'cancel':
                                        $statusClass = 'cancelled-status';
                                        break;
                                    default:
                                        $statusClass = 'pending-status';
                                }
                                ?>
                                <span class="<?php echo $statusClass; ?>">
                                    <?php echo ucfirst($payment['payment_status']); ?>
                                </span>
                            </td>
                            <td>
                                <?php if (strtolower($payment['payment_status']) === 'completed'): ?>
                                    <form method="post" class="cancelForm">
                                        <input type="hidden" name="cancel" value="1">
                                        <input type="hidden" name="payment_id" value="<?php echo $payment['payment_id']; ?>">
                                        <button type="button" onclick="confirmCancel(this.form)" class="cancel-btn">Batalkan</button>
                                    </form>
                                <?php else: ?>
                                    <button class="cancel-btn" disabled>Batalkan</button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" style="text-align: center;">Tidak ada reservasi ditemukan.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php include 'footer.php'; ?>

    <script>
    function confirmCancel(form) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: 'Apakah Anda ingin membatalkan reservasi ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#0D904F',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, batalkan!',
            cancelButtonText: 'Tidak, simpan'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    }
    </script>
</body>
</html>

<?php
mysqli_close($con);
?>