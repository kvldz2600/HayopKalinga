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
    <div class="hero" id = 'home'>
        <nav>
			<label class="logo" href = "#home">Hayop Kalinga</label>
			<ul>
				<li><a class="active"  href = "#home">Home</a></li>
				<li><a class="action" href = "#services">Services</a></li>
				<li><a class="action" href = "#aboutus">About Us</a></li>
				<li><a class="action" href = "#contactus">Contact Us</a></li>
			</ul>
        </nav>
    </div>
<!-- Page links -->
</body>
<div class="login"id = 'login'>
    <?php include 'login.php';?>
</div>
<div class="services"id = 'services'>
    <?php include 'services.php';?>
</div>
<div class="aboutus"id = 'aboutus'>
    <?php include 'aboutus.php'?>
</div>
<div class= "contactus" id = 'contactus'>
    <?php include 'contactus.php'?>
</div>    
</html>
