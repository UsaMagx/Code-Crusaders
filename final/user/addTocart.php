<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
    require_once("../login/secure.php");
    require_once("Config.php");

    $conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);
    if ($conn->connect_error) {
      die("<p class=\"error\">Error connecting to the system!</p>");
    }
    
    // create new ticket 
    print_r($_REQUEST);
    print_r($_SESSION);
    $accountID = $_SESSION['acc_id'];
    $userIDsql = "SELECT UserID FROM user WHERE AccountID = ?";

    // Initialize prepared statement
    if ($userIDstmtp = $conn->prepare($userIDsql)) {
        // Bind the parameter to the prepared statement
        $userIDstmtp->bind_param("i", $accountID);
    
        // Execute the prepared statement
        $userIDstmtp->execute();
    
        // Bind the result to a variable
        $userIDstmtp->bind_result($result);
    
        // Fetch the result
        while ($userIDstmtp->fetch()) {
            $userID = $result;
        }
    
        // Close the statement
        $userIDstmtp->close();
    } else {
        // Handle errors if the preparation fails
        echo "Error preparing statement: " . $conn->error;
    }
    //echo $userID;
    // get userID
   // $accountID = $_SESSION['']
    $eventID = $_REQUEST['eventID'];
    $createTicket = "INSERT into ticket (UserID,Quantity,EventID,Status)
                    Values(?,1,?,'Add to cart')";
    
   // $sql = "UPDATE ticket set ticket.status = 'Add to cart'
    //         where ticketID = ";

    $createTicketstmt = $conn->prepare($createTicket);
    $createTicketstmt->bind_param('ii',$userID,$eventID);
    $createTicketstmt->execute();
    header("Location: index.php");
    exit(); // Make sure to call exit after redirection to stop further script execution
    ?>
    

    
</body>
</html>