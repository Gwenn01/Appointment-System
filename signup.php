<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Customer Sign Up | Appointment System</title>
  <link rel="stylesheet" href="style/login.css"> 
  <link rel="stylesheet" href="style/signup.css"> 
</head>
<body>

  <div class="container">
    <div class="signup-card">
      <h2>Create an Account</h2>
      <p class="subtitle">Join us to clinic appointments easily</p>

      <form action="Backend/insert_user.php" method="POST" class="form-grid">
        <div class="form-column">
          <label>Full Name</label>
          <input type="text" name="fullname" required>

          <label>Username</label>
          <input type="text" name="username" required>

          <label>Email</label>
          <input type="email" name="email" required>

          <label>Phone Number</label>
          <input type="tel" name="phone" required>
        </div>

        <div class="form-column">
          <label>Password</label>
          <input type="password" name="password" required>

          <label>Gender</label>
          <select name="gender" required>
            <option value="male">Male</option>
            <option value="female">Female</option>
          </select>

          <label>Birthday</label>
          <input type="date" name="birthday" required>

          <label>Address</label>
          <input type="text" name="address" required>
        </div>

        <button type="submit" name="submit" class="full-width">Sign Up</button>
      </form>

      <p class="link">Already have an account? <a href="index.php">Login</a></p>
    </div>
  </div>

</body>
</html>
