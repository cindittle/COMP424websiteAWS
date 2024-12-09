document.addEventListener('DOMContentLoaded', function () {
    // Fetch user data from welcome.php
    fetch('welcome.php')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const user = data.data;

                // Update UI with user information
                document.getElementById('first_name').textContent = user.first_name || 'Guest';
                document.getElementById('login_info').innerHTML = `You have logged in <strong>${user.login_count || 0}</strong> times.`;
                document.getElementById('last_login').innerHTML = `Last login date: <strong>${user.last_login || 'N/A'}</strong>`;

                // Handle email verification button visibility
                const verifyEmailButton = document.querySelector('.email-button');
                verifyEmailButton.style.display = user.is_verified ? 'none' : 'inline-block';
            } else {
                console.error(data.message);
                // Redirect to login if not logged in
                window.location.href = 'login.php';
            }
        })
        .catch(error => console.error('Error fetching user data:', error));
});
