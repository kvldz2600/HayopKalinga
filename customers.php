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
    <link rel="stylesheet" href="customer.css">
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
                    <a class="action" href="dashboard.php"><span class="las la-heartbeat"></span>
                    <span>Dashboard</span></a>
                </li>
                <li>
                    <a class="active"><span class="las la-user"></span>
                    <span>Customers</span></a>
                </li>
                <li>
                    <a href="records.php"><span class="las la-user"></span>
                    <span>Records</span>
                    </a>
                </li>
                <li>
                    <a href="orders.php"><span class="las la-receipt"></span>
                    <span>Inventory Analytics</span></a>
                </li>
                <li>
                    <a href="inventory.php"><span class="las la-receipt"></span>
                    <span>Inventory</span></a>
                </li>
                <li>
                    <a  href="logout.php"><span class="las la-user"></span>
                        <span>Logout</span></a>
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
                    Customers
                </h2>
            </header>
        <main>
            <div class="table-container">
            <table>
                    <thead>
                    <tr>
                        <th>Reason for Visit</th>
                        <th>Appointment Time</th>
                        <th>Status</th>
                        <th>Pet Owner's Name</th>
                        <th>Contact</th>
                        <th>Pet Name</th>
                        <th>Pet Information</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                            include("config.php"); // Make sure "config.php" includes the database connection settings

                            // Get the current date in the format YYYY-MM-DD
                            $currentDate = date("Y-m-d");

                            // SQL query to retrieve entries for today with reason_for_visit = 'Check-up' by joining "users" and "appointments" tables
                            $sql = "SELECT
                            appointments.status AS status,
                            appointments.reason_for_visit AS rfv,
                            appointments.id_user AS apt_id,
                            appointments.time AS time,
                            CONCAT(users.fname, ' ', users.lname) AS name,
                            CONCAT(users.pnumb, ' / ', users.email) AS contact,
                            pet_information.pet_name AS pname,
                            pet_information.type AS ptype,
                            pet_information.breed AS breed,
                            pet_information.age AS age,
                            pet_information.gender AS pgender,
                            pet_information.med_history AS med_history,
                            pet_information.allergies AS allergies,
                            pet_information.current_medication AS medication,
                            pet_information.vaccination_record AS vac_rec
                        FROM
                            appointments
                        JOIN
                            users ON appointments.id_user = users.id
                        JOIN
                            pet_information ON pet_information.user_id = users.id
                        WHERE
                            DATE(appointments.date) = '$currentDate' AND
                            appointments.status != 'Service Given'
                        
                        ORDER BY
                            appointments.time ASC;
                        ";
                            // Execute the query
                            $result = $conn->query($sql);
    
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $status = $row["status"];
                                    $rfv =  $row["rfv"];
                                    $name = $row["name"];
                                    $contact = $row["contact"];
                                    $pname = $row["pname"];
                                    $time = $row["time"];
                                    $ptype = $row["ptype"];
                                    $breed = $row["breed"];
                                    $age = $row["age"];
                                    $pgender = $row["pgender"];
                                    $med_history = str_replace('\r\n', "\n", $row["med_history"]);
                                    $med_history = nl2br($med_history);
                                    $allergies = str_replace('\r\n', "\n", $row["allergies"]);
                                    $allergies = nl2br($allergies);
                                    $medication = str_replace('\r\n', "\n", $row["medication"]);
                                    $medication = nl2br($medication);
                                    $vac_rec = str_replace('\r\n', "\n", $row["vac_rec"]);
                                    $vac_rec = nl2br($vac_rec);

                                    echo '<tr>';
                                    echo '<td>' . $rfv . '</td>';
                                    echo '<td>' . $time . '</td>';
                                    echo '<td>' . $status . '</td>';
                                    echo '<td>' . $name . '</td>';
                                    echo '<td>' . $contact . '</td>';
                                    echo '<td>' . $pname . '</td>';
                                    echo '<td><button type="button" class="show_more" data-id="' . $row["apt_id"] . '">Show more</button></td>';
                                    echo '<td><button type="button" class="update" data-id="' . $row["apt_id"] . '">Update</button></td>';
                                    echo '</tr>';

                                    // Generate the modal structure with data specific to this entry
                                    echo '<div id="modalBackground_' . $row["apt_id"] . '" class="modal-background">';
                                    echo '<div id="modalContent_' . $row["apt_id"] . '" class="modal-content">';
                                    echo '<div id="modalClose_' . $row["apt_id"] . '" class="modal-close">&times;</div>';
                                    echo '<div id="modalData_' . $row["apt_id"] . '" class="modal-data">';
                                    echo 'Name: ' . $pname . '<br>';
                                    echo 'Type: ' . $ptype . '<br>';
                                    echo 'Breed: ' . $breed . '<br>';
                                    echo 'Age: ' . $age . '<br>';
                                    echo 'Gender: ' . $pgender . '<br>';
                                    echo 'Medical History: ' . $med_history . '<br>';
                                    echo 'Allergies: ' . $allergies . '<br>';
                                    echo 'Current Medication: ' . $medication . '<br>';
                                    echo 'Vaccination Records: ' . $vac_rec . '<br>';
                                    echo '</div>';
                                    echo '</div>';
                                    echo '</div>';
                                    
                                    //Generate the modal structure with update to this entry
                                    echo '<div id="modalBackgroundUpdate_' . $row["apt_id"] . '" class="modal-background">';
                                    echo '<div id="modalContentUpdate_' . $row["apt_id"] . '" class="modal-content">';
                                    echo '<div id="modalCloseUpdate_' . $row["apt_id"] . '" class="modal-close">&times;</div>';
                                    echo '<div id="modalUpdate_' . $row["apt_id"] . '" class="modal-data">';
                                    echo '<form method="POST">';
                                    echo '<input type="hidden" name="apt_id" value="' . $row["apt_id"] . '">';
                                    echo '<label name="action_update">Update Status: </label>';
                                    echo '<select name="status" required>';
                                    echo '<option value="Waiting Appointment">Waiting Appointment</option>';
                                    echo '<option value="Ongoing">Ongoing</option>';
                                    echo '<option vale="Service Given">Service Given</option>';
                                    echo '</select>';
                                    echo '<button type="submit" name="update_submit">Update</button>';
                                    echo '</form>';
                                    echo '</div>';
                                    echo '</div>';
                                    echo '</div>';

                                    if (isset($_POST['update_submit'])) {
                                        // Get the appointment ID and status from the form data
                                        $apt_id = $_POST['apt_id'];
                                        $new_status = $_POST['status'];
                                    
                                        // Construct the SQL UPDATE query
                                        $sql = "UPDATE appointments SET status = ? WHERE id_user = ?";
                                        
                                        // Prepare the SQL statement
                                        $stmt = $conn->prepare($sql);
                                    
                                        // Bind the parameters
                                        $stmt->bind_param("si", $new_status, $apt_id);
                                    
                                        // Execute the query
                                        if ($stmt->execute()) {

                                            header("Location: " . $_SERVER['PHP_SELF']);
                                        } else {
                                            // Handle the case where the update fails
                                            echo '<script>alert("Update failed: ")' .$stmt->error ;
                                            header("Location: " . $_SERVER['PHP_SELF']);
                                        }
                                        
                                        // Close the statement
                                        $stmt->close();
                                    }

                                }
                            } else {
                                echo '<tr><td colspan="8">No appointment scheduled for today.</td></tr>';
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
<script>
    document.addEventListener("DOMContentLoaded", function () {
    const showMoreButtons = document.querySelectorAll(".show_more");

    showMoreButtons.forEach(function (button) {
        button.addEventListener("click", function () {
            const appointmentId = button.getAttribute("data-id");

            // Display the specific modal content for the clicked entry
            const modalBackground = document.getElementById("modalBackground_" + appointmentId);
            const modalContent = document.getElementById("modalContent_" + appointmentId);
            const modalData = document.getElementById("modalData_" + appointmentId);

            if (modalBackground && modalContent && modalData) {
                modalBackground.style.display = "flex";
                modalContent.style.display = "flex";
            }
        });
    });

    // Close the specific modal when the close button is clicked
    showMoreButtons.forEach(function (button) {
        const appointmentId = button.getAttribute("data-id");
        const modalClose = document.getElementById("modalClose_" + appointmentId);
        if (modalClose) {
            modalClose.addEventListener("click", function () {
                const modalBackground = document.getElementById("modalBackground_" + appointmentId);
                const modalContent = document.getElementById("modalContent_" + appointmentId);

                if (modalBackground && modalContent) {
                    modalBackground.style.display = "none";
                    modalContent.style.display = "none";
                }
            });
        }
    });

    const UpdateButtons = document.querySelectorAll(".update");
    UpdateButtons.forEach(function (button) {
        button.addEventListener("click", function () {
            const appointmentId = button.getAttribute("data-id");

            // Display the specific modal content for the clicked entry
            const modalBackground = document.getElementById("modalBackgroundUpdate_" + appointmentId);
            const modalContent = document.getElementById("modalContentUpdate_" + appointmentId);
            const modalData = document.getElementById("modalUpdate_" + appointmentId);

            if (modalBackground && modalContent && modalData) {
                modalBackground.style.display = "flex";
                modalContent.style.display = "flex";
            }
        });
    });

    // Close the specific modal when the close button is clicked
    UpdateButtons.forEach(function (button) {
        const appointmentId = button.getAttribute("data-id");
        const modalClose = document.getElementById("modalCloseUpdate_" + appointmentId);
        if (modalClose) {
            modalClose.addEventListener("click", function () {
                const modalBackground = document.getElementById("modalBackgroundUpdate_" + appointmentId);
                const modalContent = document.getElementById("modalContentUpdate_" + appointmentId);

                if (modalBackground && modalContent) {
                    modalBackground.style.display = "none";
                    modalContent.style.display = "none";
                }
            });
        }
    });
});


</script>
</body>
</html>