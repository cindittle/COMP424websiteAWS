CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(255) NOT NULL,
    last_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    birth_date DATE,                        -- Optional field for the user's birth date
    question1 VARCHAR(255),                -- Security question 1
    answer1 VARCHAR(255),
    question2 VARCHAR(255),                -- Security question 2
    answer2 VARCHAR(255),
    activation_token VARCHAR(255) NOT NULL, -- Token for email verification
    is_active TINYINT(1) DEFAULT 0,        -- Indicates if the email is verified
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP -- Timestamp for account creation
);


