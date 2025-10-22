<?php
include 'db.php';
include 'header.php';
session_start();
if (!isset($_SESSION['username'])) {
  header('Location: login.php'); exit;
}
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $title = trim($_POST['title']);
  $content = trim($_POST['content']);
  if ($title === '' || $content === '') $errors[] = 'All fields required';
  if (empty($errors)) {
    $stmt = $conn->prepare('INSERT INTO posts (title, content) VALUES (?, ?)');
    $stmt->bind_param('ss', $title, $content);
    $stmt->execute();
    header('Location: index.php'); exit;
  }
}
?>
<h2>Create Post</h2>
<?php foreach($errors as $e) echo '<div class="alert alert-danger">'.htmlspecialchars($e).'</div>'; ?>
<form method="post">
  <div class="mb-3"><label>Title</label><input name="title" class="form-control"></div>
  <div class="mb-3"><label>Content</label><textarea name="content" class="form-control" rows="6"></textarea></div>
  <button class="btn btn-success">Add Post</button>
</form>
<?php include 'footer.php'; ?>
