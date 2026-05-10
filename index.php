<?php

declare(strict_types=1);

$pageTitle = 'Home';
require_once __DIR__ . '/includes/header.php';
?>

<section class="hero">
    <div class="container hero-grid">
        <div class="hero-copy">
            <p class="eyebrow">Live quiz game platform</p>
            <h1>Mind Race</h1>
            <p class="hero-text">
                Create colorful classroom quizzes, invite players with a game code, race through timed questions,
                and celebrate learning with a live leaderboard.
            </p>
            <div class="hero-actions">
                <a class="btn btn-primary" href="create_quiz.php">Create Quiz</a>
                <a class="btn btn-secondary" href="join_game.php">Join Game</a>
                <a class="btn btn-light" href="view_quizzes.php">View Quizzes</a>
                <a class="btn btn-ghost" href="about.php">About</a>
            </div>
        </div>
        <div class="hero-art" aria-hidden="true">
            <img src="assets/images/mind-race-mark.svg" alt="">
        </div>
    </div>
</section>

<section class="section">
    <div class="container feature-grid">
        <article class="feature-card">
            <span class="feature-icon">1</span>
            <h2>Create</h2>
            <p>Hosts build quiz rounds with titles, descriptions, questions, answer options, points, and timers.</p>
        </article>
        <article class="feature-card">
            <span class="feature-icon">2</span>
            <h2>Join</h2>
            <p>Players enter a short Mind Race game code and immediately join the matching quiz room.</p>
        </article>
        <article class="feature-card">
            <span class="feature-icon">3</span>
            <h2>Compete</h2>
            <p>Correct answers earn points, and faster responses can win bonus points for a stronger score.</p>
        </article>
        <article class="feature-card">
            <span class="feature-icon">4</span>
            <h2>Rank</h2>
            <p>The leaderboard ranks players by score and correct answers so every round ends with momentum.</p>
        </article>
    </div>
</section>

<section class="section section-accent">
    <div class="container split-panel">
        <div>
            <p class="eyebrow">Built for XAMPP</p>
            <h2>Dynamic PHP and MySQL, not a static demo.</h2>
        </div>
        <p>
            Mind Race includes PDO database operations, full CRUD pages, sample MySQL data, JavaScript timers,
            and reusable PHP layout files that match the Advanced Web Programming project requirements.
        </p>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
