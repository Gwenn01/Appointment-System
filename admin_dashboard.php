<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit;
}
$page = $_GET['page'] ?? 'home';

$allowed_pages = [
    "home" => "AdminDashboard/Home.php",
    "appointments" => "AdminDashboard/ManageAppointment.php",
    "approvals" => "AdminDashboard/PendingAproval.php",
    "users" => "AdminDashboard/ManageAccount.php",
    "services" => "AdminDashboard/Service.php",
];

$page_file = $allowed_pages[$page] ?? null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard | Appointment System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="style/dashborad_design.css" rel="stylesheet">

</head>
<body>

    <!-- Sidebar Navigation -->
    <div class="sidebar">
        <h4>Welcome, <?= htmlspecialchars($_SESSION['username']) ?>!</h4>
        <ul>
            <li><a href="admin_dashboard.php?page=home" class="<?= $page === 'home' ? 'active' : '' ?>"><i class="bi bi-house-door me-2"></i> Home</a></li>
            <li><a href="admin_dashboard.php?page=appointments" class="<?= $page === 'appointments' ? 'active' : '' ?>"><i class="bi bi-calendar-check me-2"></i> Appointments</a></li>
            <li><a href="admin_dashboard.php?page=approvals" class="<?= $page === 'approvals' ? 'active' : '' ?>"><i class="bi bi-exclamation-circle me-2"></i> Approvals</a></li>
            <li><a href="admin_dashboard.php?page=users" class="<?= $page === 'users' ? 'active' : '' ?>"><i class="bi bi-people me-2"></i> Accounts</a></li>
            <li><a href="admin_dashboard.php?page=services" class="<?= $page === 'services' ? 'active' : '' ?>"><i class="bi bi-list-task me-2"></i> Services</a></li>
            <li><a href="Authentication/admin_logout.php" class="logout"><i class="bi bi-box-arrow-right me-2"></i> Logout</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <?php
        if ($page_file && file_exists($page_file)) {
            include $page_file;
        } else {
            echo "<div class='card-style text-center text-danger'><h4>404 - Page not found.</h4></div>";
        }
        ?>
    </div>
    <!-- Bootstrap & Icons -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
