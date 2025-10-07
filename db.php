<?php
$db_file = __DIR__ . '/asahidb.sqlite';
try {
    $pdo = new PDO("sqlite:$db_file");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Tworzenie tabel, jeśli nie istnieją
    $pdo->exec("
    CREATE TABLE IF NOT EXISTS games (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        title TEXT NOT NULL,
        description TEXT,
        badge TEXT CHECK(badge IN ('Platinum', 'Gold', 'Silver', 'Bronze', 'Borked')),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );

    CREATE TABLE IF NOT EXISTS comments (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        game_id INTEGER NOT NULL,
        user_id INTEGER NOT NULL,
        content TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (game_id) REFERENCES games(id)
    );

    CREATE TABLE IF NOT EXISTS translations (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        game_id INTEGER NOT NULL,
        lang TEXT NOT NULL,
        title TEXT NOT NULL,
        FOREIGN KEY (game_id) REFERENCES games(id),
        UNIQUE(game_id, lang)
    );
    ");

    // Dodaj przykładowe dane, jeśli tabelki są puste
    $gamesCount = $pdo->query("SELECT COUNT(*) FROM games")->fetchColumn();
    if ($gamesCount == 0) {
        $pdo->exec("
        INSERT INTO games (title, description, badge) VALUES
        ('The Witcher 3', 'Ostatnie życzenie Geralta z Rivii.', 'Platinum'),
        ('Cyberpunk 2077', 'Gra akcji RPG w futurystycznym świecie.', 'Gold');

        INSERT INTO translations (game_id, lang, title) VALUES
        (1, 'en', 'The Witcher 3'),
        (1, 'pl', 'Wiedźmin 3'),
        (2, 'en', 'Cyberpunk 2077'),
        (2, 'pl', 'Cyberpunk 2077');
        ");
    }
} catch (PDOException $e) {
    die("Błąd połączenia z bazą SQLite: " . $e->getMessage());
}

function getTranslation($pdo, $gameId, $lang = 'pl') {
    $stmt = $pdo->prepare("SELECT title FROM translations WHERE game_id = ? AND lang = ?");
    $stmt->execute([$gameId, $lang]);
    $translation = $stmt->fetch();
    return $translation ? $translation['title'] : null;
}
?>
