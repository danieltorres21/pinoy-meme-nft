<?php
require_once 'includes/config.php';
require_once 'includes/auth_functions.php';

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    if (loginUser($username, $password)) {
        header("Location: nfts.php");
        exit;
    } else {
        $error = "Invalid username or password.";
    }
}

if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $passwordHash = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    try {
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
        if ($stmt->execute([$username, $email, $passwordHash])) {
            $success = "Registration successful! Please login.";
        } else {
            $error = "Registration failed. Username or email already exists.";
        }
    } catch (PDOException $e) {
        $error = "Registration failed: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pinoy Meme NFT - Portal</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <header>
    <h1>Pinoy Meme NFT</h1>
    <nav>
      <a href="index.php">Home</a>
      <a href="about.php">About</a>
      <a href="nfts.php">NFTs</a>
      <a href="contact.php">Contact</a>
      <a href="portal.php">Portal</a>
    </nav>
  </header>
  
  <main>
    <section class="portal-form">
      <h2>User Portal</h2>

      <!-- Display messages -->
      <?php if (isset($error)): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
      <?php endif; ?>
      <?php if (isset($success)): ?>
        <p class="success"><?= htmlspecialchars($success) ?></p>
      <?php endif; ?>

      <!-- Login Form -->
      <h3>Login</h3>
      <form method="POST">
        <label>Username: <input type="text" name="username" required></label>
        <label>Password: <input type="password" name="password" required></label>
        <button type="submit" name="login">Login</button>
      </form>

      <!-- Register Form -->
      <h3>Register</h3>
      <form method="POST">
        <label>Username: <input type="text" name="username" required></label>
        <label>Email: <input type="email" name="email" required></label>
        <label>Password: <input type="password" name="password" required></label>
        <button type="submit" name="register">Register</button>
      </form>
    </section>
  </main>
</body>
</html>
