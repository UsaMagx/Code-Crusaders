<?php
require_once("../login/secure.php");
//$evntID = $_REQUEST['eventID']; // i'll get the proper one from the url
//print_r($_REQUEST);
$accountID = $_SESSION['acc_id'];

require_once("Config.php");
$conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);
if ($conn->connect_error) {
    die("<p class=\"error\">Error connecting to the system!</p>");
}
$eventId = $_REQUEST['eventID'];
//echo $eventId;
$eventSQL = "SELECT event.Title,photo.path as picture
              From photo
              join event on photo.EventID = event.EventID
                where event.eventID=?";
$eventstmt = $conn->prepare($eventSQL);
$eventstmt->bind_param("i", $eventId);
$eventstmt->execute();
$eventstmt->bind_result($Title, $Picture);

$title = $picture = null;

while ($eventstmt->fetch()) {
    $title = $Title;
    $picture = $Picture;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <title>Document</title>
    <style>
        .rating {
            display: flex;
            justify-content: flex-start;
            justify-content: space-between;
            width: 180px;
        }

        .rating__star {
            cursor: pointer;
            color: #dabd18b2;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Body Styles */
        form h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        body {
            font-family: Verdana, sans-serif;
            background-color: #f0f0f0;
            padding: 20px;
        }

        /* Form Styling */
        form {
            background-color: #f5f1e3;
            padding: 20px;
            margin-top: 20px;
            border-radius: 10px;
            max-width: 600px;
            margin: 0 auto;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.2);
            position: fixed;
            top: 30%;
            left: 40%;
        }

        .form-group {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .form-group label {
            flex: 1;
            margin-right: 15px;
            font-weight: bold;
            text-align: left;
            /* Align labels to the left */
        }

        .form-group .rating {
            flex: 2;
            padding: 10px;
            /* Add padding to align with the textarea */
            border: 1px solid #ccc;
            /* Optional: mimic the textarea/input borders */
            border-radius: 5px;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            flex: 2;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 100%;
        }

        .form-group textarea {
            resize: vertical;
        }

        .rating__star {
            cursor: pointer;
            color: #dabd18b2;
            padding-right: 5px;
        }

        button {
            padding: 10px 20px;
            margin: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .signupbtn,
        .cancelbtn {
            width: 48%;
            color: #bec5ad;
            background-color: #533b4d;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .signupbtn:hover,
        .cancelbtn:hover {
            background-color: #636B2F;
            color: white;
        }

        .formbtns {
            display: flex;
        }

        @media (max-width: 768px) {
            form {
                padding: 10px;
            }

            .signupbtn,
            .cancelbtn {
                width: 100%;
            }

            .formbtns {
                flex-direction: column;
            }
        }

        td {
            padding: 15px;
            color: #bec5ad;
            text-align: center;
        }

        @media (max-width: 600px) {
            footer {
                font-size: 14px;
            }
        }

        #cancel {
            display: block;
        }
    </style>
</head>

<body>
    <div class="container" id="container">
        <form class="review-form" name="review-form" action="review.php" method="post">
            <h1 style="font-family:verdana">Review <?php echo "$title!" ?></h1>
            <div class="signup-fields">

                <div class="form-group">
                    <label for="stars">Stars:</label>
                    <div class="rating">
                        <i class="rating__star far fa-star" data-value="1"></i>
                        <i class="rating__star far fa-star" data-value="2"></i>
                        <i class="rating__star far fa-star" data-value="3"></i>
                        <i class="rating__star far fa-star" data-value="4"></i>
                        <i class="rating__star far fa-star" data-value="5"></i>
                    </div>
                </div>

                <div class="form-group">
                    <label for="comment"><b>Comment:</b></label>
                    <textarea id="description" name="description" rows="5" cols="30"></textarea>
                </div>
                <input type="hidden" id="rating-value" name="rating-value" value="">

            </div>
            <div class="formbtns">
                <button type="button" class="cancelbtn" id="cancel">Cancel</button>
                <button type="submit" class="signupbtn" id="upload">Upload</button>
            </div>
        </form>
    </div>
</body>
<script>
    const ratingStars = [...document.getElementsByClassName("rating__star")];
    const ratingInput = document.getElementById("rating-value");
    const comment = document.getElementById("description");
    
    function executeRating(stars) {
        const starClassActive = "rating__star fas fa-star";
        const starClassInactive = "rating__star far fa-star";
        const starsLength = stars.length;
        let i;

        stars.map((star) => {
            star.onclick = () => {
                i = stars.indexOf(star);
                ratingInput.value = star.getAttribute("data-value"); // Store rating in hidden input
                for (i; i >= 0; --i) stars[i].className = starClassActive;
                for (i = parseInt(ratingInput.value); i < starsLength; ++i) stars[i].className = starClassInactive;
            };
        });
    }

    executeRating(ratingStars);

    // Validate form before submission
    document.getElementById("upload").addEventListener("click", function(event) {
        if (comment.value.trim() === "") {
            event.preventDefault();
            comment.style.borderColor = "red";
            comment.value = "Please enter a comment";
        }
    });
</script>

</html>