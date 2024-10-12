<?php
require_once("../login/secure.php");
$accId = $_SESSION['acc_id'];

require_once("Config.php");  

// take the new values from the form


// Connect to the database
$conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);

if ($conn->connect_error) {
    die("Connection to database failed!"); // If failed to connect
}

   $sql1="select * from ticket
        inner join codecrusaders.user
        on user.userID=TICKET.UserID INNER JOIN
        codecrusaders.account on
        account.accountID=USER.AccountID WHERE account.AccountID='$accId'";

   $result1 = $conn->query($sql1);

   if ($result1 === FALSE) {
    die("UNABLE TO recieve data");
}


   while ($row =$result1->fetch_assoc()){
    $quantity = $row['Quantity'];

    $sqlstatus="UPDATE ticket
                  INNER JOIN codecrusaders.user ON ticket.UserID = user.UserID
                  INNER JOIN codecrusaders.account ON user.ACCOUNTID = account.AccountID
                  SET ticket.status = 'purchased'
                  WHERE account.AccountID = '$accId'  ";
    $resultstatus = $conn->query($sqlstatus);

    if ($resultstatus === FALSE) {
        die("UNABLE TO purchase");
    }

    $sqlcapacity="UPDATE event 
    SET CurrentCapacity = CurrentCapacity + $quantity";

    $resultcapacity = $conn->query($sqlcapacity);

    if ($resultcapacity === FALSE) {
        die("UNABLE TO inc capacity");
    }
   } 
   
   header("Location: cartPage.php");
      
/*       if ($result1 === FALSE) {
          die("UNABLE TO UPDATE THE RECORD");
      }
      while ($row =$result1->fetch_assoc()){
        if ($row['quantity'] === 0) {
            header("Location: delete.php");
        }
      }

      
     $sql = "UPDATE codecrusaders.ticket
  SET Quantity = Quantity - 1 WHERE ticketID='$Id';";
      
      // Execute the query
      $result = $conn->query($sql);
      
      if ($result === FALSE) {
          die("UNABLE TO UPDATE THE RECORD");
      }
   
    header("Location: cartPage.php"); */
    ?>