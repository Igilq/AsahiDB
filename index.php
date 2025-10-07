<?php
include 'db.php';
$currentLang = $_GET['lang'] ?? 'pl';
?>

<!DOCTYPE html>
<html lang="<?php echo $currentLang; ?>" data-theme="light">
<head>
    <meta charset="UTF-8">
    <title>AsahiDB - Gry na Asahi Linux</title>
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
    </script>
</head>
<body>
    <header>
        <h1>AsahiDB</h1>
        <div>
            <button class="theme-toggle" onclick="toggleTheme()">🌓 Przełącz motyw</button>
            <div class="lang-switcher">
                <a href="?lang=pl">🇵🇱 Polski</a> |
                <a href="?lang=en">🇬🇧 English</a>
            </div>
        </div>
    </header>

    <a href="add_game.php" class="add-game-btn">Dodaj nową grę</a>

    <ul class="game-list">
        <?php
        $stmt = $pdo->query("SELECT * FROM games");
        while ($game = $stmt->fetch()) {
            $translatedTitle = getTranslation($pdo, $game['id'], $currentLang) ?: $game['title'];
            echo '<li class="game-card">';
            echo "<h3>$translatedTitle <span class='badge {$game['badge']}'>{$game['badge']}</span></h3>";
            echo "<p>{$game['description']}</p>";
            echo "<a href='game.php?id={$game['id']}&lang=$currentLang'>Zobacz szczegóły</a>";
            echo '</li>';
        }
        ?>
    </ul>
</body>
</html>
