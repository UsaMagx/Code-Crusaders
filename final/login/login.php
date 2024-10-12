<?php
session_start()
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <main>
        <form class="login-form" id="login-form" onsubmit="return validateLogin(event)" action="loginauth.php" method="post">
            <h1>Log In</h1>
            <div class="login-fields">

                <div class="inpdata">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" placeholder="Enter Username" required aria-required="true">
                </div>

                <div class="inpdata">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="psw" placeholder="Enter Password" required aria-required="true">
                </div>

                <!-- Error message handling -->
                <?php if (isset($_GET['error'])): ?>
                    <p class="error" id="error" style="color: red;">
                        <?php
                        switch ($_REQUEST['error']) {
                            case 'username':
                                echo "Invalid username, please try again...";
                                break;
                            case 'password':
                                echo "Incorrect password, please try again...";
                                break;
                            default:
                                echo "An unknown error occurred.";
                        }
                        ?>
                    </p>
                <?php endif; ?>

                <div class="signup">
                    <button type="submit">Login</button>
                </div>
            </div>
            <p class="switch-link">Don't have an account? <a href="signup.html">Sign Up</a></p>
        </form>
    </main>

    <script>
    function validateLogin(event) {
        event.preventDefault(); // Prevent default form submission

        let isValid = true; // Assume form is valid initially

        const usernameField = document.getElementById("username");
        const passwordField = document.getElementById("password");
        const errorElement = document.getElementById("error");

        // Clear previous error styles
        usernameField.style.borderColor = "";
        passwordField.style.borderColor = "";

        // Check if fields are empty and apply styles
        if (!usernameField.value) {
            usernameField.style.borderColor = "red";
            isValid = false;
        }
        if (!passwordField.value) {
            passwordField.style.borderColor = "red";
            isValid = false;
        }
        // If valid, clear any URL parameters and submit the form
        if (isValid) {
            // Clear URL parameters
            
            document.getElementById("login-form").submit(); // Proceed with form submission
        }
    }
</script>
</body>

</html>

