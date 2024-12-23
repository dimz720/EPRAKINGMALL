<?php
include 'connection.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$user_id = $_SESSION['user_id'];
function fetchVehicle($con, $user_id)
{
    $query = "SELECT * FROM vehicles WHERE user_id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}
if (isset($_POST['delete'])) {
    $id = $_POST['id'];
    $query = "DELETE FROM vehicles WHERE id = ? AND user_id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param('ii', $id, $user_id);
    if ($stmt->execute()) {
        header("Location: car.php");
        exit();
    } else {
        echo "Gagal menghapus data kendaraan.";
    }
}
if (isset($_POST['save'])) {
    $car_brand = $_POST['car_brand'];
    $car_model = $_POST['car_model'];
    $license_number = $_POST['license_number'];
    $id = $_POST['id'];

    $vehicle = fetchVehicle($con, $user_id);

    if ($vehicle && !$id) {
        echo "<script>alert('Anda hanya dapat mengisi satu kendaraan. Silakan update kendaraan Anda.');</script>";
    } else {
        if ($id) {
            $query = "UPDATE vehicles SET car_brand = ?, car_model = ?, license_number = ? WHERE id = ? AND user_id = ?";
            $stmt = $con->prepare($query);
            $stmt->bind_param('sssii', $car_brand, $car_model, $license_number, $id, $user_id);
        } else {
            $query = "INSERT INTO vehicles (car_brand, car_model, license_number, user_id) VALUES (?, ?, ?, ?)";
            $stmt = $con->prepare($query);
            $stmt->bind_param('sssi', $car_brand, $car_model, $license_number, $user_id);
        }

        if ($stmt->execute()) {
            header("Location: car.php");
            exit();
        } else {
            echo "Gagal menyimpan data kendaraan.";
        }
    }
}

$vehicle = fetchVehicle($con, $user_id);
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
    <title>Manajemen Kendaraan - eParking Mall</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }
        body {
            background-color: #f0f2f5;
            min-height: 100vh;
        }
        .container {
            max-width: 1000px;
            margin: 2rem auto;
            padding: 1rem;
        }
        .card {
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
            padding: 2rem;
            margin-bottom: 2rem;
            transition: transform 0.2s ease;
        }
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.12);
        }
        .card-title {
            color: #1a3b5d;
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 3px solid #00855D;
            position: relative;
        }
        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }
        .form-group {
            margin-bottom: 1rem;
        }
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #34495e;
            font-weight: 500;
            font-size: 0.95rem;
        }
        .form-group input {
            width: 100%;
            padding: 0.8rem;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        .form-group input:focus {
            outline: none;
            border-color: #00855D;
            box-shadow: 0 0 0 3px rgba(0, 132, 92, 0.1);
        }
        .btn {
            background-color: #00855D;
            color: white;
            padding: 0.8rem 2rem;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 500;
        }
        .table-responsive {
            overflow-x: auto;
            border-radius: 10px;
            box-shadow: 0 0 0 1px rgba(0, 0, 0, 0.05);
        }
        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            background: white;
            border-radius: 10px;
        }
        th,
        td {
            padding: 1rem;
            text-align: left;
        }
        th {
            background-color: #00855D;
            color: white;
            font-weight: 500;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }
        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }
        .edit-btn {
            background-color: #ffc107;
            color: white;
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }
        .delete-btn {
            background-color: #dc3545;
            color: white;
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }
        @media (max-width: 768px) {
            .container {
                padding: 1rem;
            }
            .card {
                padding: 1rem;
            }
            th,
            td {
                padding: 0.75rem;
                font-size: 0.9rem;
            }
            .action-buttons {
                flex-direction: column;
                gap: 0.25rem;
            }
            .edit-btn,
            .delete-btn {
                width: 100%;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <h2 class="card-title">Kendaraan Anda</h2>
            <?php if ($vehicle): ?>
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Merk Mobil</th>
                                <th>Model Mobil</th>
                                <th>Nomor Plat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><?= htmlspecialchars($vehicle['car_brand']); ?></td>
                                <td><?= htmlspecialchars($vehicle['car_model']); ?></td>
                                <td><?= htmlspecialchars($vehicle['license_number']); ?></td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="edit-btn" onclick="editVehicle(<?= $vehicle['id']; ?>, '<?= htmlspecialchars($vehicle['car_brand']); ?>', '<?= htmlspecialchars($vehicle['car_model']); ?>', '<?= htmlspecialchars($vehicle['license_number']); ?>')">Edit</button>
                                        <form method="POST" style="display:inline;">
                                            <input type="hidden" name="id" value="<?= $vehicle['id']; ?>">
                                            <button type="submit" name="delete" class="delete-btn">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p>Anda belum memiliki kendaraan. Silakan tambahkan kendaraan Anda.</p>
            <?php endif; ?>
        </div>
        <div class="card">
            <h2 class="card-title"><?= $vehicle ? 'Update' : 'Tambah'; ?> Kendaraan</h2>
            <form method="POST">
                <input type="hidden" name="id" id="vehicle-id" value="<?= $vehicle['id'] ?? ''; ?>">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="car-brand">Merk Mobil</label>
                        <input type="text" id="car-brand" name="car_brand" placeholder="Masukkan merk mobil" value="<?= htmlspecialchars($vehicle['car_brand'] ?? ''); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="car-model">Model Mobil</label>
                        <input type="text" id="car-model" name="car_model" placeholder="Masukkan model mobil" value="<?= htmlspecialchars($vehicle['car_model'] ?? ''); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="license-number">Nomor Plat</label>
                        <input type="text" id="license-number" name="license_number" placeholder="Masukkan nomor plat" value="<?= htmlspecialchars($vehicle['license_number'] ?? ''); ?>" required>
                    </div>
                </div>
                <button type="submit" name="save" class="btn"><?= $vehicle ? 'Update' : 'Simpan'; ?></button>
            </form>
        </div>
    </div>
    <?php include 'footer.php'; ?>
    <script>
        function editVehicle(id, brand, model, license) {
            document.getElementById('vehicle-id').value = id;
            document.getElementById('car-brand').value = brand;
            document.getElementById('car-model').value = model;
            document.getElementById('license-number').value = license;
            document.getElementById('car-brand').focus();
        }
    </script>
</body>
</html>