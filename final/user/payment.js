document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('paymentForm');

    if (!form) {
        console.error("Form not found! Check the ID of the form.");
        return; // Exit if form is not found
    }

    // Reference to the card number, name, and CVV input fields
    const cardNumber = document.getElementById('cardNumber');
    const nameOnCard = document.getElementById('nameOnCard');
    const cvv = document.getElementById('cvv');

    // Add event listeners for input events on each field
    cardNumber.addEventListener('input', validateCardNumber);
    nameOnCard.addEventListener('input', validateNameOnCard);
    cvv.addEventListener('input', validateCVV);

    // Validate each field on blur (when the user leaves the field)
    cardNumber.addEventListener('blur', validateCardNumber);
    nameOnCard.addEventListener('blur', validateNameOnCard);
    cvv.addEventListener('blur', validateCVV);

    form.addEventListener('submit', (event) => {
        event.preventDefault(); // Prevent the form from submitting

        // Call the validation function
        if (validateForm()) {
            // If valid, you can proceed with form submission
            alert("Form submitted successfully!"); // Placeholder action
            form.submit(); // Uncomment to submit the form after validation
        }
    });

    function validateForm() {
        let isValid = true;

        // Validate card number
        if (!validateCardNumber()) isValid = false;
        // Validate name
        if (!validateNameOnCard()) isValid = false;
        // Validate CVV
        if (!validateCVV()) isValid = false;

        return isValid;
    }

    function validateCardNumber() {
        // Validate card number (16 digits only)
        const cardNumberPattern = /^\d{16}$/;
        if (!cardNumberPattern.test(cardNumber.value)) {
            cardNumber.classList.add('error'); // Add error styling
            return false; // Not valid
        } else {
            cardNumber.classList.remove('error'); // Remove error styling if valid
            return true; // Valid
        }
    }

    function validateNameOnCard() {
        // Validate name (letters only)
        const namePattern = /^[a-zA-Z\s]+$/;
        if (!namePattern.test(nameOnCard.value)) {
            nameOnCard.classList.add('error'); // Add error styling
            return false; // Not valid
        } else {
            nameOnCard.classList.remove('error'); // Remove error styling if valid
            return true; // Valid
        }
    }

    function validateCVV() {
        // Validate CVV (3 digits only)
        const cvvPattern = /^\d{3}$/;
        if (!cvvPattern.test(cvv.value)) {
            cvv.classList.add('error'); // Add error styling
            return false; // Not valid
        } else {
            cvv.classList.remove('error'); // Remove error styling if valid
            return true; // Valid
        }
    }

    function clearErrors() {
        const inputs = form.querySelectorAll('input');
        inputs.forEach(input => {
            input.classList.remove('error');
        });
    }
});
