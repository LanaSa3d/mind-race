<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Edit Question';
$pdo = getDbConnection();
$questionId = (int) ($_GET['id'] ?? 0);
$question = getQuestionById($pdo, $questionId);
$quizzes = $pdo->query('SELECT id, title FROM quizzes ORDER BY title ASC')->fetchAll();
$errors = [];

if (!$question) {
    setFlash('Question not found.', 'error');
    redirect('manage_questions.php');
}

// Update the selected question.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $quizId = (int) ($_POST['quiz_id'] ?? 0);
    $questionText = postValue('question_text');
    $optionA = postValue('option_a');
    $optionB = postValue('option_b');
    $optionC = postValue('option_c');
    $optionD = postValue('option_d');
    $correctAnswer = strtoupper(postValue('correct_answer'));
    $timeLimit = (int) ($_POST['time_limit'] ?? 0);
    $points = (int) ($_POST['points'] ?? 0);

    if ($quizId <= 0) {
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
            'UPDATE questions
             SET quiz_id = :quiz_id,
                 question_text = :question_text,
                 option_a = :option_a,
                 option_b = :option_b,
                 option_c = :option_c,
                 option_d = :option_d,
                 correct_answer = :correct_answer,
                 time_limit = :time_limit,
                 points = :points
             WHERE id = :id'
        );
        $statement->execute([
            'quiz_id' => $quizId,
            'question_text' => $questionText,
            'option_a' => $optionA,
            'option_b' => $optionB,
            'option_c' => $optionC,
            'option_d' => $optionD,
            'correct_answer' => $correctAnswer,
            'time_limit' => $timeLimit,
            'points' => $points,
            'id' => $questionId,
        ]);

        setFlash('Question updated successfully.');
        redirect('manage_questions.php?quiz_id=' . $quizId);
    }

    $question = array_merge($question, [
        'quiz_id' => $quizId,
        'question_text' => $questionText,
        'option_a' => $optionA,
        'option_b' => $optionB,
        'option_c' => $optionC,
        'option_d' => $optionD,
        'correct_answer' => $correctAnswer,
        'time_limit' => $timeLimit,
        'points' => $points,
    ]);
}

require_once __DIR__ . '/includes/header.php';
?>

<section class="page-hero compact-hero">
    <div class="container">
        <p class="eyebrow">Edit question</p>
        <h1>Update this question.</h1>
        <p>Change the quiz, text, options, answer, time limit, or points.</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <form class="form-card form-grid" method="post" action="edit_question.php?id=<?= e((string) $questionId); ?>" data-validate>
            <?php if ($errors): ?>
                <div class="flash flash-error">
                    <?= e(implode(' ', $errors)); ?>
                </div>
            <?php endif; ?>

            <div class="field">
                <label for="quiz_id">Select Quiz</label>
                <select id="quiz_id" name="quiz_id" required>
                    <?php foreach ($quizzes as $quiz): ?>
                        <option value="<?= e((string) $quiz['id']); ?>"<?= (int) $quiz['id'] === (int) $question['quiz_id'] ? ' selected' : ''; ?>>
                            <?= e($quiz['title']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="field">
                <label for="question_text">Question Text</label>
                <textarea id="question_text" name="question_text" required><?= e($question['question_text']); ?></textarea>
            </div>

            <div class="two-columns">
                <div class="field">
                    <label for="option_a">Option A</label>
                    <input type="text" id="option_a" name="option_a" value="<?= e($question['option_a']); ?>" required>
                </div>
                <div class="field">
                    <label for="option_b">Option B</label>
                    <input type="text" id="option_b" name="option_b" value="<?= e($question['option_b']); ?>" required>
                </div>
                <div class="field">
                    <label for="option_c">Option C</label>
                    <input type="text" id="option_c" name="option_c" value="<?= e($question['option_c']); ?>" required>
                </div>
                <div class="field">
                    <label for="option_d">Option D</label>
                    <input type="text" id="option_d" name="option_d" value="<?= e($question['option_d']); ?>" required>
                </div>
            </div>

            <div class="two-columns">
                <div class="field">
                    <label for="correct_answer">Correct Answer</label>
                    <select id="correct_answer" name="correct_answer" required>
                        <option value="A"<?= selected($question['correct_answer'], 'A'); ?>>A</option>
                        <option value="B"<?= selected($question['correct_answer'], 'B'); ?>>B</option>
                        <option value="C"<?= selected($question['correct_answer'], 'C'); ?>>C</option>
                        <option value="D"<?= selected($question['correct_answer'], 'D'); ?>>D</option>
                    </select>
                </div>
                <div class="field">
                    <label for="time_limit">Time Limit Seconds</label>
                    <input type="number" id="time_limit" name="time_limit" value="<?= e((string) $question['time_limit']); ?>" min="5" required>
                </div>
                <div class="field">
                    <label for="points">Points</label>
                    <input type="number" id="points" name="points" value="<?= e((string) $question['points']); ?>" min="1" required>
                </div>
            </div>

            <div class="actions">
                <button class="btn btn-primary" type="submit">Save Question</button>
                <a class="btn btn-ghost" href="manage_questions.php?quiz_id=<?= e((string) $question['quiz_id']); ?>">Cancel</a>
            </div>
        </form>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
