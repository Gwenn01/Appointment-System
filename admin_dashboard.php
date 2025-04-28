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

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="style/dashborad_design.css" rel="stylesheet">

</head>
<body>

<!-- Sidebar Navigation -->
<div class="sidebar">
    <h2>Welcome, <?= htmlspecialchars($_SESSION['username']) ?>!</h2>
    <ul>
        <li><a href="admin_dashboard.php?page=home" class="<?= $page === 'home' ? 'active' : '' ?>"><i class="fa-solid fa-house"></i> Home</a></li>
        <li><a href="admin_dashboard.php?page=appointments" class="<?= $page === 'appointments' ? 'active' : '' ?>"><i class="fa-solid fa-calendar-check"></i> Appointments</a></li>
        <li><a href="admin_dashboard.php?page=approvals" class="<?= $page === 'approvals' ? 'active' : '' ?>"><i class="fa-solid fa-circle-exclamation"></i> Approvals</a></li>
        <li><a href="admin_dashboard.php?page=users" class="<?= $page === 'users' ? 'active' : '' ?>"><i class="fa-solid fa-users"></i> Accounts</a></li>
        <li><a href="admin_dashboard.php?page=services" class="<?= $page === 'services' ? 'active' : '' ?>"><i class="fa-solid fa-list-check"></i> Services</a></li>
        <li><a href="Authentication/admin_logout.php" class="logout"><i class="fa-solid fa-right-from-bracket"></i> Logout</a></li>
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

</body>
</html>
