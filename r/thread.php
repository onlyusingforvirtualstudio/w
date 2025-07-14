<?php
require_once("../lib.php");
$board = "r";
$thread_id = $_GET["id"];
$thread = getThread($thread_id, $board);
$posts = getPosts($thread_id);
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $content = $_POST["content"];
  $image = handleUpload();
  $user_id = getUserId();
  addPost($thread_id, $content, $image, $user_id);
  header("Location: thread.php?id=$thread_id");
  exit();
}
?>
<!DOCTYPE html>
<html>
<head><title><?= htmlspecialchars($thread['title']) ?> - /g/</title></head>
<body>
<h2><?= htmlspecialchars($thread['title']) ?></h2>
<p><?= nl2br(htmlspecialchars($thread['content'])) ?></p>
<?php if ($thread['image']): ?>
  <img src="/uploads/<?= htmlspecialchars($thread['image']) ?>" width="300"><br>
<?php endif; ?>
<p>By peasant<?= $thread['user_id'] ?> at <?= $thread['created'] ?></p>
<hr>
<h3>Replies:</h3>
<?php foreach ($posts as $post): ?>
  <div>
    <p><?= nl2br(htmlspecialchars($post['content'])) ?></p>
    <?php if ($post['image']): ?>
      <img src="/uploads/<?= htmlspecialchars($post['image']) ?>" width="200"><br>
    <?php endif; ?>
    <p>By peasant<?= $post['user_id'] ?> at <?= $post['created'] ?></p>
  </div><hr>
<?php endforeach; ?>
<form method="POST" enctype="multipart/form-data">
  <textarea name="content" placeholder="Reply..."></textarea><br>
  <input type="file" name="image"><br>
  <button type="submit">Reply</button>
</form>
</body>
</html>