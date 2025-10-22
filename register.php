<?php
include 'db.php';
include 'header.php';
session_start();
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = trim($_POST['username']);
  $password = $_POST['password'];
  if($username === '' || $password === '') $errors[] = 'Please fill all fields';
  if(empty($errors)) {
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'editor')");
    $stmt->bind_param('ss', $username, $hash);
    if($stmt->execute()){
      echo '<div class="alert alert-success">Registered successfully. <a href="login.php">Login</a></div>';
    } else {
      $errors[] = 'Username already exists or error';
    }
  }
}
?>
<h2>Register</h2>
<?php foreach($errors as $e) echo '<div class="alert alert-danger">'.htmlspecialchars($e).'</div>'; ?>
<form method="post">
  <div class="mb-3"><label>Username</label><input name="username" class="form-control"></div>
  <div class="mb-3"><label>Password</label><input name="password" type="password" class="form-control"></div>
  <button class="btn btn-primary">Register</button>
</form>
<?php include 'footer.php'; ?>
