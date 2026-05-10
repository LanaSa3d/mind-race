<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Edit Quiz';
$pdo = getDbConnection();
$quizId = (int) ($_GET['id'] ?? 0);
$quiz = getQuizById($pdo, $quizId);
$errors = [];

if (!$quiz) {
    setFlash('Quiz not found.', 'error');
    redirect('view_quizzes.php');
}

// Update quiz details when the form is submitted.
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
        $statement = $pdo->prepare(
            'UPDATE quizzes
             SET title = :title, description = :description, host_name = :host_name
             WHERE id = :id'
        );
        $statement->execute([
            'title' => $title,
            'description' => $description,
            'host_name' => $hostName,
            'id' => $quizId,
        ]);

        setFlash('Quiz updated successfully.');
        redirect('view_quizzes.php');
    }

    $quiz['title'] = $title;
    $quiz['description'] = $description;
    $quiz['host_name'] = $hostName;
}

require_once __DIR__ . '/includes/header.php';
?>

<section class="page-hero compact-hero">
    <div class="container">
        <p class="eyebrow">Edit quiz</p>
        <h1><?= e($quiz['title']); ?></h1>
        <p>Update the quiz title, description, and host name.</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <form class="form-card form-grid" method="post" action="edit_quiz.php?id=<?= e((string) $quizId); ?>" data-validate>
            <?php if ($errors): ?>
                <div class="flash flash-error">
                    <?= e(implode(' ', $errors)); ?>
                </div>
            <?php endif; ?>

            <div class="field">
                <label for="title">Quiz Title</label>
                <input type="text" id="title" name="title" value="<?= e($quiz['title']); ?>" required>
            </div>

            <div class="field">
                <label for="description">Quiz Description</label>
                <textarea id="description" name="description" required><?= e($quiz['description']); ?></textarea>
            </div>

            <div class="field">
                <label for="host_name">Host Name</label>
                <input type="text" id="host_name" name="host_name" value="<?= e($quiz['host_name']); ?>" required>
            </div>

            <div class="actions">
                <button class="btn btn-primary" type="submit">Save Changes</button>
                <a class="btn btn-ghost" href="view_quizzes.php">Cancel</a>
            </div>
        </form>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
