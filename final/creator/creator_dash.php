<?php
require_once("../login/secure.php");
require_once("Config.php");

$accountID = $_SESSION['acc_id']; // i will find it later
//$creatorID = 1; // i will find it later
$conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);
// this is for graphs
$chardata = [];
$chardata2 = [];
$chardata3 = [];
$chardata4 = [];
// For my Events
$name = "SELECT creator.FirstName, creator.LastName, creator.CreatorID 
         FROM creator 
         INNER JOIN account ON account.AccountID = creator.AccountID 
         WHERE creator.AccountID = ?";
$namestmt = $conn->prepare($name);
$namestmt->bind_param("i", $accountID);
$namestmt->execute();
$result = $namestmt->get_result();

$FirstName = $LastName = $creatorID = null;
while ($row = $result->fetch_assoc()) {
    $FirstName = $row['FirstName'];
    $LastName = $row['LastName'];
    $creatorID = $row['CreatorID'];
}
$namestmt->close();

// for the graphs
$ticketsSold = "SELECT event.EventID,event.Title,event.CurrentCapacity from event
                     where event.CreatorID =?";
$ticketsSoldstmt = $conn->prepare($ticketsSold);
$ticketsSoldstmt->bind_param("i", $creatorID);
$ticketsSoldstmt->execute();
$result = $ticketsSoldstmt->get_result();

while ($row = $result->fetch_assoc()) {
    $chartData[] = "['" . $row['Title'] . "', " . $row['CurrentCapacity'] . "]";
}

$revenue = "SELECT event.Title, ( event.price * event.CurrentCapacity) as MoneyMade
            from event
            where event.CreatorID=?;";
$revenuestmt = $conn->prepare($revenue);
$revenuestmt->bind_param("i", $creatorID);
$revenuestmt->execute();
$result = $revenuestmt->get_result();

while ($row = $result->fetch_assoc()) {
    $chartData2[] = "['" . $row['Title'] . "', " . $row['MoneyMade'] . "]";
}

$category = "
        SELECT event.Title,event.CurrentCapacity,category.CategoryName
        from event
        inner join category on category.CategoryID = event.CategoryID
        where event.CreatorID=?;";
$categorystmt = $conn->prepare($category);
$categorystmt->bind_param("i", $creatorID);
$categorystmt->execute();
$result = $categorystmt->get_result();
while ($row = $result->fetch_assoc()) {
    $chartData3[] = "['" . $row['CategoryName'] . "', " . $row['CurrentCapacity'] . "]";
}
$curentDate = date('Y-m-d H:i:s');
$attendence = "SELECT event.Title,event.CurrentCapacity,ticket.TicketID,ticket.Status
                from event
                inner join ticket on ticket.EventID = event.EventID
                where (event.CreatorID=?) and(ticket.Status<>'cancelled') 
                and(event.DateTime<'2024-10-09 00:00:26') 
                and (ticket.Status<>'Add to cart');";
