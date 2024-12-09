document.addEventListener('DOMContentLoaded', function () {
    // Fetch user data from the server
    fetch('welcome.php')
        .then(response => response.json())
        .then(data => {
            if (data.status === "success") {
                const user = data.data;
                document.getElementById('user-name').textContent = `${user.first_name} ${user.last_name}`;
                document.getElementById('login-info').innerHTML = `You have logged in <strong>${user.login_count}</strong> times.`;
                document.getElementById('last-login').innerHTML = `Last login date: <strong>${user.last_login}</strong>`;
                if (!user.is_verified) {
                    document.getElementById('verify-email').style.display = 'block';
                }
            } else {
                console.error(data.message);
            }
        })
        .catch(error => console.error('Error fetching user data:', error));
});
