<?php
require_once 'includes/auth.php';
redirectIfNotLoggedIn();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = sanitizeInput($_POST['title']);
    $description = sanitizeInput($_POST['description']);
    $technologies = sanitizeInput($_POST['technologies']);
    $duration = sanitizeInput($_POST['duration']);
    $projectUrl = sanitizeInput($_POST['project_url']);
    $filePath = '';
    
    if (isset($_FILES['project_file']) && $_FILES['project_file']['error'] === UPLOAD_ERR_OK) {
        $uploadResult = handleFileUpload($_FILES['project_file']);
        
        if (isset($uploadResult['error'])) {
            $error = $uploadResult['error'];
        } else {
            $filePath = $uploadResult['path'];
        }
    }
    
    if (empty($error)) {
        $stmt = $conn->prepare("INSERT INTO projects (user_id, title, description, technologies, duration, project_url, file_path) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issssss", $_SESSION['user_id'], $title, $description, $technologies, $duration, $projectUrl, $filePath);
        
        if ($stmt->execute()) {
            $success = "Project added successfully!";
        } else {
            $error = "Failed to save project details.";
        }
    }
}

$projects = getUserProjects($_SESSION['user_id']);

$pageTitle = "Portfolio | SPMF";
require_once 'includes/header.php';
?>

<section class="portfolio-container">
  <h2>My Projects & Work</h2>
  <p>Here are some of the projects I've worked on during my learning journey.</p>

  <?php if ($error): ?>
    <div class="error"><?php echo $error; ?></div>
  <?php endif; ?>
  <?php if ($success): ?>
    <div class="success"><?php echo $success; ?></div>
  <?php endif; ?>

  <button class="btn-primary" onclick="openProjectModal()">➕ Add New Project</button>

  <div class="project-grid" id="projectList">
    <?php foreach ($projects as $project): ?>
      <div class="project-card">
        <h3><?php echo htmlspecialchars($project['title']); ?></h3>
        <p><?php echo htmlspecialchars($project['description']); ?></p>
        <p><strong>Technologies:</strong> <?php echo htmlspecialchars($project['technologies']); ?></p>
        <p><strong>Duration:</strong> <?php echo htmlspecialchars($project['duration']); ?></p>
        <?php if (!empty($project['project_url'])): ?>
          <p><a href="<?php echo htmlspecialchars($project['project_url']); ?>" target="_blank">View Project</a></p>
        <?php endif; ?>
        <?php if (!empty($project['file_path'])): ?>
          <a href="<?php echo htmlspecialchars($project['file_path']); ?>" target="_blank" class="btn">View Documentation</a>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<!-- Modal to Add Project -->
<div id="projectModal" class="modal">
  <div class="modal-content">
    <span class="close-btn" onclick="closeProjectModal()">&times;</span>
    <h3>Add New Project</h3>
    <form id="projectForm" method="POST" enctype="multipart/form-data">
      <input type="text" id="projectTitle" name="title" placeholder="Project Title" required>
      <textarea id="projectDesc" name="description" placeholder="Project Description" required></textarea>
      <input type="text" id="projectTech" name="technologies" placeholder="Technologies Used (comma separated)" required>
      <input type="text" id="projectDuration" name="duration" placeholder="Duration (e.g., 2 months)" required>
      <input type="url" id="projectLink" name="project_url" placeholder="Project URL (optional)">
      <input type="file" id="projectFile" name="project_file" accept="application/pdf">
      <button type="submit" class="btn-primary">Save Project</button>
    </form>
  </div>
</div>

<script>
function openProjectModal() {
  document.getElementById('projectModal').style.display = 'flex';
}

function closeProjectModal() {
  document.getElementById('projectModal').style.display = 'none';
}
</script>

<?php
require_once 'includes/footer.php';
?>