<?php
$page = isset($_GET['page']) ? $_GET['page'] : 'home'; // Default to 'home'
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Dashboard | Appointment System</title>
    <link rel="stylesheet" href="style/dashboard.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
</head>
<body>
    <!-- Sidebar Navigation -->
    <div class="sidebar">
        <h2>Customer Panel</h2>
        <ul class="list-unstyled">
            <li><a href="dashboard.php?page=home"><i class="bi bi-house-door"></i> Home</a></li>
            <li><a href="dashboard.php?page=appointments"><i class="bi bi-calendar-check"></i> My Appointments</a></li>
            <li><a href="dashboard.php?page=profile"><i class="bi bi-person"></i> Profile</a></li>
            <li><a href="logout.php" class="logout"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
        </ul>
    </div>
     <!-- Main Content -->
     <div class="main-content">
        <div id="content">
            <?php
            // Load different sections based on GET parameter
            switch ($page) {
                case "home":
                    include "pages/home.php";
                    break;
                case "appointments":
                    include "pages/appointments.php";
                    break;
                case "profile":
                    include "pages/profile.php";
                    break;
                default:
                    echo "<p>Page not found.</p>";
            }
            ?>
        </div>
    </div>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
