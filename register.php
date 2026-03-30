<?php
$errors = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["fullName"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirmPassword = $_POST["confirmPassword"];
    $college = trim($_POST["collegeName"]);
    $branch = trim($_POST["branch"]);

    if ($password !== $confirmPassword) {
        $errors = "Passwords do not match!";
    } else {
        // DB connection
        $conn = new mysqli("localhost", "root", "", "spmf");

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Check if user already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $errors = "Email already registered!";
        } else {
            // Insert new user
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (name, email, password, college, branch) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $name, $email, $hashedPassword, $college, $branch);
            if ($stmt->execute()) {
                header("Location: login.php?success=1");
                exit();
            } else {
                $errors = "Registration failed. Please try again.";
            }
        }

        $stmt->close();
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register | SPMF</title>
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: #f7f9fc;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }
    .form-container {
      background: white;
      padding: 2rem;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      width: 350px;
    }
    .form-container h2 {
      text-align: center;
      margin-bottom: 1rem;
    }
    input {
      width: 100%;
      margin: 0.5rem 0;
      padding: 0.7rem;
      border: 1px solid #ccc;
      border-radius: 8px;
    }
    button {
      width: 100%;
      background: #4caf50;
      color: white;
      padding: 0.8rem;
      border: none;
      border-radius: 8px;
      margin-top: 1rem;
      font-size: 1rem;
      cursor: pointer;
    }
    .error {
      color: red;
      font-size: 0.9rem;
      margin-top: 0.5rem;
      text-align: center;
    }
    p {
      text-align: center;
      margin-top: 1rem;
    }
    a {
      color: #4caf50;
      text-decoration: none;
      font-weight: bold;
    }
  </style>
</head>
<body>

<div class="form-container">
  <h2>Create Your Account</h2>
  <form method="POST" action="register.php">
    <input type="text" name="fullName" placeholder="Full Name" required>
    <input type="email" name="email" placeholder="Email Address" required>
    <input type="password" name="password" placeholder="Password" required>
    <input type="password" name="confirmPassword" placeholder="Confirm Password" required>
    <input type="text" name="collegeName" placeholder="College/University" required>
    <input type="text" name="branch" placeholder="Branch/Stream" required>

    <button type="submit">Register</button>

    <?php if (!empty($errors)) : ?>
      <div class="error"><?= $errors ?></div>
    <?php endif; ?>
  </form>

  <p>Already have an account? <a href="login.php">Login here</a></p>
</div>

</body>
</html>
