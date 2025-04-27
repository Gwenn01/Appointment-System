<?php
session_start();
require('Database/database.php');

// Step 1: Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);

    // Check if user exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $token = bin2hex(random_bytes(16));
        $_SESSION['reset_token'] = $token;
        $_SESSION['reset_email'] = $email;

        header("Location: Backend/reset_password.php?token=$token");
        exit;
    } else {
        $error = "No user found with that email address.";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="style/forgot_password.css"> <!-- External pure CSS -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

<div class="container">
    <h3 class="title">Forgot Password</h3>

    <?php if (!empty($error)): ?>
        <div class="alert"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" class="form-card">
        <div class="form-group">
            <label for="email">Enter your email address</label>
            <input type="email" name="email" id="email" required>
        </div>
        <button type="submit" class="btn-submit">Verify Email</button>
    </form>
</div>

</body>
</html>
