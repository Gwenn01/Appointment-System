<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit;
}

$page = $_GET['page'] ?? 'home';

$allowed_pages = [
    "home" => "Dashboard/Home.php",
    "appointments" => "Dashboard/MyAppointment.php",
    "profile" => "Dashboard/Profile.php",
    "about" => "Dashboard/About.php",
    "terms" => "Dashboard/Terms.php"
];

$page_file = $allowed_pages[$page] ?? null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customer Dashboard | Appointment System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="style/dashborad_design.css" rel="stylesheet">

</head>
<body>
<!-- Sidebar Navigation -->
<div class="sidebar">
    <h2>Hello, <?= htmlspecialchars($_SESSION['username']) ?>!</h2>
    <ul>
        <li><a href="dashboard.php?page=home" class="<?= $page === 'home' ? 'active' : '' ?>"><i class="bi bi-house-door"></i> Home</a></li>
        <li><a href="dashboard.php?page=appointments" class="<?= $page === 'appointments' ? 'active' : '' ?>"><i class="bi bi-calendar-check"></i> My Appointments</a></li>
        <li><a href="dashboard.php?page=profile" class="<?= $page === 'profile' ? 'active' : '' ?>"><i class="bi bi-person"></i> Profile</a></li>
        <li><a href="dashboard.php?page=about" class="<?= $page === 'about' ? 'active' : '' ?>"><i class="bi bi-info-circle"></i> About Us</a></li>
        <li><a href="dashboard.php?page=terms" class="<?= $page === 'terms' ? 'active' : '' ?>"><i class="bi bi-file-earmark-text"></i> Terms</a></li>
        <li><a href="Authentication/logout.php" class="logout"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
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
