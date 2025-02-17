document.getElementById('loginForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent form submission

    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;
    const errorMessage = document.getElementById('error-message');

    // Simple validation
    if (username === '' || password === '') {
        errorMessage.textContent = 'Please fill in all fields.';
    } else {
        errorMessage.textContent = ''; // Clear error message
        // Here you can add your login logic (e.g., API call)
        alert('Login successful!'); // Placeholder for successful login
    }
});