CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(255) NOT NULL,
    last_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    question1 VARCHAR(255) NOT NULL,        -- Security question 1
    answer1 VARCHAR(255) NOT NULL,
    question2 VARCHAR(255) NOT NULL,        -- Security question 2
    answer2 VARCHAR(255) NOT NULL,
    activation_token VARCHAR(255) NOT NULL, -- Token for email verification
    is_active TINYINT(1) DEFAULT 0,         whe-- Indicates if the email is verified
    count INT DEFAULT 0,                    -- Tracks the number of times the user logged in
    last_login DATETIME,                    -- Tracks the last login date and time
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP -- Timestamp for account creation
);