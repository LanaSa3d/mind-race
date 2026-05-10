<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$pdo = getDbConnection();
$questionId = (int) ($_POST['id'] ?? 0);
$quizId = (int) ($_POST['quiz_id'] ?? 0);

// Delete the selected question.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $questionId > 0) {
    $statement = $pdo->prepare('DELETE FROM questions WHERE id = :id');
    $statement->execute(['id' => $questionId]);
    setFlash('Question deleted successfully.');
} else {
    setFlash('Question could not be deleted.', 'error');
}

$redirect = $quizId > 0 ? 'manage_questions.php?quiz_id=' . $quizId : 'manage_questions.php';
redirect($redirect);
