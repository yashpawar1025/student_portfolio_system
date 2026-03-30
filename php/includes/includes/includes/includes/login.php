<?php
require_once 'includes/config.php';

// Redirect if already logged in
if (isLoggedIn()) header("Location: dashboard.php");

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitizeInput($_POST['email']);
    $password = $_POST['password'];

    // Fetch user from database
    $stmt = $conn->prepare("SELECT id, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    // Verify password
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id']; // Start session
        header("Location: dashboard.php"); // Redirect to dashboard
    } else {
        $error = "Invalid email or password!";
    }
}

$pageTitle = "Login | SPMF";
require_once 'includes/header.php';
?>

<!-- Login Form -->
<div class="form-container">
  <?php if (isset($error)) echo "<div class='error'>$error</div>"; ?>
  <form method="POST">
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Login</button>
  </form>
</div>

<?php require_once 'includes/footer.php'; ?>