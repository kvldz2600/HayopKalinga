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

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $gmail = $_POST['email'];
    $pass = $_POST['password'];

    // Fetch the hashed password from the database
    $query = "SELECT password FROM users WHERE email = ? LIMIT 1";
    $stmt = mysqli_prepare($conn, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $gmail);
        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);
            $row = mysqli_fetch_assoc($result);

            if ($row) {
                $hashedPasswordFromDatabase = $row['password'];

                // Check if the user is the admin based on email and password
                if ($gmail === "admin.hayopkalinga@gmail.com" && password_verify($pass, $hashedPasswordFromDatabase)) {
                    // User entered the admin email and the correct admin password
                    $_SESSION['user_email'] = $gmail;
                    header("location: dashboard.php");
                    exit;
                } elseif (password_verify($pass, $hashedPasswordFromDatabase)) {
                    // User is a regular user, proceed with login
                    $_SESSION['user_email'] = $gmail;
                    header("location: user-info.php");
                    exit;
                } else {
                    echo "<script type='text/javascript'> alert('Wrong Email or Password.')</script>";
                }
            } else {
                echo "<script type='text/javascript'> alert('Unregistered Email.')</script>";
            }
        } else {
            echo "<script type='text/javascript'> alert('Error: Database query failed.')</script>";
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "<script type='text/javascript'> alert('Error: Database query preparation failed.')</script>";
    }
    mysqli_close($conn);
}
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
				<li><a class="action" href = "#contactus">Contact Us</a><li>
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
