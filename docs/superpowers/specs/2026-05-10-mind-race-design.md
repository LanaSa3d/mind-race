# Mind Race Design

## Goal

Build a complete Advanced Web Programming course project called Mind Race: a Kahoot-like PHP and MySQL quiz game web application for creating quizzes, joining games with a game code, answering timed questions, and viewing scores on a leaderboard.

## Architecture

The application uses classic PHP pages running in XAMPP. Reusable layout and database logic lives in `includes/`, public pages live at the project root, static styling and browser behavior live in `assets/`, and database schema plus seed data lives in `database.sql`.

PDO prepared statements handle all database operations. Output is escaped through reusable helpers before rendering. Forms post back to PHP pages or companion action pages for create, update, and delete flows.

## Data Model

The required database is `mind_race_db`. It includes five tables:

- `users` for hosts/admin sample data.
- `quizzes` for quiz metadata and generated game codes.
- `questions` for quiz questions and answer choices.
- `players` for joined players and total score.
- `answers` for each submitted answer, correctness, response time, and earned points.

Each table includes at least five sample rows in `database.sql`.

## Pages

The project includes the required pages:

- `index.php` for the home page and primary navigation.
- `about.php` for project explanation and benefits.
- `dashboard.php` for admin summary counts.
- `create_quiz.php` for quiz creation.
- `view_quizzes.php` for quiz listing, delete links, edit links, question management, and game start/join flow.
- `edit_quiz.php` and `delete_quiz.php` for quiz update/delete.
- `manage_questions.php`, `edit_question.php`, and `delete_question.php` for full question CRUD.
- `join_game.php` for player joining through game code.
- `play.php` for timed question answering and score calculation.
- `leaderboard.php` for ranked player scores.

## User Experience

The UI should be colorful and game-like without copying any existing quiz platform. It uses a responsive layout, clear navigation, strong buttons, clean forms, quiz cards, readable data tables, and an exciting leaderboard presentation.

## Testing

Validation will include PHP syntax checks, database schema inspection, and a lightweight PHP CLI smoke test for important helper functions. Browser-level local XAMPP testing may require Apache and MySQL to be running on the user's machine.
