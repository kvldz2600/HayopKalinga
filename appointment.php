<?php
session_start();
include("config.php");

$userEmail = $_SESSION['user_email'];

$userQuery = "SELECT id, fname, lname FROM users WHERE email = '$userEmail'";
$userResult = mysqli_query($conn, $userQuery);

if ($userResult && mysqli_num_rows($userResult) > 0) {
    $userRow = mysqli_fetch_assoc($userResult);
    $userId = $userRow['id'];
    $firstName = $userRow['fname'];
    $lastName = $userRow['lname'];
} else {
    // Handle the case where the user is not found
    die("User not found.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $selectedDate = $_POST['selected_date'];
    $time = $_POST['time'];
    $reason = $_POST['reason'];

    // No need to retrieve user ID again, as it's already obtained above

    // Check if the user has pets by querying the pet_information table
    $petQuery = "SELECT id FROM pet_information WHERE user_id = $userId"; // Remove quotes around $userId
    $petResult = mysqli_query($conn, $petQuery);

    if ($petResult && mysqli_num_rows($petResult) > 0) {
        // You need to fetch the pet ID from the result
        $petRow = mysqli_fetch_assoc($petResult);
        $petId = $petRow['id']; // Correct variable name

        // Use prepared statements to prevent SQL injection
        $insertQuery = "INSERT INTO appointments (id_pet, id_user, date, time, reason_for_visit) VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $insertQuery);

        if ($stmt) {
            // Bind parameters and execute the query
            mysqli_stmt_bind_param($stmt, "iisss", $petId, $userId, $selectedDate, $time, $reason);
            if (mysqli_stmt_execute($stmt)) {
                // Appointment was successfully booked
                echo "<script type='text/javascript'> alert('Appointment was successfully booked.')</script>";
            } else {
                // Appointment booking failed
                echo "Error: " . mysqli_error($conn);
            }
            mysqli_stmt_close($stmt);
        } else {
            // Query preparation failed
            echo "Error: " . mysqli_error($conn);
        }
    } else {
        // User has no pets, so appointment cannot be booked
        echo "<script type='text/javascript'> alert('No pets found.')</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="appointment.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.36/moment-timezone-with-data.min.js"></script>
    <title>Hayop Kalinga | Appointment</title>
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
            <h1 id="calendarTitle">Event Calendar</h1>
            <div class="calendar-navigation">
                <button id="prevMonth">Previous Month</button>
                <button id="nextMonth">Next Month</button>
            </div>
            <div id="calendar" class="calendar"></div>
            <div class="modal-background" id="modalBackground">
                <div id="appointment-details" class="appointment-details">
                <form method="POST" action="">
                <label name="date">Date: <span id="selectedDatePlaceholder"></span></label><br><br>
                <input type="hidden" id="selected_date" name="selected_date">
                <label name="time">Time:</label>
                <select name="time" required>
                    <option value="07:00">07:00 AM</option>
                    <option value="08:00">08:00 AM</option>
                    <option value="09:00">09:00 AM</option>
                    <option value="10:00">10:00 AM</option>
                    <option value="11:00">11:00 AM</option>
                    <option value="12:00">12:00 PM</option>
                    <option value="13:00">1:00 PM</option>
                    <option value="14:00">2:00 PM</option>
                    <option value="15:00">3:00 PM</option>
                    <option value="16:00">4:00 PM</option>
                    <option value="17:00">5:00 PM</option>
                    <option value="18:00">6:00 PM</option>
                </select><br><br>
                <label name="reason">Reason for Visitation:</label>
                <select name="reason" required>
                    <option value="Check-up">Check-up</option>
                    <option value="Grooming">Grooming</option>
                    <option value="Home Service">Home Service</option>
                    <option value="Pet Housing">Pet Housing</option>
                    <option value="Vaccination">Vaccination</option>
                </select>
                <input type="submit" value="Book Appointment">
                </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.36/moment-timezone-with-data.min.js"></script>
<script>
        let subMenu = document.getElementById("subMenu");
        let selectedDateElement;
        
        function toggleMenu() {
            subMenu.classList.toggle("open-menu");
        }

        // Your event data here
        const events = {
            "January 1": "New Year's Day",
            "Maundy Thursday": "Maundy Thursday",
            "Good Friday": "Good Friday",
            "April 9": "Araw ng Kagitingan (Day of Valor)",
            "May 1": "Labor Day",
            "June 12": "Independence Day",
            "Last Monday of August": "National Heroes Day",
            "November 1": "All Saints' Day",
            "November 30": "Bonifacio Day",
            "December 25": "Christmas Day",
            "December 30": "Rizal Day"
            // Add more events and festivals as needed
        };


        // Set the desired time zone (e.g., Asia/Manila for Philippine time)
        const timeZone = 'Asia/Manila';

        // Create a moment with the specified time zone
        const currentDate = moment().tz(timeZone);

        function handleDateClick(event) {
            if (selectedDateElement) {
                selectedDateElement.classList.remove("selected");
            }

            event.target.classList.add("selected");
            selectedDateElement = event.target;

            // Add this line to get the selected date in the specified time zone
            const selectedDate = moment(selectedDateElement.dataset.date).tz(timeZone);

            // Update the selected_date input field
            document.getElementById("selected_date").value = selectedDate.format('YYYY-MM-DD');

            // Update the date placeholder inside the appointment-details section
            document.getElementById("selectedDatePlaceholder").textContent = selectedDate.format('LL');
            showAppointmentDetails();
        }

        function renderCalendar() {
    const calendar = document.getElementById("calendar");
    const daysInMonth = moment(currentDate).endOf('month').date(); // Use moment.js here
    const calendarTitle = document.getElementById("calendarTitle");
    const now = moment(); // Use moment.js here to get the current date

    while (calendar.firstChild) {
        calendar.removeChild(calendar.firstChild);
    }

    for (let day = 1; day <= daysInMonth; day++) {
        const date = moment(currentDate).date(day);
        const dateString = date.format('YYYY-MM-DD');

        const dayElement = document.createElement("div");
        dayElement.className = "day";
        dayElement.textContent = day;
        dayElement.dataset.date = dateString;

        if (date.isBefore(now) && !date.isSame(now, 'day')) { // Use moment.js for date comparison
            dayElement.classList.add("past");
        } else {
            dayElement.addEventListener("click", handleDateClick);
        }

        if (date.isSame(now, 'day')) { // Use moment.js for date comparison
            dayElement.classList.add("current-day");
        }

        if (events[dateString]) {
            events[dateString].forEach(event => {
                const eventElement = document.createElement("div");
                eventElement.className = "event";
                eventElement.textContent = event;
                dayElement.appendChild(eventElement);
            });
        }

        calendar.appendChild(dayElement);
    }

    calendarTitle.textContent = currentDate.format('MMMM YYYY'); // Use moment.js here
}

        function showAppointmentDetails() {
            const modalBackground = document.getElementById("modalBackground");
            const details = document.getElementById("appointment-details");
            modalBackground.style.display = details.style.display = "flex";
        }

        document.addEventListener("click", function(event) {
            if (event.target.id === "modalBackground") {
                hideAppointmentDetails();
            }
        });

        function hideAppointmentDetails() {
            const modalBackground = document.getElementById("modalBackground");
            const details = document.getElementById("appointment-details");
            modalBackground.style.display = details.style.display = "none";
        }

        const prevMonthButton = document.getElementById("prevMonth");
        const nextMonthButton = document.getElementById("nextMonth");

        prevMonthButton.addEventListener("click", showPreviousMonth);
        nextMonthButton.addEventListener("click", showNextMonth);

        function showPreviousMonth() {
    if (selectedDateElement) selectedDateElement.classList.remove("selected");
    selectedDateElement = null; // Clear selectedDateElement
    currentDate.subtract(1, 'month');
    currentDate.date(1);
    clearCalendar();
    renderCalendar();
}

function showNextMonth() {
    if (selectedDateElement) selectedDateElement.classList.remove("selected");
    selectedDateElement = null; // Clear selectedDateElement
    currentDate.add(1, 'month');
    currentDate.date(1);
    clearCalendar();
    renderCalendar();
}

        function clearCalendar() {
            const calendar = document.getElementById("calendar");
            while (calendar.firstChild) {
                calendar.removeChild(calendar.firstChild);
            }
        }

        document.addEventListener("DOMContentLoaded", renderCalendar);
    </script>
</body>
</html>