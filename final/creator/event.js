// script.js

// Wait until the DOM is fully loaded
document.addEventListener("DOMContentLoaded", function () {
    // Ensure price input is always a decimal with 2 places
    const priceInput = document.querySelector('input[name="price"]');
    priceInput.addEventListener('input', function (event) {
        let value = event.target.value;

        // Remove any non-numeric or extra dots
        value = value.replace(/[^\d.]/g, '');

        // Ensure there's only one decimal point and limit to two decimal places
        const decimalIndex = value.indexOf('.');
        if (decimalIndex !== -1) {
            const wholePart = value.substring(0, decimalIndex);
            let decimalPart = value.substring(decimalIndex + 1);
            decimalPart = decimalPart.substring(0, 2); // Limit to two decimal places
            value = wholePart + '.' + decimalPart;
        }

        event.target.value = value;
    });

    // Restrict file input to images only
    const fileInput = document.querySelector('input[type="file"]');
    fileInput.addEventListener('change', function (event) {
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
        const file = event.target.files[0];

        if (file && !allowedTypes.includes(file.type)) {
            alert('Please upload a valid image file (jpg, jpeg, png).');
            event.target.value = ''; // Clear the invalid file input
        }
    });

    // Form submission validation
    const form = document.querySelector('form');
    form.addEventListener('submit', function (event) {
        const priceValue = priceInput.value;
        const pricePattern = /^\d+(\.\d{1,2})?$/; // Regex for valid price

        // Validate price
        if (!pricePattern.test(priceValue)) {
            alert('Please enter a valid price (up to two decimal places).');
            event.preventDefault(); // Prevent form submission
            return; // Exit the function
        }

        // Check if a file is selected
        if (!fileInput.files.length) {
            alert('Please upload a picture of the event.');
            event.preventDefault(); // Prevent form submission
            return; // Exit the function
        }
    });
});
