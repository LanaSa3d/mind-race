<?php

declare(strict_types=1);

require __DIR__ . '/../includes/functions.php';

function assertTrue(bool $condition, string $message): void
{
    if (!$condition) {
        fwrite(STDERR, "FAIL: {$message}" . PHP_EOL);
        exit(1);
    }
}

$code = generateGameCode();
assertTrue((bool) preg_match('/^MR[0-9]{4}$/', $code), 'Game code should match MR0000 format.');

assertTrue(e('<script>') === '&lt;script&gt;', 'HTML escaping should encode angle brackets.');

assertTrue(calculateEarnedPoints('A', 'A', 30, 5, 1000) === 1833, 'Fast correct answer should include time bonus.');
assertTrue(calculateEarnedPoints('B', 'A', 30, 5, 1000) === 0, 'Wrong answer should receive zero points.');
assertTrue(calculateEarnedPoints('A', 'A', 30, 30, 1000) === 1000, 'Answer at time limit should receive base points.');

echo 'All helper tests passed.' . PHP_EOL;
