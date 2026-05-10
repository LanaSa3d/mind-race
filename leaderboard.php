<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Leaderboard';
$pdo = getDbConnection();
$gameCode = normalizeGameCode((string) ($_GET['game_code'] ?? ''));
$gameCodes = $pdo->query('SELECT game_code, title FROM quizzes ORDER BY title ASC')->fetchAll();

// Read players and count correct answers.
if ($gameCode !== '') {
    $statement = $pdo->prepare(
        'SELECT players.player_name,
                players.game_code,
                players.score,
                COALESCE(SUM(answers.is_correct), 0) AS correct_answers
         FROM players
         LEFT JOIN answers ON answers.player_id = players.id
         WHERE players.game_code = :game_code
         GROUP BY players.id
         ORDER BY players.score DESC, correct_answers DESC, players.joined_at ASC'
    );
    $statement->execute(['game_code' => $gameCode]);
} else {
    $statement = $pdo->query(
        'SELECT players.player_name,
                players.game_code,
                players.score,
                COALESCE(SUM(answers.is_correct), 0) AS correct_answers
         FROM players
         LEFT JOIN answers ON answers.player_id = players.id
         GROUP BY players.id
         ORDER BY players.score DESC, correct_answers DESC, players.joined_at ASC'
    );
}

$players = $statement->fetchAll();

require_once __DIR__ . '/includes/header.php';
?>

<section class="page-hero compact-hero leaderboard-hero">
    <div class="container">
        <p class="eyebrow">Leaderboard</p>
        <h1>Top racers by score.</h1>
        <p>Players are ranked by total score, then by correct answers.</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <form class="filter-bar" method="get" action="leaderboard.php">
            <label for="game_code">Filter by Quiz</label>
            <select id="game_code" name="game_code">
                <option value="">All games</option>
                <?php foreach ($gameCodes as $code): ?>
                    <option value="<?= e($code['game_code']); ?>"<?= $gameCode === $code['game_code'] ? ' selected' : ''; ?>>
                        <?= e($code['title']); ?> (<?= e($code['game_code']); ?>)
                    </option>
                <?php endforeach; ?>
            </select>
            <button class="btn btn-primary" type="submit">View</button>
        </form>

        <div class="leaderboard-list">
            <?php foreach ($players as $index => $player): ?>
                <article class="leader-card">
                    <span class="rank-badge">#<?= e((string) ($index + 1)); ?></span>
                    <div>
                        <h2><?= e($player['player_name']); ?></h2>
                        <p>Game code: <strong><?= e($player['game_code']); ?></strong></p>
                    </div>
                    <div class="score-box">
                        <strong><?= e((string) $player['score']); ?></strong>
                        <span>points</span>
                    </div>
                    <div class="score-box">
                        <strong><?= e((string) $player['correct_answers']); ?></strong>
                        <span>correct</span>
                    </div>
                </article>
            <?php endforeach; ?>

            <?php if (!$players): ?>
                <div class="panel">
                    <p>No players found for this game yet.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
