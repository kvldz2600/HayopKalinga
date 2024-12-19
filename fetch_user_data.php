<?php
include("config.php");

if (isset($_GET['user_id'])) {
    $userId = $_GET['user_id'];

    // Fetch user data from the 'users' table
    $userQuery = "SELECT * FROM users WHERE id = $userId";
    $userResult = mysqli_query($conn, $userQuery);
    $userData = mysqli_fetch_assoc($userResult);

    // Fetch pet information from the 'pet_information' table
    $petQuery = "SELECT * FROM pet_information WHERE user_id = $userId";
    $petResult = mysqli_query($conn, $petQuery);

    // Function to replace escaped newlines with <br> for line breaks
    function nl2br_custom($text) {
        // Replace '\\r\\n' with HTML <br> tags
        return str_replace(array("\\r\\n", "\\n", "\\r"), "<br>", $text);
    }

    // Display user data
    if ($userData) {
        echo "<h2>User Information</h2>";
        echo "<strong>Name:</strong> " . $userData['fname'] . " " . $userData['lname'] . "<br>";
        echo "<strong>Email:</strong> " . $userData['email'] . "<br>";
        echo "<strong>Phone Number:</strong> " . $userData['pnumb'] . "<br>";
        echo "<strong>Address:</strong> " . $userData['address'] . "<br>";

        // Display pet data
        if (mysqli_num_rows($petResult) > 0) {
            echo "<h2>Pet Information</h2>";
            while ($petData = mysqli_fetch_assoc($petResult)) {
                echo "<strong>Pet Name:</strong> " . $petData['pet_name'] . "<br>";
                echo "<strong>Type:</strong> " . $petData['type'] . "<br>";
                echo "<strong>Breed:</strong> " . $petData['breed'] . "<br>";
                echo "<strong>Age:</strong> " . $petData['age'] . "<br>";
                echo "<strong>Gender:</strong> " . $petData['gender'] . "<br>";
                echo "<strong>Medical History:</strong> " . nl2br_custom($petData['med_history']) . "<br>";
                echo "<strong>Allergies:</strong> " . nl2br_custom($petData['allergies']) . "<br>";
                echo "<strong>Current Medication:</strong> " . nl2br_custom($petData['current_medication']) . "<br>";
                echo "<strong>Vaccination Record:</strong> " . nl2br_custom($petData['vaccination_record']) . "<br>";
            }
        } else {
            echo "<p>No pet information found.</p>";
        }
    } else {
        echo "<p>User not found.</p>";
    }
}
?>