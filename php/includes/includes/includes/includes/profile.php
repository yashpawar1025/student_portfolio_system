<?php
require_once 'includes/auth.php';
redirectIfNotLoggedIn();

$user = getUser($_SESSION['user_id']);
$skills = getUserSkills($_SESSION['user_id']);
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_profile'])) {
        $name = sanitizeInput($_POST['name']);
        $college = sanitizeInput($_POST['college']);
        $branch = sanitizeInput($_POST['branch']);
        $aboutMe = sanitizeInput($_POST['about_me']);
        
        if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === UPLOAD_ERR_OK) {
            $uploadResult = handleFileUpload($_FILES['profile_pic'], 'uploads/profile_pics/');
            
            if (isset($uploadResult['error'])) {
                $error = $uploadResult['error'];
            } else {
                $profilePic = $uploadResult['path'];
                $stmt = $conn->prepare("UPDATE users SET name = ?, college = ?, branch = ?, about_me = ?, profile_pic = ? WHERE id = ?");
                $stmt->bind_param("sssssi", $name, $college, $branch, $aboutMe, $profilePic, $_SESSION['user_id']);
            }
        } else {
            $stmt = $conn->prepare("UPDATE users SET name = ?, college = ?, branch = ?, about_me = ? WHERE id = ?");
            $stmt->bind_param("ssssi", $name, $college, $branch, $aboutMe, $_SESSION['user_id']);
        }
        
        if ($stmt->execute()) {
            $_SESSION['user_name'] = $name;
            $success = "Profile updated successfully!";
            $user = getUser($_SESSION['user_id']); // Refresh user data
        } else {
            $error = "Failed to update profile.";
        }
    } elseif (isset($_POST['update_skills'])) {
        $newSkills = explode(',', sanitizeInput($_POST['skills']));
        
        // Delete existing skills
        $conn->query("DELETE FROM skills WHERE user_id = " . $_SESSION['user_id']);
        
        // Add new skills
        $stmt = $conn->prepare("INSERT INTO skills (user_id, skill_name) VALUES (?, ?)");
        foreach ($newSkills as $skill) {
            $skill = trim($skill);
            if (!empty($skill)) {
                $stmt->bind_param("is", $_SESSION['user_id'], $skill);
                $stmt->execute();
            }
        }
        
        $success = "Skills updated successfully!";
        $skills = getUserSkills($_SESSION['user_id']); // Refresh skills
    }
}

$pageTitle = "Public Profile | SPMF";
require_once 'includes/header.php';
?>

<!-- Profile Header -->
<section class="profile-header">
  <form method="POST" enctype="multipart/form-data" style="display: none;">
    <input type="file" id="uploadPic" name="profile_pic" accept="image/*">
  </form>
  <img src="<?php echo !empty($user['profile_pic']) ? $user['profile_pic'] : 'https://via.placeholder.com/120'; ?>" id="profilePic" alt="Profile Picture">
  <br>
  <button class="edit-btn" onclick="document.getElementById('uploadPic').click()">Change Picture</button>
  <h2 id="profileName"><?php echo htmlspecialchars($user['name']); ?></h2>
  <p id="profileBranch"><?php echo htmlspecialchars($user['branch'] . ' | ' . htmlspecialchars($user['college']); ?></p>
  <p id="profileEmail"><?php echo htmlspecialchars($user['email']); ?></p>
  <button class="edit-btn" onclick="openEditModal('profile')">Edit Info</button>
</section>

<!-- About Me -->
<section class="profile-section">
  <h3>About Me</h3>
  <p id="aboutMe"><?php echo !empty($user['about_me']) ? htmlspecialchars($user['about_me']) : 'Write about yourself...'; ?></p>
  <button class="edit-btn" onclick="openEditModal('about')">Edit About</button>
</section>

<!-- Skills -->
<section class="profile-section">
  <h3>Skills</h3>
  <div class="tags" id="skillsList">
    <?php foreach ($skills as $skill): ?>
      <span class="tag"><?php echo htmlspecialchars($skill); ?></span>
    <?php endforeach; ?>
  </div>
  <button class="edit-btn" onclick="openEditModal('skills')">Edit Skills</button>
</section>

<!-- Edit Modals -->
<div id="editModal" class="modal">
  <div class="modal-content">
    <span class="close-btn" onclick="closeEditModal()">&times;</span>
    <h3 id="modalTitle"></h3>
    <form id="editForm" method="POST">
      <input type="hidden" name="form_type" id="formType">
      <div id="formContent"></div>
      <button type="submit" class="btn">Save Changes</button>
    </form>
  </div>
</div>

<script>
function openEditModal(type) {
  const modal = document.getElementById('editModal');
  const title = document.getElementById('modalTitle');
  const form = document.getElementById('editForm');
  const formContent = document.getElementById('formContent');
  const formType = document.getElementById('formType');
  
  modal.style.display = 'flex';
  
  if (type === 'profile') {
    title.textContent = 'Edit Profile Info';
    formType.value = 'update_profile';
    formContent.innerHTML = `
      <input type="text" name="name" placeholder="Full Name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
      <input type="text" name="college" placeholder="College/University" value="<?php echo htmlspecialchars($user['college']); ?>" required>
      <input type="text" name="branch" placeholder="Branch/Stream" value="<?php echo htmlspecialchars($user['branch']); ?>" required>
    `;
  } else if (type === 'about') {
    title.textContent = 'Edit About Me';
    formType.value = 'update_profile';
    formContent.innerHTML = `
      <textarea name="about_me" placeholder="Tell about yourself..." rows="5" required><?php echo htmlspecialchars($user['about_me']); ?></textarea>
    `;
  } else if (type === 'skills') {
    title.textContent = 'Edit Skills';
    formType.value = 'update_skills';
    formContent.innerHTML = `
      <input type="text" name="skills" placeholder="Enter skills separated by commas" value="<?php echo htmlspecialchars(implode(', ', $skills)); ?>" required>
    `;
  }
}

function closeEditModal() {
  document.getElementById('editModal').style.display = 'none';
}

document.getElementById('uploadPic').addEventListener('change', function() {
  this.form.submit();
});
</script>

<?php
require_once 'includes/footer.php';
?>