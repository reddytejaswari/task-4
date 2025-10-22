<?php
// Run this once after importing the SQL to create the admin user.
// Usage: open create_admin.php in browser (http://localhost/my_blog/create_admin.php)
include 'db.php';
$username = 'admin';
$password = 'admin123';
$hash = password_hash($password, PASSWORD_DEFAULT);
// check exists
$stmt = $conn->prepare('SELECT id FROM users WHERE username=?');
$stmt->bind_param('s', $username);
$stmt->execute();
if ($stmt->get_result()->num_rows > 0) {
  echo 'Admin user already exists.';
  exit;
}
$stmt = $conn->prepare('INSERT INTO users (username, password, role) VALUES (?, ?, "admin")');
$stmt->bind_param('ss', $username, $hash);
if ($stmt->execute()) {
  echo 'Admin user created. Username: admin / Password: admin123';
} else {
  echo 'Error creating admin.';
}
?>