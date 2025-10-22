<?php
include 'db.php';
include 'header.php';

session_start();
$search = $_GET['search'] ?? '';
$page = max(1, (int)($_GET['page'] ?? 1));
$limit = 5;
$offset = ($page - 1) * $limit;

// Count total (for pagination)
$countStmt = $conn->prepare("SELECT COUNT(*) as cnt FROM posts WHERE title LIKE CONCAT('%',?,'%') OR content LIKE CONCAT('%',?,'%')");
$countStmt->bind_param("ss", $search, $search);
$countStmt->execute();
$countRes = $countStmt->get_result()->fetch_assoc();
$total = $countRes['cnt'];
$pages = ceil($total / $limit);

// Fetch page
$stmt = $conn->prepare("SELECT * FROM posts WHERE title LIKE CONCAT('%',?,'%') OR content LIKE CONCAT('%',?,'%') ORDER BY created_at DESC LIMIT ? OFFSET ?");
$stmt->bind_param("ssii", $search, $search, $limit, $offset);
$stmt->execute();
$result = $stmt->get_result();
?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h2>Posts</h2>
  <form class="d-flex" method="get">
    <input name="search" class="form-control me-2" placeholder="Search" value="<?=htmlspecialchars($search)?>">
    <button class="btn btn-light">Search</button>
  </form>
</div>

<?php while($row = $result->fetch_assoc()): ?>
  <div class="card mb-3">
    <div class="card-body">
      <h5 class="card-title"><?=htmlspecialchars($row['title'])?></h5>
      <p class="card-text"><?=nl2br(htmlspecialchars(substr($row['content'],0,300)))?><?php if(strlen($row['content'])>300) echo '...'; ?></p>
      <p class="text-muted"><small><?=htmlspecialchars($row['created_at'])?></small></p>
      <?php if(isset($_SESSION['username'])): ?>
        <a href="edit.php?id=<?=$row['id']?>" class="btn btn-sm btn-primary">Edit</a>
        <a href="delete.php?id=<?=$row['id']?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this post?')">Delete</a>
      <?php endif; ?>
    </div>
  </div>
<?php endwhile; ?>

<nav>
  <ul class="pagination">
    <?php for($p=1;$p<=$pages;$p++): ?>
      <li class="page-item <?=($p==$page)?'active':''?>"><a class="page-link" href="?search=<?=urlencode($search)?>&page=<?=$p?>"><?=$p?></a></li>
    <?php endfor; ?>
  </ul>
</nav>

<?php include 'footer.php'; ?>
