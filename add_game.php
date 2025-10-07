<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Dodaj grę
    $stmt = $pdo->prepare("INSERT INTO games (title, description, badge) VALUES (?, ?, ?)");
    $stmt->execute([$_POST['title'], $_POST['description'], $_POST['badge']]);
    $gameId = $pdo->lastInsertId();

    // Dodaj tłumaczenia
    $stmt = $pdo->prepare("INSERT INTO translations (game_id, lang, title) VALUES (?, 'en', ?)");
    $stmt->execute([$gameId, $_POST['title']]); // Domyślnie angielski tytuł = oryginalny

    if (!empty($_POST['title_pl'])) {
        $stmt = $pdo->prepare("INSERT INTO translations (game_id, lang, title) VALUES (?, 'pl', ?)");
        $stmt->execute([$gameId, $_POST['title_pl']]);
    }

    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pl" data-theme="light">
<head>
    <meta charset="UTF-8">
    <title>Dodaj grę - AsahiDB</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>AsahiDB</h1>
    </header>

    <h2>Dodaj nową grę</h2>
    <form action="add_game.php" method="post">
        <label>Tytuł (angielski): <input type="text" name="title" required></label><br>
        <label>Tytuł (polski): <input type="text" name="title_pl"></label><br>
        <label>Opis: <textarea name="description"></textarea></label><br>
        <label>Odznaka:
            <select name="badge">
                <option value="Platinum">Platinum</option>
                <option value="Gold">Gold</option>
                <option value="Silver">Silver</option>
                <option value="Bronze">Bronze</option>
                <option value="Borked">Borked</option>
            </select>
        </label><br>
        <button type="submit">Dodaj grę</button>
    </form>
</body>
</html>
