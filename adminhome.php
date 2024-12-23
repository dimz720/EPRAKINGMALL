<?php
include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $action = $_POST['action'];
    $newStatus = ($action === 'suspend') ? 'suspended' : 'active';
    $query = "UPDATE users SET status = ? WHERE email = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "ss", $newStatus, $email);
    $usersResult = mysqli_stmt_execute($stmt);
    $query = "UPDATE owners SET status = ? WHERE email = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "ss", $newStatus, $email);
    $ownersResult = mysqli_stmt_execute($stmt);
    
    if ($usersResult || $ownersResult) {
        echo json_encode([
            'success' => true,
            'message' => 'Status berhasil diupdate',
            'newStatus' => $newStatus
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Gagal mengupdate status'
        ]);
    }
    
    exit(); 
}

$query = "
    SELECT id, fullname, email, phone, password, status, role FROM users
    UNION
    SELECT id, fullname, email, phone, password, status, role FROM owners
";
$result = mysqli_query($con, $query);
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
    <title>Admin Dashboard - eParking Mall</title>
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: Arial, sans-serif;
    }

    body {
        background-color: #f5f5f5;
        min-height: 100vh;
    }

    .content {
        padding: 30px;
        max-width: 1400px;
        margin: 0 auto;
    }

    .page-title {
        font-size: 32px;
        margin-bottom: 30px;
        color: #333;
    }

    .users-table-container {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .table-header {
        padding: 20px;
        border-bottom: 2px solid #eee;
    }

    .table-header h2 {
        color: #333;
        font-size: 20px;
        margin-bottom: 15px;
    }

    .search-filter-container {
        display: flex;
        gap: 20px;
        align-items: center;
        flex-wrap: wrap;
    }

    .search-box {
        flex: 1;
        min-width: 200px;
        position: relative;
    }

    .search-box input {
        width: 100%;
        padding: 10px 15px;
        padding-left: 40px;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 14px;
    }

    .search-icon {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #666;
    }

    .filter-box {
        min-width: 150px;
    }

    .filter-box select {
        width: 100%;
        padding: 10px 15px;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 14px;
        background-color: white;
        cursor: pointer;
        color: #666;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th {
        color: #555;
        font-weight: 600;
        text-align: left;
        padding: 16px;
        border-bottom: 2px solid #eee;
    }

    td {
        padding: 16px;
        border-bottom: 1px solid #eee;
        color: #333;
    }
    .user-row {
        transition: all 0.2s ease;
    }

    .user-actions {
        display: flex;
        gap: 10px;
        justify-content: flex-start;
    }

    .btn {
        padding: 8px 16px;
        border-radius: 6px;
        border: none;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .btn-remove {
        background-color: #fff;
        color: #dc3545;
        border: 1px solid #dc3545;
    }

    .btn-remove:hover {
        background-color: #dc3545;
        color: white;
    }

    .btn-suspend {
        background-color: #fff;
        color: #ffc107;
        border: 1px solid #ffc107;
    }

    .btn-suspend:hover {
        background-color: #ffc107;
        color: white;
    }
    .role-badge {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 500;
    }
 
    .role-owner {
        background-color: #e8f5e9;
        color: #00855D;
    }

    .role-user {
        background-color: #f5f5f5;
        color: #616161;
    }

    .status-badge {
        padding: 4px 12px;
        border-radius: 6px;
        font-size: 14px;
    }

    .active-status {
        background-color: #00855D;
        color: white;
    }

    .suspended-status {
        background-color: #dc3545;
        color: white;
    }

    .hidden {
        display: none;
    }
</style>

</head>
<body>
<div class="content">
    <h2 class="page-title">Admin Dashboard</h2>
    <div class="users-table-container">
        <div class="table-header">
            <h2>Users Management</h2>
            <div class="search-filter-container">
                <div class="search-box">
                    <i class="uil uil-search search-icon"></i>
                    <input type="text" placeholder="Search by name..." id="searchInput" oninput="filterTable()">
                </div>
                <div class="filter-box">
                    <select id="roleFilter" onchange="filterTable()">
                        <option value="all">All Roles</option>
                        <option value="owner">Owner</option>
                        <option value="user">User</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Phone Number</th>
                        <th>Password</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="userTableBody">
                    <?php
                    if ($result) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $fullName = $row['fullname'];
                            $email = $row['email'];
                            $phone = $row['phone'];
                            $password = $row['password'];
                            $status = $row['status'];
                            $role = $row['role'];

                            $roleText = ($role == 2) ? 'Owner' : 'User';
                            $roleClass = ($role == 2) ? 'role-owner' : 'role-user';

                            $statusClass = ($status == 'suspended') ? 'suspended-status' : 'active-status';
                            $statusText = ucfirst($status);

                            $actionButton = ($status == 'suspended') 
                                ? "<button class='btn btn-remove' onclick='removeUser(this)'>Remove</button>"
                                : "<button class='btn btn-suspend' onclick='suspendUser(this)'>Suspend</button>";

                            echo "
                            <tr class='user-row'>
                                <td>$fullName</td>
                                <td>$email</td>
                                <td>$phone</td>
                                <td>••••••••</td>
                                <td><span class='role-badge $roleClass'>$roleText</span></td>
                                <td><span class='status-badge $statusClass'>$statusText</span></td>
                                <td>
                                    <div class='user-actions'>
                                        $actionButton
                                    </div>
                                </td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7'>No data found</td></tr>";
                    }
                    mysqli_close($con);
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php include 'footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function filterTable() {
    const searchInput = document.getElementById('searchInput').value.toLowerCase();
    const roleFilter = document.getElementById('roleFilter').value.toLowerCase();
    const rows = document.querySelectorAll('.user-row');

    rows.forEach(row => {
        const name = row.querySelector('td:nth-child(1)').textContent.toLowerCase(); 
        const email = row.querySelector('td:nth-child(2)').textContent.toLowerCase(); 
        const phone = row.querySelector('td:nth-child(3)').textContent.toLowerCase(); 
        const role = row.querySelector('.role-badge').textContent.toLowerCase();
        const searchMatch = name.includes(searchInput) || email.includes(searchInput) || phone.includes(searchInput);
        const roleMatch = roleFilter === 'all' || role === roleFilter;
        if (searchMatch && roleMatch) {
            row.classList.remove('hidden');
        } else {
            row.classList.add('hidden');
        }
    });
}

    function suspendUser(button) {
        const row = button.closest('tr');
        const email = row.querySelector('td:nth-child(2)').textContent;
        const statusBadge = row.querySelector('.status-badge');
        
        Swal.fire({
            title: 'Konfirmasi',
            text: 'Apakah Anda yakin ingin menangguhkan pengguna ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya',
            cancelButtonText: 'Tidak'
        }).then((result) => {
            if (result.isConfirmed) {
                updateUserStatus(email, 'suspend', (response) => {
                    if (response.success) {
                        statusBadge.textContent = 'Suspended';
                        statusBadge.classList.remove('active-status');
                        statusBadge.classList.add('suspended-status');
                        
                        button.textContent = 'Remove';
                        button.classList.remove('btn-suspend');
                        button.classList.add('btn-remove');
                        button.onclick = function() { removeUser(this); };
                    } else {
                        Swal.fire('Error', 'Gagal mengupdate status user', 'error');
                    }
                });
            }
        });
    }

    function removeUser(button) {
        const row = button.closest('tr');
        const email = row.querySelector('td:nth-child(2)').textContent;
        const statusBadge = row.querySelector('.status-badge');
        
        Swal.fire({
            title: 'Konfirmasi',
            text: 'Apakah Anda yakin ingin mengaktifkan kembali pengguna ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya',
            cancelButtonText: 'Tidak'
        }).then((result) => {
            if (result.isConfirmed) {
                updateUserStatus(email, 'remove', (response) => {
                    if (response.success) {
                        statusBadge.textContent = 'Active';
                        statusBadge.classList.remove('suspended-status');
                        statusBadge.classList.add('active-status');
                        
                        button.textContent = 'Suspend';
                        button.classList.remove('btn-remove');
                        button.classList.add('btn-suspend');
                        button.onclick = function() { suspendUser(this); };
                    } else {
                        Swal.fire('Error', 'Gagal mengupdate status user', 'error');
                    }
                });
            }
        });
    }

    function updateUserStatus(email, action, callback) {
        const formData = new FormData();
        formData.append('email', email);
        formData.append('action', action);
        
        fetch(window.location.href, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => callback(data))
        .catch(error => {
            console.error('Error:', error);
            Swal.fire('Error', 'Terjadi kesalahan saat mengupdate status', 'error');
        });
    }
</script>

</body>
</html>