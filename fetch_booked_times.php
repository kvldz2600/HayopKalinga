<?php
// Include your database connection code or config
include("config.php");

// Read the selected date from the request
$data = json_decode(file_get_contents("php://input"));
$selectedDate = $data->selected_date;

// Use prepared statements to prevent SQL injection
$stmt = $conn->prepare("SELECT time FROM appointments WHERE date = ?");
$stmt->bind_param("s", $selectedDate);

// Initialize an empty array to store booked times
$bookedTimes = [];

if ($stmt->execute()) {
    $result = $stmt->get_result();

    // Fetch booked times and add them to the array
    while ($row = $result->fetch_assoc()) {
        $bookedTimes[] = $row["time"];
    }
}

// Return the list of booked times as JSON
header("Content-Type: application/json");
echo json_encode($bookedTimes);

$stmt->close();
$conn->close();
?>
