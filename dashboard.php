<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/db.php';

$pageTitle = 'Dashboard';
$pdo = getDbConnection();

$counts = [
    'Total Quizzes' => (int) $pdo->query('SELECT COUNT(*) FROM quizzes')->fetchColumn(),
    'Total Questions' => (int) $pdo->query('SELECT COUNT(*) FROM questions')->fetchColumn(),
    'Total Players' => (int) $pdo->query('SELECT COUNT(*) FROM players')->fetchColumn(),
    'Total Games' => (int) $pdo->query('SELECT COUNT(DISTINCT game_code) FROM quizzes')->fetchColumn(),
];

$recentQuizzes = $pdo
    ->query('SELECT title, host_name, game_code, created_at FROM quizzes ORDER BY created_at DESC, id DESC LIMIT 5')
    ->fetchAll();

require_once __DIR__ . '/includes/header.php';
?>

<section class="page-hero compact-hero">
    <div class="container">
        <p class="eyebrow">Admin dashboard</p>
        <h1>Mind Race control center</h1>
        <p>Track quiz content, player participation, and game activity from one clean dashboard.</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="stat-grid">
            <?php foreach ($counts as $label => $value): ?>
                <article class="stat-card">
                    <span><?= e($label); ?></span>
                    <strong><?= e((string) $value); ?></strong>
                </article>
            <?php endforeach; ?>
        </div>

        <div class="dashboard-grid">
            <section class="panel">
                <div class="panel-heading">
                    <h2>Recent Quizzes</h2>
                    <a class="text-link" href="view_quizzes.php">View all</a>
                </div>
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Quiz</th>
                                <th>Host</th>
                                <th>Game Code</th>
                                <th>Created</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recentQuizzes as $quiz): ?>
                                <tr>
                                    <td><?= e($quiz['title']); ?></td>
                                    <td><?= e($quiz['host_name']); ?></td>
                                    <td><span class="code-pill"><?= e($quiz['game_code']); ?></span></td>
                                    <td><?= e(date('M d, Y', strtotime($quiz['created_at']))); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </section>

            <aside class="panel action-panel">
                <h2>Quick Actions</h2>
                <a class="btn btn-primary btn-full" href="create_quiz.php">Create Quiz</a>
                <a class="btn btn-secondary btn-full" href="manage_questions.php">Manage Questions</a>
                <a class="btn btn-light btn-full" href="view_quizzes.php">Manage Quizzes</a>
                <a class="btn btn-ghost btn-full" href="leaderboard.php">View Leaderboard</a>
            </aside>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
