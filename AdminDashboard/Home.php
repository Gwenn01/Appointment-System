<?php
    if (!isset($_SESSION['adminid'])) {
        header("Location: ../admin_login.php");
        exit();
    }
    require(__DIR__ . '/../Database/database.php');

    $totalAppointments = 0;
    $totalUsers = 0;
    $pendingApprovals = 0;

    // Total Appointments
    $result1 = mysqli_query($conn, "SELECT COUNT(*) AS total FROM appointments");
    if ($row = mysqli_fetch_assoc($result1)) {
        $totalAppointments = $row['total'];
    }

    // Total Users
    $result2 = mysqli_query($conn, "SELECT COUNT(*) AS total FROM users");
    if ($row = mysqli_fetch_assoc($result2)) {
        $totalUsers = $row['total'];
    }

    // Pending Approvals
    $result3 = mysqli_query($conn, "SELECT COUNT(*) AS total FROM appointments WHERE status = 'pending'");
    if ($row = mysqli_fetch_assoc($result3)) {
        $pendingApprovals = $row['total'];
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home | Admin Dashboard</title>
    <link rel="stylesheet" href="AdminDashboard/style/admin_dashboard.css"> 
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
</head>
<body>

<div class="dashboard-container">
    <header class="dashboard-header">
        <h1>Welcome, Admin!</h1>
    </header>

    <section class="dashboard-cards">
        <div class="card">
            <h3><i class="bi bi-calendar-check text-primary"></i> Total Appointments</h3>
            <p class="text-primary"><?= $totalAppointments; ?></p>
        </div>

        <div class="card">
            <h3><i class="bi bi-people text-success"></i> Total Users</h3>
            <p class="text-success"><?= $totalUsers; ?></p>
        </div>

        <div class="card">
            <h3><i class="bi bi-hourglass-split text-warning"></i> Pending Approvals</h3>
            <p class="text-warning"><?= $pendingApprovals; ?></p>
        </div>
    </section>
</div>

</body>
</html>
