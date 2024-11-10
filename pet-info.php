<?php
session_start();
include("config.php");

$userEmail = $_SESSION['user_email'];

$userQuery = "SELECT id, fname, lname, address, email, pnumb FROM users WHERE email = '$userEmail'";
$userResult = mysqli_query($conn, $userQuery);

if ($userResult && mysqli_num_rows($userResult) > 0) {
    $userRow = mysqli_fetch_assoc($userResult);
    $userId = $userRow['id'];
    $firstName = $userRow['fname'];
    $lastName = $userRow['lname'];
    $address = $userRow['address'];
    $email = $userRow['email'];
    $phone = $userRow['pnumb'];

    $petQuery = "SELECT pet_name, type, breed, age, gender, med_history, allergies, current_medication, vaccination_record FROM pet_information WHERE user_id = $userId";
    $petResult = mysqli_query($conn, $petQuery);

    if ($petResult && mysqli_num_rows($petResult) > 0) {
        $petRow = mysqli_fetch_assoc($petResult);
        $newPetName = $petRow['pet_name'];
        $newType = $petRow['type'];
        $newBreed = $petRow['breed'];
        $newPetAge = $petRow['age'];
        $newGender = $petRow['gender'];
        $newMedHis = $petRow['med_history'];
        $newAlleg = $petRow['allergies'];
        $newCurr_Med = $petRow['current_medication'];
        $newVacRec = $petRow['vaccination_record'];
    } else {
        $newPetName = '';
        $newType = '';
        $newBreed = '';
        $newPetAge = '';
        $newGender = '';
        $newMedHis = '';
        $newAlleg = '';
        $newCurr_Med = '';
        $newVacRec = '';
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newPetName = mysqli_real_escape_string($conn, $_POST['newPetName']);
    $newType = mysqli_real_escape_string($conn, $_POST['newType']);
    $newBreed = mysqli_real_escape_string($conn, $_POST['newBreed']);
    $newGender = isset($_POST['newGender']) ? mysqli_real_escape_string($conn, $_POST['newGender']) : '';
    $newPetAge = mysqli_real_escape_string($conn, $_POST['newPetAge']);
    $newMedHis = mysqli_real_escape_string($conn, $_POST['newMedHis']);
    $newAlleg = mysqli_real_escape_string($conn, $_POST['newAlleg']);
    $newCurr_Med = mysqli_real_escape_string($conn, $_POST['newCurr_Med']);
    $newVacRec = mysqli_real_escape_string($conn, $_POST['newVacRec']);

    // Check if a record exists for the user
    $petQuery = "SELECT user_id FROM pet_information WHERE user_id = $userId";
    $petResult = mysqli_query($conn, $petQuery);

    if ($petResult && mysqli_num_rows($petResult) > 0) {
        // A record exists, perform an update
        if ($row['pet_name'] === $petName) {
        $updatePetQuery = "UPDATE pet_information SET pet_name = ?, type = ?, breed = ?, age = ?, gender = ?, med_history = ?, allergies = ?, current_medication = ?, vaccination_record = ? WHERE user_id = ?";
        $stmt = mysqli_prepare($conn, $updatePetQuery);
        } else {
            $insertPetQuery = "INSERT INTO pet_information (user_id, pet_name, type, breed, age, gender, med_history, allergies, current_medication, vaccination_record) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?)";
            $stmt = mysqli_prepare($conn, $insertPetQuery);
        }
    } else {
        // No record exists, perform an insert
        $insertPetQuery = "INSERT INTO pet_information (user_id, pet_name, type, breed, age, gender, med_history, allergies, current_medication, vaccination_record) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $insertPetQuery);
    }

    if ($stmt) {
        if (isset($petResult) && mysqli_num_rows($petResult) > 0) {
            mysqli_stmt_bind_param($stmt, "sssssssssi", $newPetName, $newType, $newBreed, $newPetAge, $newGender, $newMedHis, $newAlleg, $newCurr_Med, $newVacRec, $userId);
        } else {
            mysqli_stmt_bind_param($stmt, "isssssssss", $userId, $newPetName, $newType, $newBreed, $newPetAge, $newGender, $newMedHis, $newAlleg, $newCurr_Med, $newVacRec);
        }

        if (mysqli_stmt_execute($stmt)) {
            header("Location: appointment.php");
        } else {
            echo "Error updating/inserting data: " . mysqli_stmt_error($stmt);
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "Error preparing statement: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hayop Kalinga | Pet Information</title>
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
            <h1>Pet Information</h1>
            <div class="user-info">
                <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
                    <p>
                        <strong>Pet Name:</strong>
                        <input type="text" name="newPetName" placeholder="Name of pet" value="<?php echo $newPetName; ?>">
                    </p>
                    
                    <p>
                        <strong>Type:</strong>
                        <input type="text" name="newType" placeholder="Type of pet" value="<?php echo $newType; ?>">
                    </p>
                    <p>
                        <strong>Breed:</strong>
                        <input type="text" name="newBreed" placeholder="Breed of pet" value="<?php echo $newBreed; ?>">
                    </p>
                    <p>
                        <strong>Age:</strong>
                        <input type="text" name="newPetAge" placeholder="Age of pet" value="<?php echo $newPetAge; ?>">
                    </p>
                    <p>
                        <div class="radio-group">
                        <strong>Gender:</strong>
                            <label><input type="radio" name="newGender" value="male" <?php if ($newGender === 'male') echo 'checked'; ?>> Male</label>
                            <label><input type="radio" name="newGender" value="female" <?php if ($newGender === 'female') echo 'checked'; ?>> Female</label>
                            <label><input type="radio" name="newGender" value="neutered_spayed" <?php if ($newGender === 'neutered_spayed') echo 'checked'; ?>> Neutered / Spayed</label>
                        </div>
                    </p>
                    <div class="textarea-wrapper">
                        <label for="newVacRec">Vaccination Record:</label>
                        <textarea name="newVacRec" id="newVacRec" placeholder="   - Date: [Date]
   - Veterinarian: [Veterinarian's Name]
   - Batch Number: [Batch Number]
   - Next Due Date: [Next Due Date]"><?php echo $newVacRec; ?></textarea>
                    </div>
                    <div class="textarea-wrapper">
                        <label for="newMedHis">Medical History:</label>
                        <textarea name="newMedHis" id="newMedHis" placeholder="Date: [Date]
   - Reason for Visit: [Reason for Visit]
   - Veterinarian: [Veterinarian's Name]
   - Diagnosis: [Diagnosis]
   - Treatment: [Treatment]
   - Medications Prescribed: [Medications]
   - Follow-up Instructions: [Follow-up Instructions]"><?php echo $newMedHis; ?></textarea>
                    </div>
                    <div class="textarea-wrapper">
                        <label for="newAlleg">Allergies:</label>
                        <textarea name="newAlleg" id="newAlleg" placeholder="Allergen
   - Reaction: [Reaction or Symptoms]
   - Severity: [Mild / Moderate / Severe]
   - Date of Allergic Reaction: [Date of Reaction]
   - Treatment: [Treatment Given]"><?php echo $newAlleg; ?></textarea>
                    </div>
                    <div class="textarea-wrapper">
                        <label for="newCurr_Med">Current Medication:</label>
                        <textarea name="newCurr_Med" id="newCurr_Med" placeholder="Medication
   - Dosage: [Dosage]
   - Frequency: [Frequency]
   - Start Date: [Start Date]
   - End Date (if applicable): [End Date]
   - Purpose/Prescribed for: [Reason for Medication]"><?php echo $newCurr_Med; ?></textarea>
                    </div>
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
