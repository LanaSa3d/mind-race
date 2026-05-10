<?php

declare(strict_types=1);

/**
 * Escape output before rendering it into HTML.
 */
function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

/**
 * Redirect to another page and stop the current request.
 */
function redirect(string $path): never
{
    header("Location: {$path}");
    exit;
}

/**
 * Store a one-time flash message in the session.
 */
function setFlash(string $message, string $type = 'success'): void
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    $_SESSION['flash'] = [
        'message' => $message,
        'type' => $type,
    ];
}

/**
 * Return and clear the current flash message.
 */
function getFlash(): ?array
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    if (!isset($_SESSION['flash'])) {
        return null;
    }

    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);

    return $flash;
}

/**
 * Generate a classroom-friendly game code such as MR1234.
 */
function generateGameCode(): string
{
    return 'MR' . random_int(1000, 9999);
}

/**
 * Generate a game code that is not already used by a quiz.
 */
function generateUniqueGameCode(PDO $pdo): string
{
    $statement = $pdo->prepare('SELECT COUNT(*) FROM quizzes WHERE game_code = :game_code');

    do {
        $code = generateGameCode();
        $statement->execute(['game_code' => $code]);
    } while ((int) $statement->fetchColumn() > 0);

    return $code;
}

/**
 * Calculate points using correctness plus a speed bonus.
 */
function calculateEarnedPoints(
    string $selectedAnswer,
    string $correctAnswer,
    int $timeLimit,
    int $responseTime,
    int $basePoints
): int {
    if (strtoupper($selectedAnswer) !== strtoupper($correctAnswer)) {
        return 0;
    }

    if ($timeLimit <= 0) {
        return max(0, $basePoints);
    }

    $safeResponseTime = max(0, min($responseTime, $timeLimit));
    $remainingRatio = ($timeLimit - $safeResponseTime) / $timeLimit;
    $speedBonus = (int) round($basePoints * $remainingRatio);

    return max(0, $basePoints + $speedBonus);
}

/**
 * Read and trim a POST value safely.
 */
function postValue(string $key, string $default = ''): string
{
    return trim((string) ($_POST[$key] ?? $default));
}

/**
 * Return true when a submitted option is one of the allowed answer labels.
 */
function isValidAnswerOption(string $answer): bool
{
    return in_array(strtoupper($answer), ['A', 'B', 'C', 'D'], true);
}

/**
 * Render selected="selected" for matching select options.
 */
function selected(string $actual, string $expected): string
{
    return strtoupper($actual) === strtoupper($expected) ? ' selected' : '';
}

/**
 * Render a plain text game code for URLs.
 */
function normalizeGameCode(string $gameCode): string
{
    return strtoupper(trim($gameCode));
}

/**
 * Get one quiz by its id.
 */
function getQuizById(PDO $pdo, int $quizId): ?array
{
    $statement = $pdo->prepare('SELECT * FROM quizzes WHERE id = :id');
    $statement->execute(['id' => $quizId]);
    $quiz = $statement->fetch();

    return $quiz ?: null;
}

/**
 * Get one quiz by its game code.
 */
function getQuizByGameCode(PDO $pdo, string $gameCode): ?array
{
    $statement = $pdo->prepare('SELECT * FROM quizzes WHERE game_code = :game_code');
    $statement->execute(['game_code' => normalizeGameCode($gameCode)]);
    $quiz = $statement->fetch();

    return $quiz ?: null;
}

/**
 * Get all questions for one quiz.
 */
function getQuestionsByQuiz(PDO $pdo, int $quizId): array
{
    $statement = $pdo->prepare('SELECT * FROM questions WHERE quiz_id = :quiz_id ORDER BY id ASC');
    $statement->execute(['quiz_id' => $quizId]);

    return $statement->fetchAll();
}

/**
 * Recalculate and save a player's total score.
 */
function updatePlayerScore(PDO $pdo, int $playerId): void
{
    $statement = $pdo->prepare('SELECT COALESCE(SUM(earned_points), 0) FROM answers WHERE player_id = :player_id');
    $statement->execute(['player_id' => $playerId]);
    $score = (int) $statement->fetchColumn();

    $update = $pdo->prepare('UPDATE players SET score = :score WHERE id = :player_id');
    $update->execute([
        'score' => $score,
        'player_id' => $playerId,
    ]);
}

/**
 * Get one question by its id.
 */
function getQuestionById(PDO $pdo, int $questionId): ?array
{
    $statement = $pdo->prepare(
        'SELECT questions.*, quizzes.title AS quiz_title
         FROM questions
         INNER JOIN quizzes ON quizzes.id = questions.quiz_id
         WHERE questions.id = :id'
    );
    $statement->execute(['id' => $questionId]);
    $question = $statement->fetch();

    return $question ?: null;
}
