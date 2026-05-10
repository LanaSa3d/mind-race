<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Create Quiz';
$pdo = getDbConnection();
$errors = [];

// Save a new quiz when the form is submitted.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = postValue('title');
    $description = postValue('description');
    $hostName = postValue('host_name');

    if ($title === '') {
        $errors[] = 'Quiz title is required.';
    }

    if ($description === '') {
        $errors[] = 'Quiz description is required.';
    }

    if ($hostName === '') {
        $errors[] = 'Host name is required.';
    }

    if (!$errors) {
        $gameCode = generateUniqueGameCode($pdo);
        $statement = $pdo->prepare(
            'INSERT INTO quizzes (title, description, host_name, game_code)
             VALUES (:title, :description, :host_name, :game_code)'
        );
        $statement->execute([
            'title' => $title,
            'description' => $description,
            'host_name' => $hostName,
            'game_code' => $gameCode,
        ]);

        setFlash("Quiz created successfully. Game code: {$gameCode}");
        redirect('view_quizzes.php');
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<section class="page-hero compact-hero">
    <div class="container">
        <p class="eyebrow">Create quiz</p>
        <h1>Start a new Mind Race.</h1>
        <p>Add the quiz details first. After that, you can add questions from the Manage Questions page.</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <form class="form-card form-grid" method="post" action="create_quiz.php" data-validate>
            <?php if ($errors): ?>
                <div class="flash flash-error">
                    <?= e(implode(' ', $errors)); ?>
                </div>
            <?php endif; ?>

            <div class="field">
                <label for="title">Quiz Title</label>
                <input type="text" id="title" name="title" value="<?= e($_POST['title'] ?? ''); ?>" required>
            </div>

            <div class="field">
                <label for="description">Quiz Description</label>
                <textarea id="description" name="description" required><?= e($_POST['description'] ?? ''); ?></textarea>
            </div>

            <div class="field">
                <label for="host_name">Host Name</label>
                <input type="text" id="host_name" name="host_name" value="<?= e($_POST['host_name'] ?? ''); ?>" required>
            </div>

            <div class="actions">
                <button class="btn btn-primary" type="submit">Create Quiz</button>
                <a class="btn btn-ghost" href="view_quizzes.php">View Quizzes</a>
            </div>
        </form>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
