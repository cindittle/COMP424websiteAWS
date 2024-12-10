document.addEventListener('DOMContentLoaded', function () {
    // Fetch user data from welcome.php
    fetch('welcome.php')
    .then(response => response.json())
    .then(data => {
        console.log(data); // Check the data received
        if (data.status === 'success') {
            const user = data.data;
            document.getElementById('first_name').textContent = user.first_name || 'Guest';
            document.getElementById('login_info').innerHTML = `You have logged in <strong>${user.login_count}</strong> times.`;
            document.getElementById('last_login').innerHTML = `Last login date: <strong>${user.last_login || 'N/A'}</strong>`;
        }
    })
    .catch(error => console.error('Error fetching user data:', error));
});
