<?php
session_start();
//print_r($_SESSION);
//print_r($_REQUEST);
// AccountID... ill get it from the sessions when everything is linked
$accountID = $_SESSION['acc_id']; // ill also get this from the sessions

$ratingID = null;

require_once("Config.php");

// Create a connection to the database
$conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);
// get name and surname 
$NameSql = "SELECT  FirstName,LastName from user
        join account on user.AccountID = account.AccountID
        where account.AccountID = ?";

$NameSqlstmt = $conn->prepare($NameSql);
$NameSqlstmt->bind_param("i", $accountID);
$NameSqlstmt->execute();
$NameSqlstmt->bind_result($name, $surname);
$FirstName = null;
$LastName = null;
while ($NameSqlstmt->fetch()) {
    $FirstName = $name;
    $LastName = $surname;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="myReviewsSyle.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <title>Document</title>

    

    <script>
        function showModal(reviewID) {
            // Show the modal
            document.getElementById("myModal").style.display = "block";
            document.getElementById("confirmDeleteButton").onclick = function () {
                // Set the review ID in the form and submit
                document.getElementById("deleteForm").action = 'delete_review.php?reviewID=' + reviewID;
                document.getElementById("deleteForm").submit();
            };
        }

        function hideModal() {
            // Hide the modal
            document.getElementById("myModal").style.display = "none";
        }
    </script>
</head>

<body>

    <table>
        <td>
            <nav class="navigation">
                <a href="index.php" style="color: #bec5ad">HOME</a>
         
                <a href="../login/logout.php" style="color: #bec5ad">LOG OUT</a>
            </nav>
        </td>
    </table>
    <header>
        <h2> <?php echo $FirstName . " " . $LastName ?></h2>
    </header>
    <main>
        <?php
        // SQL query to get reviews
        $getReviewsSQL = "SELECT rating.RatingNumber, rating.Description, event.Title, photo.Path, rating.RatingID 
                  FROM rating
                  JOIN event ON event.EventID = rating.EventID
                  JOIN photo ON photo.EventID = event.EventID
                  JOIN account ON account.AccountID = rating.AccountID
                  WHERE rating.AccountID = ?";

        // Prepare the SQL statement
        $getReviewsSQLstmtp = $conn->prepare($getReviewsSQL);
        $getReviewsSQLstmtp->bind_param("i", $accountID);

        // Execute the statement
        $getReviewsSQLstmtp->execute();

        // Bind the results to variables
        $getReviewsSQLstmtp->bind_result($ratingNumber, $Description, $Title, $Path, $RatingID);

        // Fetch the results
        while ($getReviewsSQLstmtp->fetch()) {
            // Extracting the rating number, description, title, and photo path
            $stars = $ratingNumber; 
            $comment = $Description;
            $title = $Title;
            $photo = $Path; 
            $ratingID = $RatingID;

            // Display fetched data
            echo "<div class='review' id='review'>
                    <div class='top' id='top'>
                        <div class='top-left' id='top-left'>
                            <img src='$photo' alt='Review Photo' style='width:100px; height:auto;'> <!-- Add alt and adjust size -->
                            <p class='title' id='title'>$title</p>
                            <button onclick='showModal($ratingID)' class='delete-button' title='Delete Review'>
                                <i class='fas fa-trash'></i> <!-- Font Awesome trash icon -->
                            </button>
                        </div>
                        <div class='top-right' id='top-right'>
                            <div class='rating'>";

            // Loop to display stars
            for ($i = 1; $i <= 5; $i++) {
                if ($i <= $stars) {
                    echo "<i class='rating__star fas fa-star' data-value='$i'></i>"; // Filled star
                } else {
                    echo "<i class='rating__star far fa-star' data-value='$i'></i>"; // Unfilled star
                }
            }

            echo "</div>
                        </div>
                    </div>
                    <div class='comment' id='comment'>
                        $comment
                    </div> <!-- Comment section now inside review but below picture and stars -->
                </div>"; // Close the .review div
        }

        // Close the statement after fetching
        $getReviewsSQLstmtp->close();
        ?>
    </main>

    <!-- The Modal -->
    <div id="myModal" class="modal">
    <!-- Modal content -->
    <div class="modal-content">
        <span class="close" onclick="hideModal()">&times;</span>
        <p>Are you sure you want to delete this review?</p>
        <form id="deleteForm" method="POST" action="delete_review.php">
            <!-- Hidden input to carry the ratingID -->
            <input type="hidden" name="ratingID" id="ratingID" value="<?php echo $ratingID?>">

            <!-- Submit button to send form with POST method -->
            <button type="submit" id="confirmDeleteButton">Yes</button>
            <button type="button" onclick="hideModal()">No</button>
        </form>
    </div>
</div>


    <footer style="text-align:center;background-color: #636B2F;">
        <p style="color: #bec5ad;">&copy Author: Code Crusaders | <a href="mailto:codecrusaders@gmail.com">codecrusaders@gmail.com</a></p>
    </footer>
</body>

</html>
