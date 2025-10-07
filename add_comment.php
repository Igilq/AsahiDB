<?php
include 'db.php';
$stmt = $pdo->prepare("INSERT INTO comments (game_id, user_id, content) VALUES (?, ?, ?)");
$stmt->execute([$_POST['game_id'], 1, $_POST['content']]);
header("Location: game.php?id={$_POST['game_id']}&lang={$_POST['lang']}");
exit();
?>
