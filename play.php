<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Play Quiz';
$pdo = getDbConnection();
$playerId = (int) ($_GET['player_id'] ?? ($_POST['player_id'] ?? 0));
$questionNumber = max(1, (int) ($_GET['question'] ?? 1));

$playerStatement = $pdo->prepare('SELECT * FROM players WHERE id = :id');
$playerStatement->execute(['id' => $playerId]);
$player = $playerStatement->fetch();

if (!$player) {
    setFlash('Please join a game first.', 'error');
    redirect('join_game.php');
}

$quiz = getQuizByGameCode($pdo, $player['game_code']);

if (!$quiz) {
    setFlash('Quiz not found for this player.', 'error');
    redirect('join_game.php');
}

$questions = getQuestionsByQuiz($pdo, (int) $quiz['id']);
$totalQuestions = count($questions);

// Save the submitted answer and move to the next question.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $questionId = (int) ($_POST['question_id'] ?? 0);
    $selectedAnswer = strtoupper(postValue('selected_answer'));
    $responseTime = max(0, (int) ($_POST['response_time'] ?? 0));
    $question = getQuestionById($pdo, $questionId);

    if ($question && (int) $question['quiz_id'] === (int) $quiz['id']) {
        $answerToSave = $selectedAnswer !== '' ? $selectedAnswer : 'N';
        $isCorrect = strtoupper($answerToSave) === strtoupper($question['correct_answer']);
        $earnedPoints = calculateEarnedPoints(
            $answerToSave,
            $question['correct_answer'],
            (int) $question['time_limit'],
            $responseTime,
            (int) $question['points']
        );

        $exists = $pdo->prepare(
            'SELECT COUNT(*) FROM answers
             WHERE player_id = :player_id AND question_id = :question_id'
        );
        $exists->execute([
            'player_id' => $playerId,
            'question_id' => $questionId,
        ]);

        if ((int) $exists->fetchColumn() === 0) {
            $insert = $pdo->prepare(
                'INSERT INTO answers
                 (player_id, question_id, selected_answer, is_correct, response_time, earned_points)
                 VALUES
                 (:player_id, :question_id, :selected_answer, :is_correct, :response_time, :earned_points)'
            );
            $insert->execute([
                'player_id' => $playerId,
                'question_id' => $questionId,
                'selected_answer' => $answerToSave,
                'is_correct' => $isCorrect ? 1 : 0,
                'response_time' => $responseTime,
                'earned_points' => $earnedPoints,
            ]);

            updatePlayerScore($pdo, $playerId);
        }
    }

    $nextQuestion = $questionNumber + 1;

    if ($nextQuestion > $totalQuestions) {
        redirect('leaderboard.php?game_code=' . urlencode($player['game_code']));
    }

    redirect('play.php?player_id=' . $playerId . '&question=' . $nextQuestion);
}

if ($totalQuestions === 0) {
    require_once __DIR__ . '/includes/header.php';
    ?>
    <section class="page-hero compact-hero">
        <div class="container">
            <p class="eyebrow">No questions</p>
            <h1>This quiz has no questions yet.</h1>
            <p>Ask the host to add questions before starting the game.</p>
            <a class="btn btn-light" href="view_quizzes.php">View Quizzes</a>
        </div>
    </section>
    <?php
    require_once __DIR__ . '/includes/footer.php';
    exit;
}

if ($questionNumber > $totalQuestions) {
    redirect('leaderboard.php?game_code=' . urlencode($player['game_code']));
}

$currentQuestion = $questions[$questionNumber - 1];

require_once __DIR__ . '/includes/header.php';
?>

<section class="play-screen">
    <div class="container">
        <div class="play-top">
            <div>
                <p class="eyebrow">Question <?= e((string) $questionNumber); ?> of <?= e((string) $totalQuestions); ?></p>
                <h1><?= e($quiz['title']); ?></h1>
                <p><?= e($player['player_name']); ?> | Current score: <?= e((string) $player['score']); ?></p>
            </div>
            <div class="timer-box">
                <span data-countdown="<?= e((string) $currentQuestion['time_limit']); ?>">
                    <?= e((string) $currentQuestion['time_limit']); ?>
                </span>
                <small>seconds</small>
            </div>
        </div>

        <form class="question-panel" method="post" action="play.php?player_id=<?= e((string) $playerId); ?>&question=<?= e((string) $questionNumber); ?>" data-play-form>
            <input type="hidden" name="player_id" value="<?= e((string) $playerId); ?>">
            <input type="hidden" name="question_id" value="<?= e((string) $currentQuestion['id']); ?>">
            <input type="hidden" name="response_time" value="0">

            <h2><?= e($currentQuestion['question_text']); ?></h2>

            <div class="answer-grid">
                <label class="answer-card answer-a">
                    <input type="radio" name="selected_answer" value="A">
                    <span>A</span>
                    <?= e($currentQuestion['option_a']); ?>
                </label>
                <label class="answer-card answer-b">
                    <input type="radio" name="selected_answer" value="B">
                    <span>B</span>
                    <?= e($currentQuestion['option_b']); ?>
                </label>
                <label class="answer-card answer-c">
                    <input type="radio" name="selected_answer" value="C">
                    <span>C</span>
                    <?= e($currentQuestion['option_c']); ?>
                </label>
                <label class="answer-card answer-d">
                    <input type="radio" name="selected_answer" value="D">
                    <span>D</span>
                    <?= e($currentQuestion['option_d']); ?>
                </label>
            </div>

            <button class="btn btn-primary" type="submit">Submit Answer</button>
        </form>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
