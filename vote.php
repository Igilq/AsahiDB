<?php
include 'db.php';
$stmt = $pdo->prepare("UPDATE games SET badge = ? WHERE id = ?");
$stmt->execute([$_GET['badge'], $_GET['game_id']]);
header("Location: game.php?id={$_GET['game_id']}&lang={$_GET['lang']}");
exit();
?>
