<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php
    echo "delele review";
    session_start();
    
    print_r($_REQUEST);
    require_once("Config.php");
    $conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);
    $accountID = 2; // ill also get this from the sessions
    $eventID = 2;
    $ratingID = $_REQUEST['reviewID'];
    $sql = "DELETE FROM rating
               WHERE RatingID = ?;";
    $stmpt=$conn->prepare($sql);
    $stmpt->bind_param("i",$ratingID);
    $stmpt->execute();
    header("Location: myReviews.php");
?>
    

</body>

</html>