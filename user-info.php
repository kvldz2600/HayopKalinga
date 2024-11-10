<?php
session_start();
include("config.php"); // Include your database connection settings

$userEmail = $_SESSION['user_email'];

// Retrieve user information, including the plain text address
$userQuery = "SELECT id, fname, lname, address, email, pnumb FROM users WHERE email = '$userEmail'";
$userResult = mysqli_query($conn, $userQuery);

if ($userResult && mysqli_num_rows($userResult) > 0) {
    $userRow = mysqli_fetch_assoc($userResult);
    $userId = $userRow['id'];
    $firstName = $userRow['fname'];
    $lastName = $userRow['lname'];
    // Retrieve the plain text address
    $address = $userRow['address'];
    $email = $userRow['email'];
    $phone = $userRow['pnumb'];
}

// Handle the form submission to update address and other fields
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize user inputs for other fields (first name, last name, email, and phone number)
    $newfirstName = mysqli_real_escape_string($conn, $_POST['newfirstName']);
    $newlastName = mysqli_real_escape_string($conn, $_POST['newlastName']);
    $newemail = mysqli_real_escape_string($conn, $_POST['newemail']);
    $newPhone = mysqli_real_escape_string($conn, $_POST['newPhone']);
    $newAddress = mysqli_real_escape_string($conn, $_POST['newAddress']); // Include address in the form

    // Validation checks for other fields
    if (
        !empty($newfirstName) && strlen($newfirstName) >= 2 && strlen($newfirstName) <= 50 &&
        !empty($newlastName) && strlen($newlastName) >= 2 and strlen($newlastName) <= 50 &&
        !empty($newemail) && filter_var($newemail, FILTER_VALIDATE_EMAIL) &&
        !empty($newPhone) && preg_match('/^(\+639|09)[0-9]{9}$/', $newPhone) &&
        !empty($newAddress) && strlen($newAddress) >= 2 && strlen($newAddress) <= 100 // Add validation for address
    ) {
        // Update other fields in the database (including the plain text address)
        $updateUserQuery = "UPDATE users SET fname ='$newfirstName', lname = '$newlastName', email = '$newemail', pnumb = '$newPhone', address = '$newAddress' WHERE id = $userId";

        if (mysqli_query($conn, $updateUserQuery)) {
            // Update the values in the session for other fields
            $_SESSION['fname'] = $newfirstName;
            $_SESSION['lname'] = $newlastName;
            $_SESSION['email'] = $newemail;
            $_SESSION['user_phone'] = $newPhone;

            // Redirect to the next page after a successful update
            header("Location: pet-info.php");
            exit;
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    } else {
        echo "Please enter valid information.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hayop Kalinga | User Information</title>
    <link rel="stylesheet" type="text/css" href="userstyle.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    <div class="hero">
        <nav>
            <label class="logo" href="home.php">Hayop Kalinga</label>
            <img src="https://img.freepik.com/premium-vector/cute-hand-drawn-cat-paws-white-background-vector-adorable-animals-silhouette-trendy-style-funny-cute-hygge-illustration-poster-banner-print-decoration-kids-playroom_514409-1359.jpg" class="user-pic" onclick="toggleMenu()">

            <div class="sub-menu-wrap" id="subMenu">
                <div class="sub-menu">
                    <div class="user-info">
                        <img src="https://img.freepik.com/premium-vector/cute-hand-drawn-cat-paws-white-background-vector-adorable-animals-silhouette-trendy-style-funny-cute-hygge-illustration-poster-banner-print-decoration-kids-playroom_514409-1359.jpg">
                        <h3><?php echo $firstName . ' ' . $lastName; ?></h3>
                    </div>
                    <hr>
                    <a href="#" class="sub-menu-link">
                        <img src="https://icons.veryicon.com/png/o/miscellaneous/icon-library-of-x-bacteria/appointment-4.png">
                        <p>Appointments</p>
                        <span>></span>
                    </a>
                    <a href="#" class="sub-menu-link">
                        <img src="https://cdn-icons-png.flaticon.com/512/165/165823.png">
                        <p>Medical Records</p>
                        <span>></span>
                    </a>
                    <a href="#" class="sub-menu-link">
                        <img src="https://cdn-icons-png.flaticon.com/512/7689/7689822.png">
                        <p>Consultation</p>
                        <span>></span>
                    </a>
                    <a href="logout.php" class="sub-menu-link">
                        <img src="https://static-00.iconduck.com/assets.00/log-out-icon-2048x2048-cru8zabe.png">
                        <p>Logout</p>
                        <span>></span>
                    </a>
                </div>
            </div>
        </nav>
        <div class="container">
            <h1>User Information</h1>
            <hr>
            <div class="user-info">
                <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <p><strong>First Name:</strong> <input type="text" name="newfirstName" value="<?php echo $firstName; ?>"readonly></p>
                    <p><strong>Last Name:</strong> <input type="text" name="newlastName" value="<?php echo $lastName; ?>"readonly></p>
                    <p><strong>Address:</strong> <input type="text" name="newAddress" value="<?php echo $address; ?>"></p>
                    <p><strong>Email:</strong><input type="text" name="newemail" value="<?php echo $email; ?>" readonly></p>
                    <p><strong>Phone:</strong> <input type="text" name="newPhone" value="<?php echo $phone; ?>"></p>
                    <input type="submit" value="Next">
                </form>
            </div>
        </div>
    </div>
<script>
    let subMenu = document.getElementById("subMenu");

    function toggleMenu() {
        subMenu.classList.toggle("open-menu");
    }
</script>
</body>
</html>
