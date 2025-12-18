CREATE DATABASE QodexFinal CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE QodexFinal;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('enseignant', 'etudiant') NOT NULL
);

CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    id_enseignant INT NOT NULL,
    FOREIGN KEY (id_enseignant) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE quizzes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    id_category INT NOT NULL,
    FOREIGN KEY (id_category) REFERENCES categories(id) ON DELETE CASCADE
);

CREATE TABLE questions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_quiz INT NOT NULL,
    question_text TEXT NOT NULL,
    correct_option VARCHAR(255) NOT NULL,
    option1 VARCHAR(255) NOT NULL,
    option2 VARCHAR(255) NOT NULL,
    option3 VARCHAR(255) NOT NULL,
    FOREIGN KEY (id_quiz) REFERENCES quizzes(id) ON DELETE CASCADE
);

CREATE TABLE results (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_etudiant INT NOT NULL,
    id_quiz INT NOT NULL,
    score INT NOT NULL,
    date_passage DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_etudiant) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (id_quiz) REFERENCES quizzes(id) ON DELETE CASCADE
);