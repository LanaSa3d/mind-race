<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Join Game';
$pdo = getDbConnection();
$errors = [];
$gameCodeValue = normalizeGameCode((string) ($_GET['game_code'] ?? ''));

// Add a player after checking that the game code exists.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $playerName = postValue('player_name');
    $gameCodeValue = normalizeGameCode(postValue('game_code'));
    $quiz = getQuizByGameCode($pdo, $gameCodeValue);

    if ($playerName === '') {
        $errors[] = 'Player name is required.';
    }

    if (!$quiz) {
        $errors[] = 'Game code was not found.';
    }

    if (!$errors) {
        $statement = $pdo->prepare(
            'INSERT INTO players (player_name, game_code, score)
             VALUES (:player_name, :game_code, 0)'
        );
        $statement->execute([
            'player_name' => $playerName,
            'game_code' => $gameCodeValue,
        ]);

        $playerId = (int) $pdo->lastInsertId();
        redirect('play.php?player_id=' . $playerId . '&question=1');
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<section class="page-hero compact-hero">
    <div class="container">
        <p class="eyebrow">Join game</p>
        <h1>Enter the race room.</h1>
        <p>Players join by typing their name and the quiz game code shared by the host.</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <form class="form-card form-grid" method="post" action="join_game.php" data-validate>
            <?php if ($errors): ?>
                <div class="flash flash-error">
                    <?= e(implode(' ', $errors)); ?>
                </div>
            <?php endif; ?>

            <div class="field">
                <label for="player_name">Player Name</label>
                <input type="text" id="player_name" name="player_name" value="<?= e($_POST['player_name'] ?? ''); ?>" required>
            </div>

            <div class="field">
                <label for="game_code">Game Code</label>
                <input type="text" id="game_code" name="game_code" value="<?= e($_POST['game_code'] ?? $gameCodeValue); ?>" placeholder="MR1234" required>
            </div>

            <div class="actions">
                <button class="btn btn-primary" type="submit">Join Game</button>
                <a class="btn btn-ghost" href="view_quizzes.php">View Codes</a>
            </div>
        </form>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
