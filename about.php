<?php

declare(strict_types=1);

$pageTitle = 'About';
require_once __DIR__ . '/includes/header.php';
?>

<section class="page-hero compact-hero">
    <div class="container">
        <p class="eyebrow">About the project</p>
        <h1>Learning feels better when everyone gets to play.</h1>
        <p>
            Mind Race helps educators, teachers, and presenters engage participants through interactive quizzes,
            live participation, instant feedback, competition, and a leaderboard.
        </p>
    </div>
</section>

<section class="section">
    <div class="container content-grid">
        <article class="content-block">
            <h2>Project Idea</h2>
            <p>
                Mind Race is a quiz game web application. A host creates a quiz, adds timed questions,
                starts a room using a unique game code, and players join with their names to answer questions.
            </p>
            <p>
                Player scores are based on correctness and response speed, making the learning process more active,
                social, and memorable.
            </p>
        </article>

        <article class="content-block">
            <h2>Benefits</h2>
            <ul class="check-list">
                <li>Fun learning that keeps attention high.</li>
                <li>Live participation for classrooms and presentations.</li>
                <li>Friendly competition through scores and rankings.</li>
                <li>Instant feedback after answers are submitted.</li>
                <li>Leaderboard results that make progress visible.</li>
                <li>Better knowledge retention through repeated active recall.</li>
            </ul>
        </article>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
