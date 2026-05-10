<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'View Quizzes';
$pdo = getDbConnection();

// Read all quizzes and count their questions.
$statement = $pdo->query(
    'SELECT quizzes.*, COUNT(questions.id) AS question_count
     FROM quizzes
     LEFT JOIN questions ON questions.quiz_id = quizzes.id
     GROUP BY quizzes.id
     ORDER BY quizzes.created_at DESC, quizzes.id DESC'
);
$quizzes = $statement->fetchAll();

require_once __DIR__ . '/includes/header.php';
?>

<section class="page-hero compact-hero">
    <div class="container">
        <p class="eyebrow">Quiz library</p>
        <h1>View and manage quizzes.</h1>
        <p>Edit quiz details, manage questions, delete quizzes, or share a game code with players.</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="panel-heading">
            <h2>All Quizzes</h2>
            <a class="btn btn-primary" href="create_quiz.php">Create Quiz</a>
        </div>

        <?php if (!$quizzes): ?>
            <div class="panel">
                <p>No quizzes found. Create your first quiz to begin.</p>
            </div>
        <?php endif; ?>

        <div class="quiz-grid">
            <?php foreach ($quizzes as $quiz): ?>
                <article class="quiz-card">
                    <div class="quiz-top">
                        <h2><?= e($quiz['title']); ?></h2>
                        <span class="code-pill"><?= e($quiz['game_code']); ?></span>
                    </div>
                    <p><?= e($quiz['description']); ?></p>
                    <ul class="meta-list">
                        <li><strong>Host:</strong> <?= e($quiz['host_name']); ?></li>
                        <li><strong>Questions:</strong> <?= e((string) $quiz['question_count']); ?></li>
                        <li><strong>Created:</strong> <?= e(date('M d, Y', strtotime($quiz['created_at']))); ?></li>
                    </ul>
                    <div class="actions">
                        <a class="btn btn-small btn-light" href="edit_quiz.php?id=<?= e((string) $quiz['id']); ?>">Edit quiz</a>
                        <a class="btn btn-small btn-secondary" href="manage_questions.php?quiz_id=<?= e((string) $quiz['id']); ?>">Manage questions</a>
                        <a class="btn btn-small btn-primary" href="join_game.php?game_code=<?= e($quiz['game_code']); ?>">Start game</a>
                        <form method="post" action="delete_quiz.php">
                            <input type="hidden" name="id" value="<?= e((string) $quiz['id']); ?>">
                            <button class="btn btn-small btn-danger" type="submit" data-confirm="Delete this quiz and its questions?">Delete quiz</button>
                        </form>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
