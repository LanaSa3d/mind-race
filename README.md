# Mind Race: Quiz Game Web App

Mind Race is a Kahoot-like quiz game website for an Advanced Web Programming course project. Hosts can create quizzes, add questions, share a game code, and let players join the quiz, answer timed questions, earn scores, and view a leaderboard.

## Technologies Used

- HTML5
- CSS
- JavaScript
- PHP
- MySQL
- XAMPP
- PDO for secure database queries

## Features

- Home page with project introduction
- About page explaining the project idea
- Admin dashboard with total quizzes, questions, players, and games
- Create, view, edit, and delete quizzes
- Add, view, edit, and delete quiz questions
- Automatic game code generation, such as `MR1234`
- Player join form using player name and game code
- Timed play page with JavaScript countdown
- Score calculation based on correct answer and response speed
- Leaderboard sorted by highest score
- MySQL database with 5 tables and sample data

## Folder Structure

```text
mind-race/
├── index.php
├── about.php
├── dashboard.php
├── create_quiz.php
├── view_quizzes.php
├── edit_quiz.php
├── delete_quiz.php
├── manage_questions.php
├── edit_question.php
├── delete_question.php
├── join_game.php
├── play.php
├── leaderboard.php
├── includes/
│   ├── db.php
│   ├── header.php
│   ├── footer.php
│   └── functions.php
├── assets/
│   ├── css/
│   │   └── style.css
│   ├── js/
│   │   └── script.js
│   └── images/
├── tests/
│   ├── functions_test.php
│   └── structure_test.php
├── database.sql
├── CODE_EXPLANATION.md
├── README.md
└── .gitignore
```

## Database Setup

The database file is:

```text
database.sql
```

It creates the database:

```text
mind_race_db
```

It also creates these tables:

- `users`
- `quizzes`
- `questions`
- `players`
- `answers`

Each table includes at least 5 sample rows.

## How To Run Using XAMPP

1. Start Apache and MySQL from XAMPP.
2. Copy the `mind-race` folder into your XAMPP `htdocs` folder.
3. Open phpMyAdmin:

```text
http://localhost/phpmyadmin/
```

4. Click Import.
5. Choose the `database.sql` file.
6. Click Go to import the database and sample data.
7. Open the website in the browser:

```text
http://localhost/mind-race/
```

## CRUD Operations

Create:

- `create_quiz.php` creates new quizzes.
- `manage_questions.php` adds new questions.
- `join_game.php` adds new players.

Read:

- `view_quizzes.php` displays quizzes.
- `manage_questions.php` displays questions.
- `leaderboard.php` displays player rankings.
- `dashboard.php` displays summary counts.

Update:

- `edit_quiz.php` updates quiz information.
- `edit_question.php` updates question information.

Delete:

- `delete_quiz.php` deletes quizzes.
- `delete_question.php` deletes questions.

## Simple Project Explanation

Mind Race is a dynamic PHP and MySQL website. The host creates a quiz and questions. The system creates a game code. Players join using the game code, answer questions, and receive points. The leaderboard ranks players by score.

For a beginner-friendly explanation of the code files, read:

```text
CODE_EXPLANATION.md
```

## Developed By

Developed by : Lana Saad, Lana Maher, Lama Alkhawaja
