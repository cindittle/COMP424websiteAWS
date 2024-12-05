# User Authentication and Registration System

This project is a user authentication and registration system implemented using PHP, HTML, and CSS. It includes features for user registration, login, password reset, and logout. The project is designed with security mechanisms such as CSRF protection and proactive password feedback.

## Features

- **User Registration**
  - Includes input fields for first name, last name, birth date, email, and password.
  - Security questions for account recovery.
  - Proactive password strength feedback.

- **User Login**
  - Secure login using session-based authentication.
  - CSRF token validation.

- **Forgot Password**
  - Allows users to reset their passwords after answering security questions.

- **Logout**
  - Securely ends the user session.

## File Structure

- `index.html`: Provides the frontend interface for user registration and login, with basic styling and form validation.
- `reg.php`: Handles user registration, including saving user details and security questions to the database.
- `login.php`: Authenticates user credentials and starts a session upon successful login.
- `logout.php`: Destroys the user session and logs the user out.
- `forgotPassword.php`: Implements the password recovery mechanism using security questions.
- `README.md`: Documentation for the project.

## Technologies Used

- **Backend**: PHP
- **Frontend**: HTML, CSS
- **Session Management**: PHP Sessions
- **Security Features**:
  - CSRF Protection
  - Password Strength Validation
  - Security Questions for Password Recovery