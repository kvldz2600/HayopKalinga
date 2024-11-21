<<<<<<< HEAD
<?php
include("config.php");

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
=======
>>>>>>> 80320a4b7a0abaf37b3ac13927669a5ca99b890d
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Hayop Kalinga | Login </title>
		<link rel="stylesheet" type="text/css" href="loginstyle.css">
	</head>
	<body>
		<div class="lgn">
			<div class="login-form">
				<h2>Login</h2>
				<form action="#" method="POST">
					<div class="input-box">
					<label>Email</label>
					<span class ="icon"></span>
					<input type ="email" name="email" required>
					</div>
					<div class="input-box">
					<label>Password</label>
					<span class ="icon"></span>
					<input type ="password" name="password" required>
					
					</div>
					<div class="remember-forgot">
						<a class="underline-animation" href="#"> Forgot Password?</a>
					</div>
					<div class="login-register">
					<p>Don't have an account? <a href="SignUp.php" class="underline-animation">Register</a></p>
					</div>
				</form>
			</div>
		</div>
	</body>
</html>