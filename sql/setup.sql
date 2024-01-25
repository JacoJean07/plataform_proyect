DROP DATABASE IF EXISTS plataform_db;

CREATE DATABASE plataform_db;

USE plataform_db;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_email VARCHAR(255) UNIQUE,
    user_password VARCHAR(255),
    user_role INT
);

CREATE TABLE data (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    data_name VARCHAR(255),
    data_lastname VARCHAR(255),
    data_birthdate DATE,
    data_dni CHARACTER(15),
    data_phone CHARACTER(15),
    data_address VARCHAR(255),
    data_career INT,
    data_rm BOOLEAN,
    Foreign Key (user_id) REFERENCES users(id)
);

CREATE TABLE cv (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    cv_twitter VARCHAR(255),
    cv_facebook VARCHAR(255),
    cv_youtube VARCHAR(255),
    cv_github VARCHAR(255),
    cv_linkedin VARCHAR(255),
    cv_instagram VARCHAR(255),
    cv_presentation VARCHAR(255),
    cv_about VARCHAR(255),
    cv_profesionAbout VARCHAR(255),
    -- cv_skills VARCHAR(255),
    cv_portfolio VARCHAR(255),
    cv_status BOOLEAN,
    cv_rm BOOLEAN,
    Foreign Key (user_id) REFERENCES users(id)
);

CREATE TABLE skills (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    skill_name VARCHAR(255),
    Foreign Key (user_id) REFERENCES users(id)
);

CREATE TABLE proyects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    proyect_name VARCHAR(255),
    proyect_category VARCHAR(255),
    proyect_description VARCHAR(255),
    proyect_date DATE
    -- proyect_photo 
);
