<?php

declare(strict_types=1);

$requiredFiles = [
    'create_quiz.php',
    'view_quizzes.php',
    'edit_quiz.php',
    'delete_quiz.php',
    'manage_questions.php',
    'edit_question.php',
    'delete_question.php',
    'join_game.php',
    'play.php',
    'leaderboard.php',
    'database.sql',
    'CODE_EXPLANATION.md',
];

foreach ($requiredFiles as $file) {
    if (!file_exists(__DIR__ . '/../' . $file)) {
        fwrite(STDERR, "FAIL: Missing required file {$file}" . PHP_EOL);
        exit(1);
    }
}

$databaseSql = file_get_contents(__DIR__ . '/../database.sql');
$requiredTables = ['users', 'quizzes', 'questions', 'players', 'answers'];

foreach ($requiredTables as $table) {
    if (strpos($databaseSql, "CREATE TABLE {$table}") === false) {
        fwrite(STDERR, "FAIL: Missing table {$table} in database.sql" . PHP_EOL);
        exit(1);
    }
}

echo 'All structure checks passed.' . PHP_EOL;
