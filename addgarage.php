<?php
session_start();
require 'connection.php';

if (!isset($_SESSION['owner_id'])) {
    header("Location: ownersignin.php");
    exit();
}
function uploadImage($file)
{
    $targetDir = "uploads/";
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true);
    }
    $targetFile = $targetDir . basename($file['name']);
    if (!move_uploaded_file($file['tmp_name'], $targetFile)) {
        throw new Exception("Failed to upload image.");
    }
    return $targetFile;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $owner_id = $_SESSION['owner_id'];
    $name = $_POST['name'];
    $location = $_POST['location'];
    $region = $_POST['region'];
    $image = uploadImage($_FILES['image']);
    $totalSpots1 = $_POST['totalSpots1'];
    $price1 = $_POST['price1'];
    $totalSpots2 = $_POST['totalSpots2'];
    $price2 = $_POST['price2'];

    $query1 = "INSERT INTO Garages (owner_id, name, location, region, image_url) 
               VALUES ('$owner_id', '$name', '$location', '$region', '$image')";
    
    if (mysqli_query($con, $query1)) {
        $garageId = mysqli_insert_id($con);
        $query2 = "INSERT INTO Spots (garage_id, total_spots1, type1, price1, total_spots2, type2, price2) 
                   VALUES ('$garageId', '$totalSpots1', 'Jam', '$price1', '$totalSpots2', 'Hari', '$price2')";
        if (mysqli_query($con, $query2)) {
            header("Location: signin.php");
            exit();
        } else {
            echo "Error: " . mysqli_error($con);
        }
    } else {
        echo "Error: " . mysqli_error($con);
    }
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
    <title>Tambah Garasi - eParking Mall</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background-color: #f5f5f5;
        }
        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 2rem;
        }

        .settings-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
        }

        .settings-section {
            background-color: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .section-title {
            color: #333;
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #00855D;
            font-size: 1.25rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #555;
            font-weight: 500;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }

        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            border-color: #00855D;
            outline: none;
            box-shadow: 0 0 0 2px rgba(0, 132, 92, 0.1);
        }

        .file-input {
            width: 100%;
            border: 2px dashed #ddd;
            border-radius: 8px;
            cursor: pointer;
            transition: border-color 0.3s;
        }

        .file-input input[type="file"] {
            width: 100%;
            padding: 0.8rem;
            cursor: pointer;
        }

        .file-input:hover {
            border-color: #00855D;
        }

        .save-btn {
            background-color: #00855D;
            color: white;
            padding: 1rem 2rem;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            cursor: pointer;
            width: 100%;
            max-width: 200px;
            margin: 2rem auto 0;
            display: block;
            transition: background-color 0.3s;
        }

    </style>
</head>

<body>
    <div class="container">
        <form action="addgarage.php" method="POST" enctype="multipart/form-data">
            <div class="settings-grid">
                <div class="settings-section">
                    <h2 class="section-title">Pengaturan Garasi</h2>
                    <div class="form-group">
                        <label for="name">Nama Garasi</label>
                        <input type="text" id="name" name="name" placeholder="Masukkan nama garasi Anda" required>
                    </div>
                    <div class="form-group">
                        <label for="location">Lokasi Garasi</label>
                        <input type="text" id="location" name="location" placeholder="Masukkan lokasi garasi Anda" required>
                    </div>
                    <div class="form-group">
                        <label for="region">Wilayah Garasi</label>
                        <input type="text" id="region" name="region" placeholder="Masukkan wilayah garasi (kota)" required>
                    </div>
                    <div class="form-group">
                        <label for="image">Gambar Garasi</label>
                        <div class="file-input">
                            <input type="file" id="image" name="image" accept="image/*" required>
                        </div>
                    </div>
                </div>

                <div class="settings-section">
                    <h2 class="section-title">Pengaturan Tempat Parkir</h2>
                    <div class="form-group">
                        <label for="totalSpots1">Jumlah Tempat Parkir (Per Jam)</label>
                        <input type="number" id="totalSpots1" name="totalSpots1" placeholder="Masukkan jumlah tempat parkir" min="1" required>
                    </div>
                    <div class="form-group">
                        <label for="price1">Harga (Per Jam)</label>
                        <input type="number" id="price1" name="price1" placeholder="Masukkan harga per jam" min="0" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label for="totalSpots2">Jumlah Tempat Parkir (Per Hari)</label>
                        <input type="number" id="totalSpots2" name="totalSpots2" placeholder="Masukkan jumlah tempat parkir" min="1" required>
                    </div>
                    <div class="form-group">
                        <label for="price2">Harga (Per Hari)</label>
                        <input type="number" id="price2" name="price2" placeholder="Masukkan harga per bulan" min="0" step="0.01" required>
                    </div>
                </div>
            </div>

            <button type="submit" class="save-btn">Simpan</button>
        </form>
    </div>
    <?php include 'footer.php'; ?>
</body>

</html>
