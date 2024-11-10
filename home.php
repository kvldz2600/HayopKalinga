<?php
session_start();
include("config.php");

if (isset($_SESSION['user_email'])) {
    $userEmail = $_SESSION['user_email'];
    $query = "SELECT * FROM users WHERE email = '$userEmail'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $firstName = $row['fname'];
        $lastName = $row['lname'];
    }
}

$isUserLoggedIn = isset($_SESSION['user_email']);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Hayop Kalinga | Your Online Veterinary Booking Site</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <div class="hero">
        <nav>
			<label class="logo" href = "home.html">Hayop Kalinga</label>
			<ul>
				<li><a class="active"  href = "home.php">Home</a></li>
				<li><a class="action" href = "services.php">Services</a></li>
				<li><a class="action" href = "aboutus.php">About SUs</a></li>
				<li><a class="action" href = "contactus.php">Contact</a></li>
				<button class="login-popup" type="button" onclick="location.href = 'login.php';"> Login</button>
			</ul>
        </nav>
    </div>
</body>
</html>
