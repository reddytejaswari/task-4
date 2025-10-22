<?php
include 'db.php';
include 'header.php';
session_start();
$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = $_POST['username'];
  $password = $_POST['password'];
  $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE username = ?");
  $stmt->bind_param('s', $username);
  $stmt->execute();
  $res = $stmt->get_result();
  if ($user = $res->fetch_assoc()) {
    if (password_verify($password, $user['password'])) {
      $_SESSION['username'] = $user['username'];
      $_SESSION['role'] = $user['role'];
      header('Location: index.php');
      exit;
    } else $err = 'Invalid credentials';
  } else $err = 'Invalid credentials';
}
?>
<h2>Login</h2>
<?php if($err) echo '<div class="alert alert-danger">'.htmlspecialchars($err).'</div>'; ?>
<form method="post">
  <div class="mb-3"><label>Username</label><input name="username" class="form-control"></div>
  <div class="mb-3"><label>Password</label><input name="password" type="password" class="form-control"></div>
  <button class="btn btn-primary">Login</button>
</form>
<?php include 'footer.php'; ?>
