<?php

declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once __DIR__ . '/functions.php';

$pageTitle = $pageTitle ?? 'Mind Race';
$currentPage = basename((string) ($_SERVER['PHP_SELF'] ?? 'index.php'));
$flash = getFlash();

$navItems = [
    'index.php' => 'Home',
    'create_quiz.php' => 'Create Quiz',
    'view_quizzes.php' => 'View Quizzes',
    'join_game.php' => 'Join Game',
    'leaderboard.php' => 'Leaderboard',
    'dashboard.php' => 'Dashboard',
    'about.php' => 'About',
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle); ?> | Mind Race</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header class="site-header">
        <nav class="navbar container" aria-label="Main navigation">
            <a class="brand" href="index.php" aria-label="Mind Race home">
                <span class="brand-mark">MR</span>
                <span class="brand-text">Mind Race</span>
            </a>

            <button class="nav-toggle" type="button" data-nav-toggle aria-expanded="false" aria-controls="main-nav">
                <span></span>
                <span></span>
                <span></span>
                <span class="sr-only">Toggle navigation</span>
            </button>

            <div class="nav-links" id="main-nav" data-nav-menu>
                <?php foreach ($navItems as $href => $label): ?>
                    <a class="<?= $currentPage === $href ? 'active' : ''; ?>" href="<?= e($href); ?>">
                        <?= e($label); ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </nav>
    </header>

    <main>
        <?php if ($flash): ?>
            <div class="container">
                <div class="flash flash-<?= e($flash['type']); ?>">
                    <?= e($flash['message']); ?>
                </div>
            </div>
        <?php endif; ?>
