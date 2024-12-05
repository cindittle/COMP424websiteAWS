// Script to dynamically change user details, for example from PHP variables
document.addEventListener('DOMContentLoaded', function () {
    // Assuming you have a way to get user data (like from PHP or an API)
    const userName = "John Doe"; // Example: Dynamically populate this with PHP or JavaScript
    const loginCount = 5; // Example: Dynamically populate this with PHP or JavaScript
    const lastLoginDate = "2024-12-01 14:30:00"; // Example: Dynamically populate this with PHP or JavaScript
    
    // Update the HTML elements with dynamic values
    document.getElementById('user-name').textContent = userName;
    document.getElementById('login-info').innerHTML = `You have logged in <strong>${loginCount}</strong> times.`;
    document.getElementById('last-login').innerHTML = `Last login date: <strong>${lastLoginDate}</strong>`;
});
