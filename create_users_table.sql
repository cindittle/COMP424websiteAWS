CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(255) NOT NULL,
    last_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    username VARCHAR(255) NOT NULL UNIQUE, -- Added username field for registration
    password VARCHAR(255) NOT NULL,
    birth_date DATE,                        -- Added birth_date field
    question1 VARCHAR(255),                -- Security question 1
    answer1 VARCHAR(255),
    question2 VARCHAR(255),                -- Security question 2
    answer2 VARCHAR(255),
    question3 VARCHAR(255),                -- Security question 3
    answer3 VARCHAR(255),
    activation_token VARCHAR(255) NOT NULL, -- Renamed for clarity (was activation_token)
    is_active TINYINT(1) DEFAULT 0,        -- Indicates if the email is verified
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP -- Added timestamp for creation
);

