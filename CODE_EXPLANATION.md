# Code Explanation

This file explains the main parts of the Mind Race project in simple words.

## 1. Database Connection

The database connection is inside:

- `includes/db.php`

This file connects PHP to the MySQL database named `mind_race_db` using PDO.

## 2. Header And Footer

The repeated page layout is inside:

- `includes/header.php`
- `includes/footer.php`

These files keep the navigation bar and footer in one place so the pages do not repeat the same HTML many times.

## 3. Main Pages

- `index.php`: homepage
- `about.php`: project idea explanation
- `dashboard.php`: admin summary
- `create_quiz.php`: creates a new quiz
- `view_quizzes.php`: displays all quizzes
- `edit_quiz.php`: updates quiz information
- `delete_quiz.php`: deletes a quiz
- `manage_questions.php`: adds, displays, edits, and deletes questions
- `edit_question.php`: updates question information
- `delete_question.php`: deletes a question
- `join_game.php`: lets players join using a game code
- `play.php`: lets players answer quiz questions
- `leaderboard.php`: displays player ranking

## 4. Main Functions

The main helper functions are inside:

- `includes/functions.php`

Important functions include:

- `generateGameCode()`: creates a game code like `MR1234`
- `generateUniqueGameCode()`: creates a game code that is not already used
- `calculateEarnedPoints()`: calculates the score for an answer
- `getQuizById()`: gets quiz data by quiz id
- `getQuizByGameCode()`: gets quiz data by game code
- `getQuestionsByQuiz()`: gets all questions for one quiz
- `updatePlayerScore()`: updates the total score for a player

## 5. CRUD Explanation

CRUD means Create, Read, Update, and Delete.

- Create: `create_quiz.php`, `manage_questions.php`, `join_game.php`
- Read: `view_quizzes.php`, `manage_questions.php`, `leaderboard.php`, `dashboard.php`
- Update: `edit_quiz.php`, `edit_question.php`
- Delete: `delete_quiz.php`, `delete_question.php`

## 6. Database File

The database structure and sample data are inside:

- `database.sql`

This file creates the database, creates the tables, and adds sample rows.

## 7. JavaScript

The simple timer and confirmation messages are inside:

- `assets/js/script.js`

JavaScript is used for the countdown timer, delete confirmations, mobile menu, and simple form checks.

## 8. CSS

The website design is inside:

- `assets/css/style.css`

This file controls colors, layout, buttons, forms, tables, cards, and mobile responsiveness.

## How To Present This Project

Mind Race is a quiz game website similar to Kahoot. The host creates quizzes and questions. Players join using a game code. Players answer questions, and the system calculates scores based on correct answers and response speed.

The leaderboard ranks players based on their scores. The project uses PHP and MySQL to store and manage data. CRUD operations are included for quizzes and questions.
