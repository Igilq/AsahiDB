<?php
include 'db.php';
$currentLang = $_GET['lang'] ?? 'pl';

$stmt = $pdo->prepare("SELECT * FROM games WHERE id = ?");
$stmt->execute([$_GET['id']]);
$game = $stmt->fetch();
$translatedTitle = getTranslation($pdo, $game['id'], $currentLang) ?: $game['title'];
?>

<!DOCTYPE html>
<html lang="<?php echo $currentLang; ?>" data-theme="light">
<head>
    <meta charset="UTF-8">
    <title><?php echo $translatedTitle; ?> - AsahiDB</title>
    <link rel="stylesheet" href="style.css">
    <script>
        function toggleTheme() {
            const html = document.documentElement;
            html.setAttribute('data-theme', html.getAttribute('data-theme') === 'light' ? 'dark' : 'light');
            localStorage.setItem('theme', html.getAttribute('data-theme'));
        }
        document.addEventListener('DOMContentLoaded', () => {
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-theme', savedTheme);
        });
        function vote(badge) {
            if (confirm(`Zmienić status na ${badge}?`)) {
                window.location.href = `vote.php?game_id=<?php echo $_GET['id']; ?>&badge=${badge}&lang=<?php echo $currentLang; ?>`;
            }
        }
    </script>
</head>
<body>
    <header>
        <h1>AsahiDB</h1>
        <div>
            <button class="theme-toggle" onclick="toggleTheme()">🌓 Przełącz motyw</button>
            <div class="lang-switcher">
                <a href="?id=<?php echo $_GET['id']; ?>&lang=pl">🇵🇱 Polski</a> |
                <a href="?id=<?php echo $_GET['id']; ?>&lang=en">🇬🇧 English</a>
            </div>
        </div>
    </header>

    <div class="game-card">
        <h2><?php echo $translatedTitle; ?> <span class='badge <?php echo $game['badge']; ?>'><?php echo $game['badge']; ?></span></h2>
        <p><?php echo $game['description']; ?></p>

        <div class="vote-buttons">
            <button class="vote-btn" onclick="vote('Platinum')">Platinum</button>
            <button class="vote-btn" onclick="vote('Gold')">Gold</button>
            <button class="vote-btn" onclick="vote('Silver')">Silver</button>
            <button class="vote-btn" onclick="vote('Bronze')">Bronze</button>
            <button class="vote-btn" onclick="vote('Borked')">Borked</button>
        </div>

        <h3>Komentarze</h3>
        <?php
        $stmt = $pdo->prepare("SELECT * FROM comments WHERE game_id = ?");
        $stmt->execute([$_GET['id']]);
        while ($comment = $stmt->fetch()) {
            echo "<div class='comment'>";
            echo "<p><strong>Użytkownik #{$comment['user_id']}:</strong> {$comment['content']}</p>";
            echo "</div>";
        }
        ?>

        <h3>Dodaj komentarz</h3>
        <form action="add_comment.php" method="post">
            <input type="hidden" name="game_id" value="<?php echo $_GET['id']; ?>">
            <input type="hidden" name="lang" value="<?php echo $currentLang; ?>">
            <textarea name="content" required></textarea><br>
            <button type="submit">Wyślij</button>
        </form>
    </div>
</body>
</html>
