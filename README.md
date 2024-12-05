# User Authentication and Session Management Web Application

## Project Description
This project is a full-stack web application designed to allow users to register, log in, and manage their sessions securely. Key functionalities include user registration, login, session management, logout, and a password recovery system. The application uses PHP, MySQL (or AWS for the database), HTML, JavaScript, and CSS for both front-end and back-end development.

---

## File Descriptions

1. **index.html**
   - This is the main HTML file containing both the user registration and login forms.
   - Users can either create a new account or log in to an existing one.
   - Features include:
     - A password strength meter for real-time feedback on password strength during registration.
     - Hidden CSRF token fields in both the registration and login forms for added security.

2. **reg.php**
   - PHP script responsible for handling new user registrations.
   - Checks if the username is unique, hashes the user’s password, and stores user information (first name, last name, birth date, email, etc.) in the MySQL database.
   - Redirects to `index.php` upon successful registration, where the user is automatically logged in for the first time.
   - Includes CSRF token validation to protect against cross-site request forgery.

3. **login.php**
   - PHP script that handles user login by verifying entered credentials against the stored database information.
   - On successful login, the user is redirected to `index.php`.
   - Tracks failed login attempts and logs suspicious activity if there are too many failed attempts within a short period, alerting administrators of potential brute-force attacks.
   - CSRF token validation is also included.

4. **index.php**
   - The main application page, where logged-in users can view their login information.
   - Displays:
     - A greeting with the user’s first and last name.
     - Total login count for that user.
     - The last login date.
   - Contains a link to download a confidential file (`company_confidential_file.txt`), which is only accessible to logged-in users.
   - Includes session timeout and auto-logout functionality after 10 minutes of inactivity.

5. **logout.php**
   - PHP script that securely handles user logout.
   - Destroys the current session and redirects the user back to the login/registration page (`index.html`).
   - Ensures that user data is cleared from the session.

6. **forgotPassword.php**
   - Handles the password recovery process, where users can reset their password if they forget it.
   - Allows users to answer security questions to validate their identity.
   - Generates a reset token, valid for a short period, and sends a password reset link to the user's registered email.
   - Enables users to set a new password upon token verification.

7. **company_confidential_file.txt**
   - A confidential file that logged-in users can download from `index.php`.
   - This file is only accessible to authenticated users as per project security requirements.

---

## Completion Status
- **User registration**: Complete
- **User login**: Complete
- **Logout function**: Complete
- **Forgot password function**: Complete

---

## Security Features
- **CSRF Protection**: Hidden tokens in form submissions to prevent CSRF attacks.
- **Password Hashing**: Uses `password_hash()` for secure password storage.
- **Session Timeout**: Users are logged out after 10 minutes of inactivity.
- **Suspicious Activity Logging**: Tracks and logs failed login attempts for security monitoring.
- **HTTPS Enforcement** (recommended for deployment): Use HTTPS to secure all transmitted data.

---

## Known Issues
- None as of now.
