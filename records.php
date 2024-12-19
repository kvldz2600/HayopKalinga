<?php
session_start();
include("config.php");
date_default_timezone_set('Asia/Manila');

// Check if the user is logged in as admin
if (isset($_SESSION['user_email']) && $_SESSION['user_email'] == "admin.hayopkalinga@gmail.com") {
    // The user has the correct email, continue with your code.
} else {
    // The user doesn't have the correct email
    echo '<script>alert("You do not have administrator privileges.");</script>';
    header("Location: home.php");

    if (!isset($_SESSION['user_email'])) {
        echo '<script>alert("You are not logged in.");</script>';
        exit();
    }
}
?>

<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="refresh" content="30">
    <title>Hayop Kalinga | Administrator Page</title>
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
    <script type="text/javascript">
        // Function to open the pop-up window with user details
        function openPopUp(userId) {
            var url = "fetch_user_data.php?user_id=" + userId;  // URL of the PHP file that fetches data
            window.open(url, "User Data", "width=600,height=400,scrollbars=yes");
        }
    </script>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-brand">
            <h1><span class="las la-paw">Hayop Kalinga</span></h1>
        </div>
        <div class="sidebar-menu">
            <ul>
                <li><a href="dashboard.php"><span class="las la-heartbeat"></span><span>Dashboard</span></a></li>
                <li><a href="customers.php"><span class="las la-user"></span><span>Customers</span></a></li>
                <li><a class="active"><span class="las la-user"></span><span>Records</span></a></li>
                <li><a href="orders.php"><span class="las la-receipt"></span><span>Inventory Analytics</span></a></li>
                <li><a href="inventory.php"><span class="las la-receipt"></span><span>Inventory</span></a></li>
                <li><a href="logout.php"><span class="las la-user"></span><span>Logout</span></a></li>
            </ul>
        </div>
    </div>
    <div class="main-content">
        <header>
            <h2>
                <label class="header-title">
                    <span class="las la-bars"></span>
                </label>
                RECORDS
            </h2>
        </header>
        <main>
            <?php
            // Fetch user data from the 'users' table
            $sql = "SELECT * FROM users;";
            $result = mysqli_query($conn, $sql);
            $resultsCheck = mysqli_num_rows($result);

            if ($resultsCheck > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $userId = $row['id'];  // Assuming 'id' is the primary key
                    echo "<a href='#' onclick='openPopUp($userId)'>" . $row['fname'] . " " . $row['lname'] . "</a><br>";
                }
            } else {
                echo "NO RECORDS.";
            }
            ?>
        </main>
    </div>
</body>
</html>