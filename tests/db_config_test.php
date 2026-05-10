<?php

declare(strict_types=1);

$dbFile = file_get_contents(__DIR__ . '/../includes/db.php');

$requiredLines = [
    '$host = \'localhost\';',
    '$database = \'mind_race_db\';',
    '$username = \'root\';',
    '$password = \'\';',
];

foreach ($requiredLines as $line) {
    if (strpos($dbFile, $line) === false) {
        fwrite(STDERR, "FAIL: Missing database config line: {$line}" . PHP_EOL);
        exit(1);
    }
}

echo 'Database config test passed.' . PHP_EOL;
