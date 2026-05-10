<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Manage Questions';
$pdo = getDbConnection();
$errors = [];
$selectedQuizId = (int) ($_GET['quiz_id'] ?? ($_POST['quiz_id'] ?? 0));

$quizzes = $pdo->query('SELECT id, title FROM quizzes ORDER BY title ASC')->fetchAll();

// Add a new question to the selected quiz.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $questionText = postValue('question_text');
    $optionA = postValue('option_a');
    $optionB = postValue('option_b');
    $optionC = postValue('option_c');
    $optionD = postValue('option_d');
    $correctAnswer = strtoupper(postValue('correct_answer'));
    $timeLimit = (int) ($_POST['time_limit'] ?? 0);
    $points = (int) ($_POST['points'] ?? 0);

    if ($selectedQuizId <= 0) {
        $errors[] = 'Please select a quiz.';
    }

    if ($questionText === '' || $optionA === '' || $optionB === '' || $optionC === '' || $optionD === '') {
        $errors[] = 'Question text and all options are required.';
    }

    if (!isValidAnswerOption($correctAnswer)) {
        $errors[] = 'Correct answer must be A, B, C, or D.';
    }

    if ($timeLimit <= 0 || $points <= 0) {
        $errors[] = 'Time limit and points must be greater than zero.';
    }

    if (!$errors) {
        $statement = $pdo->prepare(
            'INSERT INTO questions
             (quiz_id, question_text, option_a, option_b, option_c, option_d, correct_answer, time_limit, points)
             VALUES
             (:quiz_id, :question_text, :option_a, :option_b, :option_c, :option_d, :correct_answer, :time_limit, :points)'
        );
        $statement->execute([
            'quiz_id' => $selectedQuizId,
            'question_text' => $questionText,
            'option_a' => $optionA,
            'option_b' => $optionB,
            'option_c' => $optionC,
            'option_d' => $optionD,
            'correct_answer' => $correctAnswer,
            'time_limit' => $timeLimit,
            'points' => $points,
        ]);

        setFlash('Question added successfully.');
        redirect('manage_questions.php?quiz_id=' . $selectedQuizId);
    }
}

// Read questions. If a quiz is selected, only show that quiz's questions.
if ($selectedQuizId > 0) {
    $statement = $pdo->prepare(
        'SELECT questions.*, quizzes.title AS quiz_title
         FROM questions
         INNER JOIN quizzes ON quizzes.id = questions.quiz_id
         WHERE questions.quiz_id = :quiz_id
         ORDER BY questions.id DESC'
    );
    $statement->execute(['quiz_id' => $selectedQuizId]);
} else {
    $statement = $pdo->query(
        'SELECT questions.*, quizzes.title AS quiz_title
         FROM questions
         INNER JOIN quizzes ON quizzes.id = questions.quiz_id
         ORDER BY questions.id DESC'
    );
}

$questions = $statement->fetchAll();

require_once __DIR__ . '/includes/header.php';
?>

<section class="page-hero compact-hero">
    <div class="container">
        <p class="eyebrow">Manage questions</p>
        <h1>Add and organize quiz questions.</h1>
        <p>This page demonstrates Create, Read, Update, and Delete operations for questions.</p>
    </div>
</section>

<section class="section">
    <div class="container dashboard-grid">
        <form class="form-card form-grid" method="post" action="manage_questions.php" data-validate>
            <h2>Add Question</h2>

            <?php if ($errors): ?>
                <div class="flash flash-error">
                    <?= e(implode(' ', $errors)); ?>
                </div>
            <?php endif; ?>

            <div class="field">
                <label for="quiz_id">Select Quiz</label>
                <select id="quiz_id" name="quiz_id" required>
                    <option value="">Choose a quiz</option>
                    <?php foreach ($quizzes as $quiz): ?>
                        <option value="<?= e((string) $quiz['id']); ?>"<?= (int) $quiz['id'] === $selectedQuizId ? ' selected' : ''; ?>>
                            <?= e($quiz['title']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="field">
                <label for="question_text">Question Text</label>
                <textarea id="question_text" name="question_text" required><?= e($_POST['question_text'] ?? ''); ?></textarea>
            </div>

            <div class="two-columns">
                <div class="field">
                    <label for="option_a">Option A</label>
                    <input type="text" id="option_a" name="option_a" value="<?= e($_POST['option_a'] ?? ''); ?>" required>
                </div>
                <div class="field">
                    <label for="option_b">Option B</label>
                    <input type="text" id="option_b" name="option_b" value="<?= e($_POST['option_b'] ?? ''); ?>" required>
                </div>
                <div class="field">
                    <label for="option_c">Option C</label>
                    <input type="text" id="option_c" name="option_c" value="<?= e($_POST['option_c'] ?? ''); ?>" required>
                </div>
                <div class="field">
                    <label for="option_d">Option D</label>
                    <input type="text" id="option_d" name="option_d" value="<?= e($_POST['option_d'] ?? ''); ?>" required>
                </div>
            </div>

            <div class="two-columns">
                <div class="field">
                    <label for="correct_answer">Correct Answer</label>
                    <select id="correct_answer" name="correct_answer" required>
                        <option value="A">A</option>
                        <option value="B">B</option>
                        <option value="C">C</option>
                        <option value="D">D</option>
                    </select>
                </div>
                <div class="field">
                    <label for="time_limit">Time Limit Seconds</label>
                    <input type="number" id="time_limit" name="time_limit" value="<?= e($_POST['time_limit'] ?? '30'); ?>" min="5" required>
                </div>
                <div class="field">
                    <label for="points">Points</label>
                    <input type="number" id="points" name="points" value="<?= e($_POST['points'] ?? '1000'); ?>" min="1" required>
                </div>
            </div>

            <button class="btn btn-primary" type="submit">Add Question</button>
        </form>

        <aside class="panel">
            <h2>Questions</h2>
            <p>Select a quiz in the form to add a question. Use the buttons below to edit or delete questions.</p>
            <a class="btn btn-light btn-full" href="manage_questions.php">Show All Questions</a>
            <a class="btn btn-ghost btn-full" href="view_quizzes.php">Back To Quizzes</a>
        </aside>
    </div>
</section>

<section class="section section-tight">
    <div class="container">
        <div class="panel">
            <div class="panel-heading">
                <h2>Existing Questions</h2>
                <span class="code-pill"><?= e((string) count($questions)); ?> total</span>
            </div>

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Quiz</th>
                            <th>Question</th>
                            <th>Correct</th>
                            <th>Time</th>
                            <th>Points</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($questions as $question): ?>
                            <tr>
                                <td><?= e($question['quiz_title']); ?></td>
                                <td><?= e($question['question_text']); ?></td>
                                <td><?= e($question['correct_answer']); ?></td>
                                <td><?= e((string) $question['time_limit']); ?>s</td>
                                <td><?= e((string) $question['points']); ?></td>
                                <td>
                                    <div class="actions">
                                        <a class="btn btn-small btn-light" href="edit_question.php?id=<?= e((string) $question['id']); ?>">Edit</a>
                                        <form method="post" action="delete_question.php">
                                            <input type="hidden" name="id" value="<?= e((string) $question['id']); ?>">
                                            <input type="hidden" name="quiz_id" value="<?= e((string) $question['quiz_id']); ?>">
                                            <button class="btn btn-small btn-danger" type="submit" data-confirm="Delete this question?">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (!$questions): ?>
                            <tr>
                                <td colspan="6">No questions found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
