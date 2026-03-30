<?php
require_once 'auth.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title><?php echo isset($pageTitle) ? $pageTitle : 'SPMF'; ?></title>
  <link rel="stylesheet" href="/assets/css/styles.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
</head>
<body>
  <nav class="navbar">
    <div class="container">
      <h1>SPMF</h1>
      <ul class="nav-links">
        <li><a href="index.php">Home</a></li>
        <li><a href="portfolio.php">Portfolio</a></li>
        <li><a href="certificate.php">Certificates</a></li>
        <li><a href="profile.php">Public Profile</a></li>
        <li><a href="dashboard.php">Dashboard</a></li>
        <?php if (isLoggedIn()): ?>
          <li><a href="logout.php" class="btn">Logout</a></li>
        <?php else: ?>
          <li><a href="login.php" class="btn">Login</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </nav>