<?php
require_once 'includes/auth.php';
redirectIfNotLoggedIn();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = sanitizeInput($_POST['title']);
    $issuer = sanitizeInput($_POST['issuer']);
    $date = sanitizeInput($_POST['date']);
    
    if (isset($_FILES['certFile']) && $_FILES['certFile']['error'] === UPLOAD_ERR_OK) {
        $uploadResult = handleFileUpload($_FILES['certFile']);
        
        if (isset($uploadResult['error'])) {
            $error = $uploadResult['error'];
        } else {
            $stmt = $conn->prepare("INSERT INTO certificates (user_id, title, issuer, issue_date, file_path) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("issss", $_SESSION['user_id'], $title, $issuer, $date, $uploadResult['path']);
            
            if ($stmt->execute()) {
                $success = "Certificate uploaded successfully!";
            } else {
                $error = "Failed to save certificate details.";
            }
        }
    } else {
        $error = "Please select a certificate file to upload.";
    }
}

$certificates = getUserCertificates($_SESSION['user_id']);

$pageTitle = "Certificates | SPMF";
require_once 'includes/header.php';
?>

<section class="certificates-page">
  <h2>Certificates & Achievements</h2>
  <p>Showcase your hard-earned certificates here!</p>

  <?php if ($error): ?>
    <div class="error"><?php echo $error; ?></div>
  <?php endif; ?>
  <?php if ($success): ?>
    <div class="success"><?php echo $success; ?></div>
  <?php endif; ?>

  <form id="certForm" class="cert-upload-form" method="POST" enctype="multipart/form-data">
    <input type="text" id="certTitle" name="title" placeholder="Certificate Title" required />
    <input type="text" id="certIssuer" name="issuer" placeholder="Issuing Organization" required />
    <input type="date" id="certDate" name="date" required />
    <input type="file" id="certFile" name="certFile" accept="image/*,application/pdf" required />
    <button type="submit" class="btn">Upload Certificate</button>
  </form>

  <div class="cert-list" id="certList">
    <?php foreach ($certificates as $cert): ?>
      <div class="cert-card">
        <?php if (strpos($cert['file_path'], '.pdf') !== false): ?>
          <embed src="<?php echo $cert['file_path']; ?>" width="250" height="200" type="application/pdf">
        <?php else: ?>
          <img src="<?php echo $cert['file_path']; ?>" alt="<?php echo htmlspecialchars($cert['title']); ?>" style="max-width: 250px; max-height: 200px;">
        <?php endif; ?>
        <h4><?php echo htmlspecialchars($cert['title']); ?></h4>
        <p>Issued by: <?php echo htmlspecialchars($cert['issuer']); ?></p>
        <p>Date: <?php echo date('F j, Y', strtotime($cert['issue_date'])); ?></p>
        <a href="<?php echo $cert['file_path']; ?>" target="_blank" class="btn">View Certificate</a>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<?php
require_once 'includes/footer.php';
?>