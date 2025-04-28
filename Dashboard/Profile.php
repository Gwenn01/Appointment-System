<?php
// Check if user is logged in
if (!isset($_SESSION['userid'])) {
    if (!isset($_SESSION['userid'])) {
        header("Location: ../login.php");
        exit();
    }
}

// Database connection
require(__DIR__ . '/../Database/database.php');

// Fetch user info
$userId = $_SESSION['userid'];
$query = "SELECT * FROM users WHERE id = $userId";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Profile | Appointment System</title>
  <link rel="stylesheet" href="Dashboard/style/profile.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<div class="profile-container">
  <h2 class="profile-title"><i class="fa-solid fa-user-circle"></i> My Profile</h2>

  <h4><?= htmlspecialchars($user['name']) ?></h4>

  <div class="profile-info">
    <p><i class="fa-solid fa-envelope"></i> <?= htmlspecialchars($user['email']) ?></p>
    <p><i class="fa-solid fa-phone"></i> <?= htmlspecialchars($user['phone_number']) ?></p>
    <p><i class="fa-solid fa-venus-mars"></i> <?= htmlspecialchars($user['gender']) ?></p>
    <p><i class="fa-solid fa-calendar-days"></i> <?= htmlspecialchars($user['date_of_birth']) ?></p>
    <p><i class="fa-solid fa-house"></i> <?= htmlspecialchars($user['address']) ?></p>
  </div>

  <button class="edit-profile-btn" id="openModalBtn">
    <i class="fa-solid fa-pen"></i> Edit Profile
  </button>

  <hr class="divider">

  <h5 class="section-title"><i class="fa-solid fa-key"></i> Change Password</h5>
  <form method="POST" action="Backend/change_password.php" class="form-style">
    <input type="password" name="current_password" class="form-control" placeholder="Current Password" required>
    <input type="password" name="new_password" class="form-control" placeholder="New Password" required>
    <input type="password" name="confirm_password" class="form-control" placeholder="Confirm New Password" required>
    <button type="submit" class="btn-success w-100"><i class="fa-solid fa-floppy-disk"></i> Update Password</button>
  </form>
</div>

<!-- Modal Edit Profile -->
<div class="modal" id="editProfileModal">
  <div class="modal-content">
    <div class="modal-header">
      <h5><i class="fa-solid fa-pen"></i> Edit Profile</h5>
      <button class="close-modal" id="closeModalBtn">&times;</button>
    </div>

    <form method="POST" action="Backend/update_profile.php" class="form-style">
      <input type="hidden" name="user_id" value="<?= $user['id'] ?>">

      <label>Full Name</label>
      <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($user['name']) ?>" required>

      <label>Phone Number</label>
      <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($user['phone_number']) ?>" required>

      <label>Gender</label>
      <select name="gender" class="form-control" required>
        <option value="male" <?= $user['gender'] == 'male' ? 'selected' : '' ?>>Male</option>
        <option value="female" <?= $user['gender'] == 'female' ? 'selected' : '' ?>>Female</option>
        <option value="other" <?= $user['gender'] == 'other' ? 'selected' : '' ?>>Other</option>
      </select>

      <label>Birthday</label>
      <input type="date" name="birthday" class="form-control" value="<?= $user['date_of_birth'] ?>" required>

      <label>Address</label>
      <textarea name="address" rows="2" class="form-control" required><?= htmlspecialchars($user['address']) ?></textarea>

      <button type="submit" class="btn-primary w-100"><i class="fa-solid fa-floppy-disk"></i> Save Changes</button>
    </form>
  </div>
</div>

<!-- Modal Script -->
<script>
  const openModalBtn = document.getElementById('openModalBtn');
  const closeModalBtn = document.getElementById('closeModalBtn');
  const modal = document.getElementById('editProfileModal');

  openModalBtn.addEventListener('click', () => {
    modal.classList.add('show');
  });

  closeModalBtn.addEventListener('click', () => {
    modal.classList.remove('show');
  });

  window.addEventListener('click', (e) => {
    if (e.target === modal) {
      modal.classList.remove('show');
    }
  });
</script>

</body>
</html>
