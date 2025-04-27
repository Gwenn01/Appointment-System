<?php
if (!isset($_SESSION['adminid'])) {
    header("Location: ../admin_login.php");
    exit();
}

require(__DIR__ . '/../Database/database.php');

// Handle user deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_user'])) {
    $userId = intval($_POST['user_id']);
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->close();
    header("Location: admin_dashboard.php?page=users");
    exit();
}

// Handle user update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_user'])) {
    $userId = intval($_POST['user_id']);
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);

    if (!empty($name) && !empty($email)) {
        $stmt = $conn->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
        $stmt->bind_param("ssi", $name, $email, $userId);
        $stmt->execute();
        $stmt->close();
    }
    header("Location: admin_dashboard.php?page=users");
    exit();
}

// Fetch admins
$admins = [];
$adminQuery = mysqli_query($conn, "SELECT id, name, email FROM admins");
while ($row = mysqli_fetch_assoc($adminQuery)) {
    $row['role'] = 'Admin';
    $admins[] = $row;
}

// Fetch users
$customers = [];
$userQuery = mysqli_query($conn, "SELECT id, name, email FROM users");
while ($row = mysqli_fetch_assoc($userQuery)) {
    $row['role'] = 'Customer';
    $customers[] = $row;
}

// Combine
$accounts = array_merge($admins, $customers);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Accounts | Admin Dashboard</title>
<link rel="stylesheet" href="AdminDashboard/style/manage_accounts.css"> <!-- Your custom pure CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
</head>
<body>

<div class="dashboard-container">
  <header class="dashboard-header">
    <h1>Manage Accounts</h1>
  </header>

  <div class="search-filter-bar">
    <input type="text" id="searchAccount" class="search-input" placeholder="🔍 Search users...">
    <select id="filterRole" class="filter-select">
      <option value="">Filter by Role</option>
      <option value="Admin">Admin</option>
      <option value="Customer">Customer</option>
    </select>
  </div>

  <div class="table-wrapper">
    <table class="accounts-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Email</th>
          <th>Role</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody id="accountsTable">
        <?php foreach ($accounts as $account): ?>
        <tr>
          <td><?= str_pad($account['id'], 3, '0', STR_PAD_LEFT); ?></td>
          <td><?= htmlspecialchars($account['name']); ?></td>
          <td><?= htmlspecialchars($account['email']); ?></td>
          <td><span class="badge <?= $account['role'] === 'Admin' ? 'badge-admin' : 'badge-customer'; ?>"><?= $account['role']; ?></span></td>
          <td>
            <?php if ($account['role'] === 'Admin'): ?>
              <a href="edit_admin.php?id=<?= $account['id']; ?>" class="btn-edit"><i class="bi bi-pencil"></i> Edit</a>
              <a href="delete_admin.php?id=<?= $account['id']; ?>" class="btn-delete" onclick="return confirm('Are you sure?')"><i class="bi bi-trash"></i> Delete</a>
            <?php else: ?>
              <button 
                class="btn-edit editBtn"
                data-id="<?= $account['id']; ?>"
                data-name="<?= htmlspecialchars($account['name']); ?>"
                data-email="<?= htmlspecialchars($account['email']); ?>">
                <i class="bi bi-pencil"></i> Edit
              </button>

              <form method="POST" style="display:inline;" onsubmit="return confirm('Are you sure?')">
                <input type="hidden" name="user_id" value="<?= $account['id']; ?>">
                <button type="submit" name="delete_user" class="btn-delete"><i class="bi bi-trash"></i> Delete</button>
              </form>
            <?php endif; ?>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Modal for Edit User -->
<div class="modal" id="editUserModal">
  <div class="modal-content">
    <div class="modal-header">
      <h2>Edit User</h2>
      <span class="close-modal" id="closeModal">&times;</span>
    </div>
    <form method="POST">
      <input type="hidden" name="user_id" id="editUserId">
      <div class="form-group">
        <label>Name</label>
        <input type="text" name="name" id="editUserName" required>
      </div>
      <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" id="editUserEmail" required>
      </div>
      <button type="submit" name="edit_user" class="btn-save">Save Changes</button>
    </form>
  </div>
</div>

<script>
// Open and Load data to modal
document.addEventListener('DOMContentLoaded', () => {
  const editButtons = document.querySelectorAll('.editBtn');
  const modal = document.getElementById('editUserModal');
  const closeModal = document.getElementById('closeModal');
  const userIdInput = document.getElementById('editUserId');
  const userNameInput = document.getElementById('editUserName');
  const userEmailInput = document.getElementById('editUserEmail');

  editButtons.forEach(button => {
    button.addEventListener('click', () => {
      userIdInput.value = button.dataset.id;
      userNameInput.value = button.dataset.name;
      userEmailInput.value = button.dataset.email;
      modal.classList.add('show');
    });
  });

  closeModal.addEventListener('click', () => {
    modal.classList.remove('show');
  });

  window.addEventListener('click', (e) => {
    if (e.target == modal) {
      modal.classList.remove('show');
    }
  });

  // Filter logic
  const searchInput = document.getElementById('searchAccount');
  const filterSelect = document.getElementById('filterRole');
  const rows = document.querySelectorAll('#accountsTable tr');

  function filterAccounts() {
    const search = searchInput.value.toLowerCase();
    const role = filterSelect.value.toLowerCase();

    rows.forEach(row => {
      const cells = row.querySelectorAll('td');
      const name = cells[1].textContent.toLowerCase();
      const userRole = cells[3].textContent.toLowerCase();

      const matchesSearch = name.includes(search);
      const matchesRole = !role || userRole.includes(role);

      row.style.display = matchesSearch && matchesRole ? '' : 'none';
    });
  }

  searchInput.addEventListener('input', filterAccounts);
  filterSelect.addEventListener('change', filterAccounts);
});
</script>

</body>
</html>
