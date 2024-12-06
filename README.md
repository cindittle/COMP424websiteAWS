# COMP424 Website Project

## Project Overview
This project is a secure user management web application hosted on AWS. It includes functionalities for user registration, email verification, login tracking, and password recovery. The application is built using PHP, HTML, CSS, and JavaScript, with a MySQL database on AWS RDS.

## Features
- **User Registration**:
  - Secure user registration with name, email, username, password, and security questions.
  - Sends a verification email upon successful registration.
- **Email Verification**:
  - Activates accounts via unique email verification links.
  - Option to resend the verification email.
- **Login System**:
  - Tracks login attempts, login count, and last login time.
  - Redirects users to a personalized welcome page after successful login.
- **Password Recovery**:
  - Allows users to recover their accounts using security questions.
- **Security Features**:
  - CSRF protection for form submissions.
  - Passwords are securely hashed before storage.
  - Security questions for account recovery.

## File Structure

### **Frontend**
- `index.html`:
  - Login page for user authentication.
- `register.html`:
  - User registration form.
- `resendEmail.html`:
  - Page to resend email verification links.
- `forgot.html`:
  - Password recovery form.
- `welcome.html`:
  - Personalized welcome page for logged-in users.

### **Backend**
- `reg.php`:
  - Handles user registration and sends verification emails.
- `login.php`:
  - Authenticates user credentials and tracks login details.
- `verifyEmail.php`:
  - Activates user accounts via email verification.
- `resendEmail.php`:
  - Sends a new email verification link.
- `forgotPassword.php`:
  - Processes account recovery requests.
- `confirmRegistration.php`:
  - Confirms that a verification email has been sent.

### **Utility Files**
- `config.php`:
  - Contains database connection details.
- `create_users_table.sql`:
  - SQL script to set up the `users` table in the database.

### **CSS & JavaScript**
- `forgot.css`, `welcome.css`:
  - Styling for `forgot.html` and `welcome.html`.
- `welcome.js`:
  - Client-side logic for the welcome page.

### **Assets**
- `Confidential.txt`:
  - A downloadable file accessible only to logged-in users.

## Database Schema
The database is defined in `create_users_table.sql` and includes:
- `id`: Primary key.
- `first_name`, `last_name`: User's name.
- `email`: Unique email address for the user.
- `username`: Unique username.
- `password`: Hashed password.
- `question1`, `question2`, `question3`: Security questions.
- `answer1`, `answer2`, `answer3`: Hashed answers.
- `activation_token`: Token for email verification.
- `is_active`: Indicates if the account is verified.
- `created_at`: Timestamp of account creation.

## How to Run
1. **Set Up the Database**:
   - Import `create_users_table.sql` into your MySQL database.
   - Ensure the `config.php` file has the correct database credentials.

2. **Deploy on AWS**:
   - Use AWS EC2 for hosting PHP files and AWS RDS for the MySQL database.
   - Ensure the server has `httpd` or equivalent web server software installed.

3. **Email Configuration**:
   - Update `reg.php` and related files with your email credentials (SMTP server, sender email).

4. **Access the Application**:
   - Navigate to `index.html` to start using the application.

## Future Enhancements
- Implement Two-Factor Authentication (2FA).
- Add an admin dashboard for user management.
- Enhance security with rate-limiting and IP-based blocking.
