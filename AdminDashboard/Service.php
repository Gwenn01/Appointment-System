<?php
if (!isset($_SESSION['adminid'])) {
    header("Location: ../admin_login.php");
    exit();
}

require(__DIR__ . '/../Database/database.php');

// Handle Add Service
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_service'])) {
    $name = trim($_POST['service_name']);
    $description = trim($_POST['description']);

    if (!empty($name) && !empty($description)) {
        $stmt = $conn->prepare("INSERT INTO services (service_name, description) VALUES (?, ?)");
        $stmt->bind_param("ss", $name, $description);
        $stmt->execute();
        $stmt->close();
        header("Location: admin_dashboard.php?page=services");
        exit();
    }
}

// Handle Delete Service
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_service'])) {
    $serviceId = intval($_POST['service_id']);
    $stmt = $conn->prepare("DELETE FROM services WHERE id = ?");
    $stmt->bind_param("i", $serviceId);
    $stmt->execute();
    $stmt->close();
    header("Location: admin_dashboard.php?page=services");
    exit();
}

// Fetch services
$services = [];
$result = mysqli_query($conn, "SELECT * FROM services ORDER BY id ASC");
while ($row = mysqli_fetch_assoc($result)) {
    $services[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Services | Admin Dashboard</title>
  <link rel="stylesheet" href="AdminDashboard/style/manage_services.css"> <!-- New Pure CSS -->
</head>
<body>

<div class="dashboard-container">
  <header class="dashboard-header">
    <h1>Manage Services</h1>
  </header>

  <!-- Add Service Form -->
  <form method="POST" class="service-form">
    <h2>Add New Service</h2>
    <div class="form-group">
      <label for="service_name">Service Name</label>
      <input type="text" name="service_name" id="service_name" required>
    </div>
    <div class="form-group">
      <label for="description">Service Description</label>
      <textarea name="description" id="description" rows="3" required></textarea>
    </div>
    <button type="submit" name="add_service" class="btn-submit">Add Service</button>
  </form>

  <!-- Services Table -->
  <div class="table-wrapper">
    <table class="services-table">
      <thead>
        <tr>
          <th>#</th>
          <th>Service Name</th>
          <th>Description</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (count($services) > 0): ?>
            <?php foreach ($services as $service): ?>
                <tr>
                    <td><?= $service['id'] ?></td>
                    <td><?= htmlspecialchars($service['service_name']) ?></td>
                    <td><?= htmlspecialchars($service['description']) ?></td>
                    <td>
                        <form method="POST" onsubmit="return confirm('Are you sure you want to delete this service?');" class="inline-form">
                            <input type="hidden" name="service_id" value="<?= $service['id'] ?>">
                            <button type="submit" name="delete_service" class="btn-delete">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="4" class="no-data">No services found.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

</body>
</html>
