<?php
require_once("../lib.php");
$board = "h";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $title = $_POST["title"];
  $content = $_POST["content"];
  $image = handleUpload();
  $user_id = getUserId();
  createThread($board, $title, $content, $image, $user_id);
  pruneThreads($board);
  header("Location: index.php");
  exit();
}
?>
<!DOCTYPE html>
<html>
<head><title>New Thread - /g/</title></head>
<body>
<h2>New Thread - /g/</h2>
<form method="POST" enctype="multipart/form-data">
  Title: <input name="title"><br>
  Content: <textarea name="content"></textarea><br>
  Image: <input type="file" name="image"><br>
  <button type="submit">Post</button>
</form>
</body>
</html>