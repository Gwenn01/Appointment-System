<?php
if (!isset($_SESSION['adminid'])) {
    if (!isset($_SESSION['adminid'])) {
        header("Location: ../admin_login.php");
        exit();
    }
}

require(__DIR__ . '/../Database/database.php');

// Fetch pending appointments
$appointments = mysqli_query($conn, "
    SELECT a.id, u.name, a.status, t.slot_date
    FROM appointments a
    JOIN users u ON a.user_id = u.id
    JOIN time_slots t ON a.time_slot_id = t.id
    WHERE a.status = 'pending'
");

// Fetch pending user registrations
$pending_users = mysqli_query($conn, "
    SELECT id, name, email, created_at FROM users WHERE is_verified = 0
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Pending Approvals | Admin Dashboard</title>
  <link rel="stylesheet" href="AdminDashboard/style/pending_approvals.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<div class="dashboard-container">
  <header class="dashboard-header">
    <h1>Pending Approvals</h1>
  </header>

  <div class="search-filter-bar">
    <input type="text" id="searchApproval" class="search-input" placeholder="🔍 Search requests...">
    <select id="filterApprovalType" class="filter-select">
      <option value="">Filter by Type</option>
      <option value="Appointment">Appointment</option>
      <option value="User Registration">User Registration</option>
    </select>
    <button class="btn-refresh" onclick="location.reload();"><i class="fa-solid fa-arrows-rotate"></i> Refresh</button>
  </div>

  <div class="table-wrapper">
    <table class="approvals-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Type</th>
          <th>Request Date</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody id="approvalsTable">
        <?php while ($row = mysqli_fetch_assoc($appointments)): ?>
        <tr>
          <td><?= str_pad($row['id'], 3, '0', STR_PAD_LEFT); ?></td>
          <td><?= htmlspecialchars($row['name']); ?></td>
          <td><span class="badge badge-appointment">Appointment</span></td>
          <td><?= htmlspecialchars($row['slot_date']); ?></td>
          <td><span class="badge badge-pending">Pending</span></td>
          <td>
            <form method="POST" action="Backend/approve_request.php" class="inline-form">
              <input type="hidden" name="type" value="appointment">
              <input type="hidden" name="id" value="<?= $row['id']; ?>">
              <button class="btn-approve"><i class="fa-solid fa-check"></i> Approve</button>
            </form>
            <form method="POST" action="Backend/reject_request.php" class="inline-form">
              <input type="hidden" name="type" value="appointment">
              <input type="hidden" name="id" value="<?= $row['id']; ?>">
              <button class="btn-reject"><i class="fa-solid fa-xmark"></i> Reject</button>
            </form>
          </td>
        </tr>
        <?php endwhile; ?>

        <?php while ($row = mysqli_fetch_assoc($pending_users)): ?>
        <tr>
          <td><?= str_pad($row['id'], 3, '0', STR_PAD_LEFT); ?></td>
          <td><?= htmlspecialchars($row['name']); ?></td>
          <td><span class="badge badge-registration">User Registration</span></td>
          <td><?= htmlspecialchars(date('Y-m-d', strtotime($row['created_at']))); ?></td>
          <td><span class="badge badge-pending">Pending</span></td>
          <td>
            <form method="POST" action="Backend/approve_request.php" class="inline-form">
              <input type="hidden" name="type" value="registration">
              <input type="hidden" name="id" value="<?= $row['id']; ?>">
              <button class="btn-approve"><i class="fa-solid fa-check"></i> Approve</button>
            </form>
            <form method="POST" action="Backend/reject_request.php" class="inline-form">
              <input type="hidden" name="type" value="registration">
              <input type="hidden" name="id" value="<?= $row['id']; ?>">
              <button class="btn-reject"><i class="fa-solid fa-xmark"></i> Reject</button>
            </form>
          </td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
  const searchInput = document.getElementById("searchApproval");
  const filterType = document.getElementById("filterApprovalType");
  const rows = document.querySelectorAll("#approvalsTable tr");

  function filterRows() {
    const search = searchInput.value.toLowerCase();
    const type = filterType.value.toLowerCase();

    rows.forEach(row => {
      const cells = row.querySelectorAll("td");
      const id = cells[0].textContent.toLowerCase();
      const name = cells[1].textContent.toLowerCase();
      const requestType = cells[2].textContent.toLowerCase();

      const matchesSearch = id.includes(search) || name.includes(search);
      const matchesType = !type || requestType.includes(type);

      row.style.display = matchesSearch && matchesType ? "" : "none";
    });
  }

  searchInput.addEventListener("input", filterRows);
  filterType.addEventListener("change", filterRows);
});
</script>

</body>
</html>
