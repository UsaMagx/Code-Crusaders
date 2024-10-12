document.addEventListener('DOMContentLoaded', function () {
    const nameField = document.getElementById('name');
    const surnameField = document.getElementById('surname');
    const emailField = document.getElementById('email');
    const passwordField = document.getElementById('psw');
    const repeatPasswordField = document.getElementById('psw-repeat');
    const form = document.querySelector('.signup-form');
    const errorMessage = document.getElementById('Error');

    // Function to allow only letters in name and surname
    function validateLetters(inputField) {
        inputField.addEventListener('input', function () {
            const regex = /^[a-zA-Z]*$/; // Regex to allow only letters
            if (!regex.test(inputField.value)) {
                inputField.value = inputField.value.replace(/[^a-zA-Z]/g, ''); // Remove invalid characters
            }
        });
    }

    // Function to validate email format
    function validateEmail() {
        const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; // Basic email pattern
        return regex.test(emailField.value);
    }

    // Function to apply or remove red border based on email validity
    function checkEmailValidity() {
        if (!validateEmail()) {
            emailField.style.border = '2px solid red'; // Set border to red for invalid input
        } else {
            emailField.style.border = ''; // Reset border for valid input
        }
    }

    // Function to check if passwords match
    function checkPasswordsMatch() {
        if (passwordField.value !== repeatPasswordField.value) {
            repeatPasswordField.style.border = '2px solid red'; // Set border to red if passwords do not match
            errorMessage.style.display = 'inline'; // Show the error message
            return false;
        } else {
            repeatPasswordField.style.border = ''; // Reset border if passwords match
            errorMessage.style.display = 'none'; // Hide the error message
            return true;
        }
    }

    // Apply letter validation to name and surname fields
    validateLetters(nameField);
    validateLetters(surnameField);

    // Check email validity as user types in the email field
    emailField.addEventListener('input', checkEmailValidity);

    // Check if passwords match as user types in the repeat password field
    repeatPasswordField.addEventListener('input', checkPasswordsMatch);

    // Form submit event listener to validate the form before submission
    form.addEventListener('submit', function (event) {
        if (!validateEmail()) {
            event.preventDefault(); // Prevent form submission if email is invalid
            alert('Please enter a valid email address.');
            emailField.focus(); // Focus on the email field for correction
        }

        if (!checkPasswordsMatch()) {
            event.preventDefault(); // Prevent form submission if passwords do not match
            alert('Passwords do not match!');
            repeatPasswordField.focus(); // Focus on the repeat password field for correction
        }
    });

});