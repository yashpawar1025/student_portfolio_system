<?php
require_once 'includes/config.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitizeInput($_POST['name']);
    $email = sanitizeInput($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash password

    // Insert into database
    $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $password);
    
    if ($stmt->execute()) {
        header("Location: login.php?success=1"); // Redirect after success
    } else {
        $error = "Registration failed. Email may already exist.";
    }
}

$pageTitle = "Register | SPMF";
require_once 'includes/header.php';
?>

<!-- Registration Form -->
<div class="form-container">
  <?php if (isset($error)) echo "<div class='error'>$error</div>"; ?>
  <form method="POST">
    <input type="text" name="name" placeholder="Full Name" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Register</button>
  </form>
</div>

<?php require_once 'includes/footer.php'; ?>