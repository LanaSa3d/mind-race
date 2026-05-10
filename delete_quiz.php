<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$pdo = getDbConnection();
$quizId = (int) ($_POST['id'] ?? 0);

// Delete the selected quiz. Questions are deleted automatically by the database.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $quizId > 0) {
    $statement = $pdo->prepare('DELETE FROM quizzes WHERE id = :id');
    $statement->execute(['id' => $quizId]);
    setFlash('Quiz deleted successfully.');
} else {
    setFlash('Quiz could not be deleted.', 'error');
}

redirect('view_quizzes.php');
