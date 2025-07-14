<?php
require_once("../lib.php");
$board = "g";
$threads = getThreads($board);
?>
<!DOCTYPE html>
<html>
<head><title>/g/ - 921480</title></head>
<body>
<h1>/g/ - General</h1>
<a href="new.php">Start a new thread</a><hr>
<?php foreach ($threads as $thread): ?>
  <div>
    <h3><a href="thread.php?id=<?= $thread['id'] ?>"><?= htmlspecialchars($thread['title']) ?></a></h3>
    <p><?= nl2br(htmlspecialchars($thread['content'])) ?></p>
    <?php if ($thread['image']): ?>
      <img src="/uploads/<?= htmlspecialchars($thread['image']) ?>" width="150"><br>
    <?php endif; ?>
    <p>By retard<?= $thread['user_id'] ?> at <?= $thread['created'] ?></p>
  </div><hr>
<?php endforeach; ?>
</body>
</html>