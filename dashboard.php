<?php
session_start();
include("config.php");
date_default_timezone_set('Asia/Manila');

// Check if the user is logged in
if (isset($_SESSION['user_email']) && $_SESSION['user_email'] == "admin.hayopkalinga@gmail.com") {
    // The user has the correct email, continue with your code.
} else {
    // The user doesn't have the correct email
    echo '<script>alert("You do not have administrator previlages.");</script>';
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
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-brand">
            <h1><span class="las la-paw">Hayop Kalinga</span></h1>
        </div>
        <div class="sidebar-menu">
            <ul>
                <li>
                    <a class="active"><span class="las la-heartbeat"></span>
                    <span>Dashboard</span></a>
                </li>
                <li>
                    <a href="customers.php"><span class="las la-user"></span>
                    <span>Customers</span></a>
                </li>
                <li>
                    <a href="diagnosis.php"><span class="las la-file-medical"></span>
                    <span>Diagnosis</span></a>
                </li>
                <li>
                    <a href="orders.php"><span class="las la-receipt"></span>
                    <span>Inventory Analytics</span></a>
                </li>
                <li>
                    <a href="inventory.php"><span class="las la-receipt"></span>
                    <span>Inventory</span></a>
                </li>
            </ul>
        </div>
    </div>
    <div class="main-content">
            <header>
                <h2>
                    <label class="header-title">
                        <span class="las la-bars"></span>
                    </label>
                    Dashboard
                </h2>
            </header>
        <main>
            <div class="cards">
                <div class="card-single">
                    <div>
                        <h1><?php
                        include("config.php");
                        $currentDate = date("Y-m-d");

                        $query = "SELECT COUNT(*) as checkup_count FROM appointments WHERE DATE(appointments.date) = '$currentDate' AND appointments.reason_for_visit = 'Check-up'
                        ORDER BY appointments.time ASC";
                        $result = $conn->query($query);

                        if ($result) {
                            $row = $result->fetch_assoc();
                            $checkupCount = $row['checkup_count'];
                        } else {
                            $checkupCount = 0;
                        }
                        echo $checkupCount;
                        // Close the database connection
                        ?></h1>
                        <span>Check-up</span>
                    </div>
                    <div>
                        <span class="las la-stethoscope"></span>
                    </div>
                </div>
                <div class="card-single">
                    <div>
                    <h1><?php
                        include("config.php");
                        $currentDate = date("Y-m-d");

                        $query = "SELECT COUNT(*) as checkup_count FROM appointments WHERE DATE(appointments.date) = '$currentDate' AND appointments.reason_for_visit = 'Grooming'
                        ORDER BY appointments.time ASC";
                        $result = $conn->query($query);

                        if ($result) {
                            $row = $result->fetch_assoc();
                            $checkupCount = $row['checkup_count'];
                        } else {
                            $checkupCount = 0;
                        }
                        echo $checkupCount;
                        // Close the database connection
                        ?></h1>
                        <span>Grooming</span>
                    </div>
                    <div>
                        <span class="las la-cut"></span>
                    </div>
                </div>
                <div class="card-single">
                    <div>
                    <h1><?php
                        include("config.php");
                        $currentDate = date("Y-m-d");

                        $query = "SELECT COUNT(*) as checkup_count FROM appointments WHERE DATE(appointments.date) = '$currentDate' AND appointments.reason_for_visit = 'Home Service'
                        ORDER BY appointments.time ASC";
                        $result = $conn->query($query);

                        if ($result) {
                            $row = $result->fetch_assoc();
                            $checkupCount = $row['checkup_count'];
                        } else {
                            $checkupCount = 0;
                        }
                        echo $checkupCount;
                        // Close the database connection
                        ?></h1>
                        <span>Home Service</span>
                    </div>
                    <div>
                        <span class="las la-truck"></span>
                    </div>
                </div>
                <div class="card-single">
                    <div>
                    <h1><?php
                        include("config.php");
                        $currentDate = date("Y-m-d");

                        $query = "SELECT COUNT(*) as checkup_count FROM appointments WHERE DATE(appointments.date) = '$currentDate' AND appointments.reason_for_visit = 'Pet Housing'
                        ORDER BY appointments.time ASC";
                        $result = $conn->query($query);

                        if ($result) {
                            $row = $result->fetch_assoc();
                            $checkupCount = $row['checkup_count'];
                        } else {
                            $checkupCount = 0;
                        }
                        echo $checkupCount;
                        // Close the database connection
                        ?></h1>
                        <span>Pet Housing</span>
                    </div>
                    <div>
                        <span class="las la-home"></span>
                    </div>
                </div>
                <div class="card-single">
                    <div>
                    <h1><?php
                        include("config.php");
                        $currentDate = date("Y-m-d");

                        $query = "SELECT COUNT(*) as checkup_count FROM appointments WHERE DATE(appointments.date) = '$currentDate' AND appointments.reason_for_visit = 'Vaccination'
                        ORDER BY appointments.time ASC";
                        $result = $conn->query($query);

                        if ($result) {
                            $row = $result->fetch_assoc();
                            $checkupCount = $row['checkup_count'];
                        } else {
                            $checkupCount = 0;
                        }
                        echo $checkupCount;
                        // Close the database connection
                        ?></h1>
                        <span>Vaccination</span>
                    </div>
                    <div>
                        <span class="las la-syringe"></span>
                    </div>
                </div>
                <div class="table-card">
                    <table>
                        <thead>
                            <th>Name</th>
                            <th>Time</th>
                        </thead>
                        <tbody>
                        <?php
                            include("config.php"); // Make sure "config.php" includes the database connection settings

                            // Get the current date in the format YYYY-MM-DD
                            $currentDate = date("Y-m-d");

                            // SQL query to retrieve entries for today with reason_for_visit = 'Check-up' by joining "users" and "appointments" tables
                            $sql = "SELECT CONCAT(users.fname, ' ', users.lname) AS name, appointments.time AS time
                                    FROM users 
                                    JOIN appointments ON users.id = appointments.id_user
                                    WHERE DATE(appointments.date) = '$currentDate' AND appointments.reason_for_visit = 'Check-up'
                                    ORDER BY appointments.time ASC";
                            // Execute the query
                            $result = $conn->query($sql);
    
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $name = $row["name"];
                                    $time = $row["time"];

                                    echo '<tr>';
                                    echo '<td>' . $name . '</td>';
                                    echo '<td>' . $time . '</td>';
                                    echo '</tr>';
                                }
                            } else {
                                echo '<tr><td colspan="2">No Check-up appointments for today.</td></tr>';
                            }
                        ?>
                        </tbody>
                    </table>
                </div>
                <div class="table-card">
                    <table>
                        <thead>
                            <th>Name</th>
                            <th>Time</th>
                        </thead>
                        <tbody>
                        <?php
                            include("config.php"); // Make sure "config.php" includes the database connection settings

                            // Get the current date in the format YYYY-MM-DD
                            $currentDate = date("Y-m-d");

                            // SQL query to retrieve entries for today with reason_for_visit = 'Check-up' by joining "users" and "appointments" tables
                            $sql = "SELECT CONCAT(users.fname, ' ', users.lname) AS name, appointments.time AS time
                                    FROM users 
                                    JOIN appointments ON users.id = appointments.id_user
                                    WHERE DATE(appointments.date) = '$currentDate' AND appointments.reason_for_visit = 'Grooming'
                                    ORDER BY appointments.time ASC";

                            // Execute the query
                            $result = $conn->query($sql);
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $name = $row["name"];
                                    $time = $row["time"];

                                    echo '<tr>';
                                    echo '<td>' . $name . '</td>';
                                    echo '<td>' . $time . '</td>';
                                    echo '</tr>';
                                }
                            } else {
                                echo '<tr><td colspan="2">No Grooming appointments for today.</td></tr>';
                            }
                        ?>
                        </tbody>
                    </table>
                </div>
                <div class="table-card">
                    <table>
                        <thead>
                            <th>Name</th>
                            <th>Time</th>
                        </thead>
                        <tbody>
                        <?php
                            include("config.php"); // Make sure "config.php" includes the database connection settings

                            // Get the current date in the format YYYY-MM-DD
                            $currentDate = date("Y-m-d");

                            // SQL query to retrieve entries for today with reason_for_visit = 'Check-up' by joining "users" and "appointments" tables
                            $sql = "SELECT CONCAT(users.fname, ' ', users.lname) AS name, appointments.time AS time
                                    FROM users 
                                    JOIN appointments ON users.id = appointments.id_user
                                    WHERE DATE(appointments.date) = '$currentDate' AND appointments.reason_for_visit = 'Home Service'
                                    ORDER BY appointments.time ASC";

                            // Execute the query
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $name = $row["name"];
                                    $time = $row["time"];

                                    echo '<tr>';
                                    echo '<td>' . $name . '</td>';
                                    echo '<td>' . $time . '</td>';
                                    echo '</tr>';
                                }
                            } else {
                                echo '<tr><td colspan="2">No Home Service appointments for today.</td></tr>';
                            }
                        ?>
                        </tbody>
                    </table>
                </div>
                <div class="table-card">
                    <table>
                        <thead>
                            <th>Name</th>
                            <th>Time</th>
                        </thead>
                        <tbody>
                        <?php
                            include("config.php"); // Make sure "config.php" includes the database connection settings

                            // Get the current date in the format YYYY-MM-DD
                            $currentDate = date("Y-m-d");

                            // SQL query to retrieve entries for today with reason_for_visit = 'Check-up' by joining "users" and "appointments" tables
                            $sql = "SELECT CONCAT(users.fname, ' ', users.lname) AS name, appointments.time AS time
                                    FROM users 
                                    JOIN appointments ON users.id = appointments.id_user
                                    WHERE DATE(appointments.date) = '$currentDate' AND appointments.reason_for_visit = 'Pet Housing'
                                    ORDER BY appointments.time ASC";

                            // Execute the query
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $name = $row["name"];
                                    $time = $row["time"];

                                    echo '<tr>';
                                    echo '<td>' . $name . '</td>';
                                    echo '<td>' . $time . '</td>';
                                    echo '</tr>';
                                }
                            } else {
                                echo '<tr><td colspan="2">No Pet Housing appointments for today.</td></tr>';
                            }
                        ?>
                        </tbody>
                    </table>
                </div>
                <div class="table-card">
                    <table>
                        <thead>
                            <th>Name</th>
                            <th>Time</th>
                        </thead>
                        <tbody>
                        <?php
                            include("config.php"); // Make sure "config.php" includes the database connection settings

                            // Get the current date in the format YYYY-MM-DD
                            $currentDate = date("Y-m-d");

                            // SQL query to retrieve entries for today with reason_for_visit = 'Check-up' by joining "users" and "appointments" tables
                            $sql = "SELECT CONCAT(users.fname, ' ', users.lname) AS name, appointments.time AS time
                                    FROM users 
                                    JOIN appointments ON users.id = appointments.id_user
                                    WHERE DATE(appointments.date) = '$currentDate' AND appointments.reason_for_visit = 'Vaccination'
                                    ORDER BY appointments.time ASC";

                            // Execute the query
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $name = $row["name"];
                                    $time = $row["time"];

                                    echo '<tr>';
                                    echo '<td>' . $name . '</td>';
                                    echo '<td>' . $time . '</td>';
                                    echo '</tr>';
                                }
                            } else {
                                echo '<tr><td colspan="2">No Vaccination appointments for today.</td></tr>';
                            }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</body>
</html>