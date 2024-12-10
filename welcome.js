document.addEventListener('DOMContentLoaded', () => {
    fetch('welcome.php')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                document.getElementById('first_name').textContent = data.data.first_name;
                document.getElementById('login_info').innerHTML = `You have logged in <strong>${data.data.login_count}</strong> times.`;
                document.getElementById('last_login').innerHTML = `Last login date: <strong>${data.data.last_login}</strong>`;
            } else {
                console.error('Error:', data.message);
                window.location.href = 'index.html';
            }
        })
        .catch(error => console.error('Fetch error:', error));
});
