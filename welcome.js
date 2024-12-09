document.addEventListener('DOMContentLoaded', function () {
    // Fetch user data from welcome.php
    fetch('welcome.php')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const user = data.data;

                // Update UI with user information
                document.getElementById('user-name').textContent = user.first_name;
                document.getElementById('login-info').innerHTML = `You have logged in <strong>${user.login_count}</strong> times.`;
                document.getElementById('last-login').innerHTML = `Last login date: <strong>${user.last_login}</strong>`;

                // Handle email verification button visibility
                const verifyEmailButton = document.querySelector('.email-button');
                verifyEmailButton.style.display = user.is_verified ? 'none' : 'inline-block';
            } else {
                // Redirect to login page if not logged in
                console.error(data.message);
                window.location.href = 'login.html';
            }
        })
        .catch(error => console.error('Error fetching user data:', error));
});