$attendencestmt = $conn->prepare($attendence);
$attendencestmt->bind_param("i", $creatorID);
$attendencestmt->execute();
$result = $attendencestmt->get_result();
while ($row = $result->fetch_assoc()) {
    $chartData4[] = "['" . $row['Title'] . "', " . $row['CurrentCapacity'] . "]";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="creator_dashStyle.css">
    <title>Document</title>
</head>

<body>
    <nav class="navigation">
        <a href="creator_dash.php" style="color: #bec5ad">HOME</a>

        <a href="../login/logout.php" style="color: #bec5ad">LOG OUT</a>
    </nav>
    <div class="sideAndMain">
        <div class="sideBar">
            <!-- this is a placeholder for now, will be able to operate properly with php -->
            <div class="search sideElement">
                <input type="text" id="myInput" placeholder="Search." title="Type in a name">
            </div>

            <div class="category sideElement">
                <button class="category">Categories</button>
                <div class="drop-content">
                    <a href="#sport">Sport</a>
                    <a href="#movies">Movies</a>
                    <a href="#theatre">Theatre</a>
                    <a href="#">Music Festival</a>
                    <a href="#comedy">Comedy</a>
                    <a href="#">Art Exhibition</a>
                </div>
            </div>
            <a href="createevent.php" class="normal">
            <button>Create Event</button>
            </a>
            <button class="normal" id="Past">Past Events</button>
            <button class="normal" id="show-graphs">Statistics</button>
        </div>
        <div class="graphs" id="graphs">
            <H1>Statistics</H1>
            <div id="columnchart_values"></div>
            <div id="columnchart_values2"></div>
            <div id="columnchart_values3"></div>
            <div id="columnchart_values4"></div>
        </div>
        <div class="events" id="events">
            <h1>Created Events</h1>
            <?php
            // Fetch events created by the current user
            $event = "SELECT category.CategoryName, photo.Path, event.Description, event.Title, event.EventID 
                  FROM event 
                  INNER JOIN category ON category.CategoryID = event.CategoryID 
                  INNER JOIN photo ON photo.EventID = event.EventID 
                  WHERE event.CreatorID = ?";
            $eventstmt = $conn->prepare($event);
            $eventstmt->bind_param("i", $creatorID);
            $eventstmt->execute();
            $result = $eventstmt->get_result();

            while ($row = $result->fetch_assoc()) {
                $category = $row['CategoryName'];
                $picture = $row['Path'];
                $description = $row['Description'];
                $title = $row['Title'];
                $eventID = $row['EventID'];

                echo "
            <figure class='event'>
                <a href='eventInfo.php?eventID=$eventID'>
                    <img src='$picture' alt='$title'>
                    <figcaption>
                        <h3>$title</h3>
                        <p>$description</p>
                    </figcaption>
                </a>
            </figure>";
            }
            $eventstmt->close();
            ?>


        </div>
    </div>

    <footer style="text-align:center;background-color: #636B2F;">
        <p style="color: #bec5ad;">&copy Author: Code Crusaders | <a
                href="mailto:codecrusaders@gmail.com">codecrusaders@gmail.com</a></p>
    </footer>


</body>

</html>
<script>
    const events = document.getElementById("events");
    const graphs = document.getElementById('graphs');
    const pastButton = document.getElementById("Past");
    const show_graphs = document.getElementById("show-graphs");
    // Debugging logs
    console.log("Events div: ", events);
    console.log("Graphs div: ", graphs);
    console.log("Past Button: ", pastButton);
    console.log("show-graphs: ", show_graphs);



    pastButton.addEventListener("click", function() {
        console.log("Past Events button clicked!"); // Check if event fires
        
        events.style.display = "block";  // Show events
        graphs.style.display = "none";   // Hide graphs
    });

    show_graphs.addEventListener("click", function() {
        
        
        events.style.display = "none";  // Show events
        graphs.style.display = "block";   // Hide graphs
    });

    </script>
<style>
    /* General Styles */
* {
    box-sizing: border-box;
}

body {
    font-family: 'Arial', sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f5f1e3;
    color: #333;
    margin-top: 75px;
}

html {
    scroll-behavior: smooth;
}

h1, h2, p {
    margin: 0;
    padding: 0;
}

a {
    text-decoration: none;
    color: #636B2F;
    transition: color 0.3s ease;
}

a:hover {
    color: #533b4d;
}

/* Navigation Styles */
.navigation {
    display: flex;
    justify-content: space-around;
    background-color: #636B2F;
    padding: 15px 0;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    z-index: 1000;
/*     color: #bec5ad;
    font-weight: bold;
    padding: 10px 15px;
    transition: background-color 0.3s ease, color 0.3s ease; */
}

.navigation a {
    color: #bec5ad;
    font-weight: bold;
    padding: 10px 15px;
    transition: background-color 0.3s ease, color 0.3s ease;
}

.navigation a:hover {
    background-color: #533b4d;
    color: #fff;
    border-radius: 5px;
}

/* Main Layout */
.sideAndMain {
    display: flex;
    margin-left: 22%; /* Adjust to provide space for the fixed sidebar */
    padding: 20px;
    flex-grow: 1;
}

/* Sidebar Styles */
.sideBar {
    background-color: #97a675;
    padding: 20px;
    width: 20%; /* Fixed width for the sidebar */
    border-radius: 10px;
    position: fixed;
    top: 75px;
    left: 0;
    height: calc(100vh - 75px - 25px);
    overflow-y: auto;
    z-index: 999;
}

.sideElement {
    margin-bottom: 20px;
}

.sideElement input[type="text"] {
    width: 100%;
    padding: 10px;
    border-radius: 5px;
    border: 1px solid #ccc;
}

button {
    display: block;
    width: 100%;
    padding: 10px;
    margin-bottom: 10px;
    border: none;
    border-radius: 5px;
    background-color: #636B2F;
    color: #fff;
    cursor: pointer;
    font-size: 16px;
    transition: background-color 0.3s ease;
}

button:hover {
    background-color: #533b4d;
    
}

/* Dropdown Styles */
.category {
    position: relative;
}

.drop-content {
    display: none;
    position: absolute;
    top: 100%;
    left: 0;
    background-color: #636B2F;
    padding: 10px;
    border-radius: 5px;
    min-width: 160px;
    z-index: 1;
}

.category:hover .drop-content {
    display: block;
}

.drop-content a {
    color: #fff;
    display: block;
    padding: 5px 0;
    transition: background-color 0.3s ease;
}

.drop-content a:hover {
    background-color: greenyellow;
}

/* Graphs Styles */
.graphs {
    width: calc(100% - 240px); /* Adjust width to fit beside the sidebar */
    background-color: #f5f5f5;
    padding: 20px;
    border-radius: 10px;
    display: grid;
    gap: 20px;
}

/* Events Styles */
.events {
    /*display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); /* Responsive grid for events */
    display: flex;
    flex-wrap: nowrap; /* No wrapping to keep all items in a single row */
    gap: 1rem;
    padding: 1rem;
    margin-left: 240px; /* Offset for the sidebar */
    margin-top: 40px;
    justify-content: start; /* Align items to the start */
}
.events{
    display: none;
}

/* Individual Event Card */
.event {
    background-color: #fff;
    border-radius: 8px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    height: 400px;
    border: 3px solid #636B2F;
    transition: transform 0.3s ease;
    cursor: pointer;
}

.event:hover {
    transform: scale(1.05);
}

.event img {
    width: 100%;
    height: 200px;
    object-fit: cover;
}

.event-description {
    padding: 1rem;
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.event-description h3 {
    color: #141301;
}

.event-description p {
    color: #333;
    margin-bottom: 2rem;
    text-align: center;
}

.rate-link {
    color: #636B2F;
    text-decoration: none;
    align-self: center;
    margin-top: auto;
}

.rate-link:hover {
    text-decoration: underline;
}

/* Footer Styles */
footer {
    display: flex;
    justify-content: space-around;
    background-color: #636B2F;
    padding: 15px 0;
    position: fixed; /* Fix the footer */
    bottom: 0;
    left: 0;
    width: 100%;
    z-index: 1000;
}
.graphs h1, .events h1 {
    text-align: center;
}
footer a {
    color: #ff9f1a;
    transition: color 0.3s ease;
}

footer a:hover {
    color: #fff;
}

/* Responsive Styles */
@media screen and (max-width: 768px) {
    .sideAndMain {
        flex-direction: column;
        margin-left: 0;
    }

    .sideBar {
        width: 100%;
        position: relative;
        height: auto;
    }

    .graphs, .events {
        width: 100%;
        margin-left: 0;
    }

    .events {
        grid-template-columns: 1fr;
    }
    H1{
        text-align: center;
    }
}

</style>





<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    // Load the Google Charts library
    google.charts.load('current', {
        'packages': ['corechart']
    });

    // Draw the charts when the library is loaded
    google.charts.setOnLoadCallback(function() {
        drawChart(); // First chart
        drawChart2();
        drawChart3();
        drawChart4();
    });

    function drawChart() {
    var data = google.visualization.arrayToDataTable([
        ['Event', 'Tickets Sold'],
        <?php echo implode(",\n", $chartData); ?>
    ]);

    var options = {
        title: 'Number of tickets sold per event',
        hAxis: {
            title: 'Events'
        },
        vAxis: {
            title: 'Tickets Sold'
        },
        legend: {
            position: 'top'
        },
        colors: ['#79a27e'], // Color scheme for the bars
        backgroundColor: {
            fill: '#f5f5f5'
        },
        chartArea: {
            width: '50%', // Adjust width to avoid crowding
            height: '70%'
        },
        bar: {
            groupWidth: '75%'
        },
        annotations: {
            alwaysOutside: true,
            textStyle: {
                color: '#000',
                fontSize: 12,
                auraColor: 'none'
            }
        },
        animation: {
            startup: true,
            duration: 1000,
            easing: 'out',
        },
    };

    var chart = new google.visualization.ColumnChart(document.getElementById('columnchart_values'));
    chart.draw(data, options);
}

function drawChart2() {
    var data = google.visualization.arrayToDataTable([
        ['Event', 'Revenue'],
        <?php echo implode(",\n", $chartData2); ?>
    ]);

    var options = {
        title: 'Revenue per event',
        hAxis: {
            title: 'Events'
        },
        vAxis: {
            title: 'Revenue'
        },
        legend: {
            position: 'top'
        },
        colors: ['#e67e22'], // Different color for the second chart
        backgroundColor: {
            fill: '#f5f5f5'
        },
        chartArea: {
            width: '50%', // Adjust width
            height: '70%'
        },
        bar: {
            groupWidth: '75%'
        },
        annotations: {
            alwaysOutside: true,
            textStyle: {
                color: '#000',
                fontSize: 12,
                auraColor: 'none'
            }
        },
        animation: {
            startup: true,
            duration: 1000,
            easing: 'out',
        },
    };

    var chart = new google.visualization.ColumnChart(document.getElementById('columnchart_values2'));
    chart.draw(data, options);
}

function drawChart3() {
    var data = google.visualization.arrayToDataTable([
        ['Category', 'Tickets Sold'],
        <?php echo implode(",\n", $chartData3); ?>
    ]);

    var options = {
        title: 'Tickets Sold Per Category',
        hAxis: {
            title: 'Category'
        },
        vAxis: {
            title: 'Tickets Sold'
        },
        legend: {
            position: 'top'
        },
        colors: ['#e67e22'], // Different color for the third chart
        backgroundColor: {
            fill: '#f5f5f5'
        },
        chartArea: {
            width: '50%', // Adjust width
            height: '70%'
        },
        bar: {
            groupWidth: '75%'
        },
        annotations: {
            alwaysOutside: true,
            textStyle: {
                color: '#000',
                fontSize: 12,
                auraColor: 'none'
            }
        },
        animation: {
            startup: true,
            duration: 1000,
            easing: 'out',
        },
    };

    var chart = new google.visualization.ColumnChart(document.getElementById('columnchart_values3'));
    chart.draw(data, options);
}

function drawChart4() {
    var data = google.visualization.arrayToDataTable([
        ['Event', 'Attendance'],
        <?php echo implode(",\n", $chartData4); ?>
    ]);

    var options = {
        title: 'Attendance per Event',
        hAxis: {
            title: 'Events'
        },
        vAxis: {
            title: 'Attendance'
        },
        legend: {
            position: 'top'
        },
        colors: ['#e67e22'], // Different color for the fourth chart
        backgroundColor: {
            fill: '#f5f5f5'
        },
        chartArea: {
            width: '50%', // Adjust width
            height: '70%'
        },
        bar: {
            groupWidth: '50%'
        },
        annotations: {
            alwaysOutside: true,
            textStyle: {
                color: '#000',
                fontSize: 12,
                auraColor: 'none'
            }
        },
        animation: {
            startup: true,
            duration: 1000,
            easing: 'out',
        },
    };

    var chart = new google.visualization.ColumnChart(document.getElementById('columnchart_values4'));
    chart.draw(data, options);
}

</script>