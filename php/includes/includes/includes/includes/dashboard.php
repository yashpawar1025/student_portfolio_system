<?php
require_once 'includes/auth.php';
redirectIfNotLoggedIn(); // Block unauthorized access

// Fetch user data
$user = getUser($_SESSION['user_id']);

$pageTitle = "Dashboard | SPMF";
require_once 'includes/header.php';
?>

<!-- Dashboard Content -->
<div class="dashboard">
  <h1>Welcome, <?php echo $user['name']; ?>!</h1>
  <div class="stats">
    <div class="card">
      <h3>Certificates</h3>
      <p>5</p>
    </div>
  </div>
</div>

<?php require_once 'includes/footer.php'; ?>