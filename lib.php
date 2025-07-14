<?php
session_start();
$db = new PDO("sqlite:db.sqlite3");
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

function initDB() {
  global $db;
  $db->exec("CREATE TABLE IF NOT EXISTS users (id INTEGER PRIMARY KEY AUTOINCREMENT)");
  $db->exec("CREATE TABLE IF NOT EXISTS threads (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    board TEXT, title TEXT, content TEXT, image TEXT,
    user_id INTEGER, created TIMESTAMP DEFAULT CURRENT_TIMESTAMP
  )");
  $db->exec("CREATE TABLE IF NOT EXISTS posts (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    thread_id INTEGER, content TEXT, image TEXT,
    user_id INTEGER, created TIMESTAMP DEFAULT CURRENT_TIMESTAMP
  )");
}
initDB();

function getUserId() {
  global $db;
  if (!isset($_SESSION["user_id"])) {
    $db->exec("INSERT INTO users DEFAULT VALUES");
    $_SESSION["user_id"] = $db->lastInsertId();
  }
  return $_SESSION["user_id"];
}

function createThread($board, $title, $content, $image, $user_id) {
  global $db;
  $stmt = $db->prepare("INSERT INTO threads (board, title, content, image, user_id) VALUES (?, ?, ?, ?, ?)");
  $stmt->execute([$board, $title, $content, $image, $user_id]);
}

function getThreads($board) {
  global $db;
  return $db->query("SELECT * FROM threads WHERE board = '$board' ORDER BY created DESC")->fetchAll(PDO::FETCH_ASSOC);
}

function getThread($id, $board) {
  global $db;
  $stmt = $db->prepare("SELECT * FROM threads WHERE id = ? AND board = ?");
  $stmt->execute([$id, $board]);
  return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getPosts($thread_id) {
  global $db;
  $stmt = $db->prepare("SELECT * FROM posts WHERE thread_id = ? ORDER BY created");
  $stmt->execute([$thread_id]);
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function addPost($thread_id, $content, $image, $user_id) {
  global $db;
  $stmt = $db->prepare("INSERT INTO posts (thread_id, content, image, user_id) VALUES (?, ?, ?, ?)");
  $stmt->execute([$thread_id, $content, $image, $user_id]);
}

function pruneThreads($board) {
  global $db;
  $threads = $db->query("SELECT id FROM threads WHERE board = '$board' ORDER BY created ASC")->fetchAll(PDO::FETCH_ASSOC);
  if (count($threads) > 50) {
    foreach (array_slice($threads, 0, count($threads) - 50) as $t) {
      $db->prepare("DELETE FROM posts WHERE thread_id = ?")->execute([$t['id']]);
      $db->prepare("DELETE FROM threads WHERE id = ?")->execute([$t['id']]);
    }
  }
}

function handleUpload() {
  if (!isset($_FILES["image"]) || $_FILES["image"]["error"] !== UPLOAD_ERR_OK) return "";
  $ext = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
  if (!in_array(strtolower($ext), ["jpg", "jpeg", "png", "gif"])) return "";
  $name = uniqid() . "." . $ext;
  move_uploaded_file($_FILES["image"]["tmp_name"], "uploads/" . $name);
  return $name;
}
?>