<?php
session_start();
include("config.php");

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $firstname = $_POST['fname'];
    $lastname = $_POST['lname'];
    $address = $_POST['address'];
    $num = $_POST['pnumb'];
    $gmail = $_POST['email'];
    $pass = $_POST['password'];
    $confirmPass = $_POST['confirm_password'];
    $hashedPassword = password_hash($pass, PASSWORD_DEFAULT);

    // Validation checks
    if (
        !empty($firstname) && strlen($firstname) >= 2 && strlen($firstname) <= 50 &&
        !empty($lastname) && strlen($lastname) >= 2 && strlen($lastname) <= 50 &&
        !empty($address) && strlen($address) >= 2 && strlen($address) <= 100 &&
        !empty($num) && preg_match('/^(\+639|09)[0-9]{9}$/', $num) &&
        !empty($gmail) && filter_var($gmail, FILTER_VALIDATE_EMAIL) &&
        !empty($pass) && !is_numeric($gmail) && $pass === $confirmPass
    ) {
        // Create a prepared statement with placeholders
        $query = "INSERT INTO users (fname, lname, address, pnumb, email, password) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $query);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ssssss", $firstname, $lastname, $address, $num, $gmail, $hashedPassword);

            if (mysqli_stmt_execute($stmt)) {
                echo "<script type='text/javascript'> alert('Registration Successful!')</script>";
            } else {
                echo "<script type='text/javascript'> alert('Error: Registration failed.')</script>";
            }

            mysqli_stmt_close($stmt);
        } else {
            echo "<script type='text/javascript'> alert('Error: Database query preparation failed.')</script>";
        }
    } else {
        echo "<script type='text/javascript'> alert('Please enter valid information and make sure your passwords match.')</script>";
    }

    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Hayop Kalinga | Your Online Venterinary Booking Site </title>
		<link rel="stylesheet" type="text/css" href="signupStyle.css">
	</head>
	<body>
		<nav>
			<label class="logo">Hayop Kalinga</label>
			<ul>
				<li><a class="action" href = "home.php">Home</a></li>
				<li><a class="action" href = "services.php">Services</a></li>
				<li><a class="action" href = "aboutus.php">About Us</a></li>
				<li><a class="action" href = "contactus.php">Contact</a></li>
			</ul>
		</nav>
		
		<div class="signupbox">
			<h1>Sign Up</h1>
			<form method="POST">
				<label>First Name</label>
				<input type="text" name="fname" placeholder="">
				<label>Last Name</label>
				<input type="text" name="lname" placeholder="">
				<label>Address</label>
				<input type="text" name="address" placeholder="">
				<label>Phone Number</label>
				<input type="tel" name="pnumb" placeholder="" pattern="(\+63|0)[0-9]{10}" required>
				<label>Email Address</label>
				<input type="email" name="email" placeholder="" required>
				<label>Password</label>
				<input type="password" name="password" placeholder="" required>
				<label>Confirm Password</label>
				<input type="password" name="confirm_password" placeholder="" required>
				<input type="submit" value="Submit" style="	width:100%;height: 50px;margin-top: 20px;border: none;background-color:#0047AB;color: white;font-size: 18px;">
			<p> By clicking the Sign Up button, you agree to our <br>
			<a class= "underline-animation" href="#">Terms and Conditions</a> and <a class= "underline-animation" href="#">Policy Privacy</a>
			<br>
		</div>
	</body>
</html>