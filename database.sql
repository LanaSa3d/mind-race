DROP DATABASE IF EXISTS mind_race_db;
CREATE DATABASE mind_race_db;
USE mind_race_db;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS answers;
DROP TABLE IF EXISTS players;
DROP TABLE IF EXISTS questions;
DROP TABLE IF EXISTS quizzes;
DROP TABLE IF EXISTS users;
SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    role VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE quizzes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    description TEXT NOT NULL,
    host_name VARCHAR(100) NOT NULL,
    game_code VARCHAR(20) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE questions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    quiz_id INT NOT NULL,
    question_text TEXT NOT NULL,
    option_a VARCHAR(255) NOT NULL,
    option_b VARCHAR(255) NOT NULL,
    option_c VARCHAR(255) NOT NULL,
    option_d VARCHAR(255) NOT NULL,
    correct_answer VARCHAR(10) NOT NULL,
    time_limit INT NOT NULL DEFAULT 30,
    points INT NOT NULL DEFAULT 1000,
    CONSTRAINT fk_questions_quizzes
        FOREIGN KEY (quiz_id) REFERENCES quizzes(id)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE players (
    id INT AUTO_INCREMENT PRIMARY KEY,
    player_name VARCHAR(100) NOT NULL,
    game_code VARCHAR(20) NOT NULL,
    score INT DEFAULT 0,
    joined_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE answers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    player_id INT NOT NULL,
    question_id INT NOT NULL,
    selected_answer VARCHAR(10) NOT NULL,
    is_correct BOOLEAN NOT NULL,
    response_time INT NOT NULL,
    earned_points INT NOT NULL DEFAULT 0,
    answered_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_answers_players
        FOREIGN KEY (player_id) REFERENCES players(id)
        ON DELETE CASCADE,
    CONSTRAINT fk_answers_questions
        FOREIGN KEY (question_id) REFERENCES questions(id)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO users (name, email, role) VALUES
('Lana Saad', 'lana@example.com', 'Admin'),
('Maha Ali', 'maha@example.com', 'Teacher'),
('Omar Hassan', 'omar@example.com', 'Presenter'),
('Sara Nasser', 'sara@example.com', 'Teacher'),
('Fahad Salem', 'fahad@example.com', 'Host');

INSERT INTO quizzes (title, description, host_name, game_code) VALUES
('Web Basics Sprint', 'HTML, CSS, and JavaScript fundamentals for beginners.', 'Lana Saad', 'MR1001'),
('PHP Power Round', 'A quick challenge covering PHP syntax and server-side logic.', 'Maha Ali', 'MR1002'),
('Database Dash', 'Practice MySQL tables, relationships, and queries.', 'Omar Hassan', 'MR1003'),
('Security Showdown', 'Questions about secure web programming habits.', 'Sara Nasser', 'MR1004'),
('JavaScript Lightning', 'Fast-paced browser scripting and DOM quiz.', 'Fahad Salem', 'MR1005');

INSERT INTO questions (
    quiz_id,
    question_text,
    option_a,
    option_b,
    option_c,
    option_d,
    correct_answer,
    time_limit,
    points
) VALUES
(1, 'Which HTML5 element is used for page navigation links?', 'section', 'nav', 'article', 'aside', 'B', 30, 1000),
(1, 'Which CSS property changes the text color?', 'font-style', 'background', 'color', 'display', 'C', 25, 900),
(2, 'Which PHP superglobal stores form values submitted with POST?', '$_GET', '$_POST', '$_SESSION', '$_FILES', 'B', 30, 1000),
(2, 'Which PHP function is commonly used to escape HTML output?', 'htmlspecialchars', 'trim', 'explode', 'header', 'A', 30, 1000),
(3, 'Which SQL statement retrieves records from a table?', 'INSERT', 'UPDATE', 'SELECT', 'DELETE', 'C', 20, 800),
(3, 'Which key uniquely identifies a row in a table?', 'Primary key', 'Foreign key', 'Index key', 'Sort key', 'A', 25, 900),
(4, 'What helps prevent SQL injection in PHP?', 'Prepared statements', 'Inline styles', 'Image compression', 'Plain text passwords', 'A', 30, 1200),
(4, 'Passwords should usually be stored as:', 'Plain text', 'Encrypted URLs', 'Secure hashes', 'Browser cookies only', 'C', 30, 1200),
(5, 'Which method selects an element by ID in JavaScript?', 'queryById', 'getElementById', 'findId', 'selectId', 'B', 20, 800),
(5, 'Which event runs after a form is submitted?', 'submit', 'hover', 'resize', 'scroll', 'A', 20, 800);

INSERT INTO players (player_name, game_code, score) VALUES
('Noura', 'MR1001', 1833),
('Khalid', 'MR1001', 900),
('Reema', 'MR1002', 2000),
('Yousef', 'MR1003', 800),
('Aisha', 'MR1004', 1200);

INSERT INTO answers (
    player_id,
    question_id,
    selected_answer,
    is_correct,
    response_time,
    earned_points
) VALUES
(1, 1, 'B', TRUE, 5, 1833),
(2, 1, 'B', TRUE, 30, 1000),
(3, 3, 'B', TRUE, 0, 2000),
(4, 5, 'C', TRUE, 20, 800),
(5, 7, 'A', TRUE, 30, 1200);
