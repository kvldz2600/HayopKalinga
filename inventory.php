<?php
session_start();
include("config.php");
date_default_timezone_set('Asia/Manila');

// Check if the user is logged in
if (isset($_SESSION['user_email']) && $_SESSION['user_email'] == "admin.hayopkalinga@gmail.com") {
    // The user has the correct email, continue with your code.
} else {
    // The user doesn't have the correct email
    echo '<script>alert("You do not have administrator privileges.");</script>';
    header("Location: home.php");
    exit();
}

// Calculate the current date
$today = date("Y-m-d");

// Update the freshness column for items meeting the expiration criteria
$sqlUpdateFreshness = "UPDATE inventory SET freshness = CASE
    WHEN DATEDIFF(exp_date, '$today') <= 90 THEN 'Near Expiration'
    ELSE freshness
    END";

// Execute the update query
if ($conn->query($sqlUpdateFreshness) === TRUE) {
    // Update was successful
} else {
    echo "Error updating freshness: " . $conn->error;
}

// Code to update freshness of expired items
$sqlUpdateExpired = "UPDATE inventory SET freshness = 'Expired' WHERE exp_date < '$today'";
if ($conn->query($sqlUpdateExpired) === TRUE) {
    // Update was successful
} else {
    echo "Error removing expired items: " . $conn->error;
}

// Query to get items with exp_date within 90 days
$sqlExpAlert = "SELECT name, exp_date, freshness FROM inventory WHERE DATEDIFF(exp_date, '$today') <= 90 AND freshness != 'Expired'";
$resultExpAlert = $conn->query($sqlExpAlert);

// Query to get items with quantity below 50
$sqlStockAlert = "SELECT name, quantity, freshness FROM inventory WHERE quantity <= 200 AND freshness != 'Expired'";
$resultStockAlert = $conn->query($sqlStockAlert);

echo '<script>';
echo 'var today = "' . $today . '";';
echo 'var resultExpAlert = ' . json_encode(fetchData($resultExpAlert)) . ';';
echo 'var resultStockAlert = ' . json_encode(fetchData($resultStockAlert)) . ';';
echo 'var alertsShown = ' . json_encode(isset($_SESSION['alertsShown'])) . ';';
echo '</script>';

function fetchData($result) {
    $data = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }
    return $data;
}
?>

<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hayop Kalinga | Administrator Page</title>
    <link rel="stylesheet" href="inventory.css">
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
                    <a href="dashboard.php"><span class="las la-heartbeat"></span>
                    <span>Dashboard</span></a>
                </li>
                <li>
                    <a href="customers.php"><span class="las la-user"></span>
                    <span>Customers</span></a>
                </li>
                <li>
                    <a href="diagnosis.php"><span class="las la-file-medical"></span>
                    <span>Diagnosis</span></a>
                </li>
                <li>
                    <a href="orders.php"><span class="las la-receipt"></span>
                    <span>Inventory Analytics</span></a>
                </li>
                <li>
                    <a class="active"><span class="las la-receipt"></span>
                    <span>Inventory</span></a>
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
                    Inventory
                </h2>
            </header>
        <main>
            <header>
                <input type="text" id="search" placeholder="Search">
                <button id="searchButton">Search</button>
                <button id="addButton">Add Item</button>
            </header>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Quantity Left</th>
                    <th>Expiration Date</th>
                    <th>Item Price: ₱</th>
                    <th>Edit item</th>
                    <th>Remove Item</th>
                </tr>
                <tbody>
                    <?php
                    $sql = "SELECT
                    inventory.id AS id,
                    inventory.name AS name,
                    inventory.quantity AS quantity,
                    inventory.exp_date AS exp_date,
                    inventory.price AS price,
                    inventory.freshness AS freshness
                FROM
                    inventory
                ORDER BY
                    inventory.exp_date ASC;
                ";

                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            if ($row["freshness"] !== "Expired") {
                            $id = $row["id"];
                            $name = $row["name"];
                            $quantity = $row["quantity"];
                            $exp_date = $row["exp_date"];
                            $price = $row["price"];

                            echo '<tr>';
                            echo '<td>' . $id . '</td>';
                            echo '<td>' . $name . '</td>';
                            echo '<td>' . $quantity . '</td>';
                            echo '<td>' . $exp_date . '</td>';
                            echo '<td>' . $price . '</td>';
                            echo '<td><button type="button" class="editButton" data-id="' . $row["id"] . '">Edit</button></td>';
                            echo '<td><button type="button" class="removeButton" data-id="' . $row["id"] . '">Remove</button></td>';
                            echo '</tr>';
                            
                            //Generate the modal structure with Edit to this entry
                            echo '<div id="modalBackgroundEdit_' . $row["id"] . '" class="modal-background">';
                            echo '<div id="modalContentEdit_' . $row["id"] . '" class="modal-content">';
                            echo '<div id="modalCloseEdit_' . $row["id"] . '" class="modal-close">&times;</div>';
                            echo '<div id="modalEdit_' . $row["id"] . '" class="modal-data">';
                            echo '<label for="action_update">Edit Item: </label>';
                            echo '<br>';
                            echo '<form method="POST">';
                            echo '<input type="hidden" name="id" value="' . $row["id"] . '">';
                            echo '<label for="newName">Name: </label>';
                            echo '<input type="text" name="newName" value="' . $row["name"] . '" readonly>';
                            echo '<label for="newQuantity">Quantity: </label>';
                            echo '<input type="text" name="newQuantity" value="' . $row["quantity"] . '" required>';
                            echo '<label for="newExpDate">Expiration Date: </label>';
                            echo '<input type="text" name="newExpDate" value="' . $row["exp_date"] . '" readonly>';
                            echo '<label for="newPrice">Price: </label>';
                            echo '<input type="text" name="newPrice" value="' . $row["price"] . '" required>';
                            echo '<br>';
                            echo '<button type="submit" name="update_submit">Update</button>';
                            echo '</form>';
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';

                            //Generate the modal structure with Remove to this entry
                            echo '<div id="modalBackgroundRemove_' . $row["id"] . '" class="modal-background">';
                            echo '<div id="modalContentRemove_' . $row["id"] . '" class="modal-content">';
                            echo '<div id="modalCloseRemove_' . $row["id"] . '" class="modal-close">&times;</div>';
                            echo '<div id="modalRemove_' . $row["id"] . '" class="modal-data">';
                            echo '<form method="POST">';
                            echo '<input type="hidden" name="id" value="' . $row["id"] . '">';
                            echo '<label for="action_remove">Remove Item: </label>';
                            echo '<label for="newName">Name: </label>';
                            echo '<input type="text" name="newName" value="' . $row["name"] . '" readonly>';
                            echo '<label for="newQuantity">Quantity: </label>';
                            echo '<input type="text" name="newQuantity" value="' . $row["quantity"] . '"readonly>';
                            echo '<label for="newExpDate">Expiration Date: </label>';
                            echo '<input type="text" name="newExpDate" value="' . $row["exp_date"] . '" readonly>';
                            echo '<label for="newPrice">Price: </label>';
                            echo '<input type="text" name="newPrice" value="' . $row["price"] . '"readonly>';
                            echo '<br><br>';
                            echo 'Are you sure you want to remove this?';
                            echo '<button type="submit" name="removeButtons" data-id="' . $row["id"] . '">Remove</button>';
                            echo '<button type="button" name="cancelButtons" data-id="' . $row["id"] . '">Cancel</button>';
                            echo '</form>';
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                            }
                        }
                    } else {
                        echo '<tr><td colspan="8">No Inventory item retrieved from database.</td></tr>';
                    }

                    if (isset($_POST['removeButtons'])) {
                        // Get the appointment ID and status from the form data
                        $id = $_POST['id'];
                    
                        // Construct the SQL UPDATE query
                        $sql = "DELETE FROM inventory WHERE id = ?";
                        
                        // Prepare the SQL statement
                        $stmt = $conn->prepare($sql);
                    
                        // Bind the parameters
                        $stmt->bind_param("i", $id);
                    
                        // Execute the query
                        if ($stmt->execute()) {
                            echo '<meta http-equiv="refresh" content="0">';
                            exit();
                        } else {
                            // Handle the case where the update fails
                            echo '<script>alert("Removal failed: ' . $stmt->error . '")</script>';
                            header("Location: " . $_SERVER['PHP_SELF']);
                        }
                        // Close the statement
                        $stmt->close();
                    }

                    if (isset($_POST['update_submit'])) {
                        // Get the item ID and data from the form
                        $id = $_POST['id'];
                        $newName = $_POST['newName'];
                        $newQuantity = $_POST['newQuantity'];
                        $newExpDate = $_POST['newExpDate'];
                        $newPrice = $_POST['newPrice'];
                    
                        // Construct the SQL UPDATE query
                        $sql = "UPDATE inventory SET name = ?, quantity = ?, exp_date = ? , price = ? WHERE id = ?";
                    
                        // Prepare the SQL statement
                        $stmt = $conn->prepare($sql);
                    
                        // Bind the parameters
                        $stmt->bind_param("sdssi", $newName, $newQuantity, $newExpDate, $newPrice, $id);
                    
                        // Execute the query
                        if ($stmt->execute()) {
                            echo '<meta http-equiv="refresh" content="0">';
                            exit();
                        } else {
                            // Handle the case where the update fails
                            echo '<script>alert("Update failed: ' . $stmt->error . '")</script>';
                            header("Location: " . $_SERVER['PHP_SELF']);
                        }
                    
                        // Close the statement
                        $stmt->close();
                    }
                    
                    ?>
                </tbody>
            </table>
            <div id="modalBackgroundAdd_" class="modal-background">
                <div id="modalContentAdd_" class="modal-content">
                    <div id="modalCloseAdd_" class="modal-close">&times;</div>
                    <div id="modalAdd_" class="modal-data">
                    <label>Add Item:</label>
                        <form method="POST" id="addForm">
                            <label for="newName">Item Name:</label>
                            <input type="text" name="newName" id="newName" value="" required>
                            <label for="newQuantity">Item Quantity:</label>
                            <input type="text" name="newQuantity" id="newQuantity" value="" required>
                            <label for="newExpDate">Item Expiration Date:</label>
                            <input type="date" name="newExpDate" id="newExpDate" value="<?php echo date('Y-m-d'); ?>" required>
                            <label for="newPrice">Item Price: ₱</label>
                            <input type="text" name="newPrice" id="newPrice" value="" required>
                            <button type="submit" name="add_submit">Add Item</button>
                            <button type="button" id="cancelButton" name="cancelButton">Cancel</button>
                        </form>
                    </div>
                </div>
            </div>
            <?php
                if (isset($_POST['add_submit'])) {
                    // Get the datas from the form data
                    $newName = $_POST['newName'];
                    $newQuantity = $_POST['newQuantity'];
                    $newExpDate = $_POST['newExpDate'];
                    $newPrice = $_POST['newPrice']; 
                    
                    // Construct the SQL UPDATE query
                    $sql = "INSERT INTO inventory (name, quantity, exp_date, price) VALUES (?, ?, STR_TO_DATE(?, '%Y-%m-%d'), ?)";
                    
                    // Prepare the SQL statement
                    $stmt = $conn->prepare($sql);
                
                    // Bind the parameters
                    $stmt->bind_param("sdsd", $newName, $newQuantity, $newExpDate, $newPrice);
                
                    // Execute the query
                    if ($stmt->execute()) {
                        echo '<meta http-equiv="refresh" content="0">';
                        exit();
                    } else {
                        // Handle the case where the update fails
                        echo '<script>alert("Insertion failed: ' . $stmt->error . '")</script>';
                        header("Location: " . $_SERVER['PHP_SELF']);
                    }
                    // Close the statement
                    $stmt->close();
                }

            ?>
        </main>
    </div>
    <script>
    const searchButton = document.getElementById('searchButton');
    const addButton = document.getElementById('addButton');
    const cancelButton = document.getElementById('cancelButton');
    const editButtons = document.querySelectorAll(".editButton");
    const removeButtons = document.querySelectorAll(".removeButton");
    const cancelButtons = document.querySelectorAll(".cancelButton");

    editButtons.forEach(function (button) {
        button.addEventListener("click", function () {
            const inventoryId = button.getAttribute("data-id");

            // Display the specific modal content for the clicked entry
            const modalBackgroundEdit = document.getElementById("modalBackgroundEdit_" + inventoryId);
            const modalContentEdit = document.getElementById("modalContentEdit_" + inventoryId);

            if (modalBackgroundEdit && modalContentEdit) {
                modalBackgroundEdit.style.display = "block";
                modalContentEdit.style.display = "block";
            }
        });
    });

    removeButtons.forEach(function (button) {
        button.addEventListener("click", function () {
            const inventoryId = button.getAttribute("data-id");

            // Display the specific modal content for the clicked entry
            const modalBackgroundRemove = document.getElementById("modalBackgroundRemove_" + inventoryId);
            const modalContentRemove = document.getElementById("modalContentRemove_" + inventoryId);

            if (modalBackgroundRemove && modalContentRemove) {
                modalBackgroundRemove.style.display = "block";
                modalContentRemove.style.display = "block";
            }
        });
    });

    addButton.addEventListener("click", function () {
        // Display the add modal
        const modalBackgroundAdd = document.getElementById("modalBackgroundAdd_");
        const modalContentAdd = document.getElementById("modalContentAdd_");

        if (modalBackgroundAdd && modalContentAdd) {
            modalBackgroundAdd.style.display = "block";
            modalContentAdd.style.display = "block";
        }
    });

    // Close modal event listeners
    editButtons.forEach(function (button) {
    const inventoryId = button.getAttribute("data-id");
    const modalClose = document.getElementById("modalCloseEdit_" + inventoryId);
    if (modalClose) {
        modalClose.addEventListener("click", function () {
            const modalBackgroundEdit = document.getElementById("modalBackgroundEdit_" + inventoryId);
            const modalContentEdit = document.getElementById("modalContentEdit_" + inventoryId);

            if (modalBackgroundEdit && modalContentEdit) {
                modalBackgroundEdit.style.display = "none";
                modalContentEdit.style.display = "none"; 
            }
            });
        }
    });

    removeButtons.forEach(function (button) {
        const inventoryId = button.getAttribute("data-id");
        const modalCloseRemove = document.getElementById("modalCloseRemove_" + inventoryId);
        if (modalCloseRemove) {
            modalCloseRemove.addEventListener("click", function () {
                const modalBackgroundRemove = document.getElementById("modalBackgroundRemove_" + inventoryId);
                const modalContentRemove = document.getElementById("modalContentRemove_" + inventoryId);

                if (modalBackgroundRemove && modalContentRemove) {
                    modalBackgroundRemove.style.display = "none";
                    modalContentRemove.style.display = "none";
                }
            });
        }
    });

    const modalCloseAdd = document.getElementById("modalCloseAdd_");
    if (modalCloseAdd) {
        modalCloseAdd.addEventListener("click", function () {
            const modalBackgroundAdd = document.getElementById("modalBackgroundAdd_");
            const modalContentAdd = document.getElementById("modalContentAdd_");

            if (modalBackgroundAdd && modalContentAdd) {
                modalBackgroundAdd.style.display = "none";
                modalContentAdd.style.display = "none";
            }
        });
    };

    cancelButtons.forEach(function (button) {
    button.addEventListener("click", function () {
        // Handle the "Cancel" button click event for each row
        const inventoryId = button.getAttribute("data-id");

        // Here, you can close the specific modal for this row
        const modalBackgroundCancel = document.getElementById("modalBackgroundCancel_" + inventoryId);
        const modalContentCancel = document.getElementById("modalContentCancel_" + inventoryId);

        if (modalBackgroundCancel && modalContentCancel) {
            modalBackgroundCancel.style.display = "none";
            modalContentCancel.style.display = "none";
        }
    });
});

    cancelButton.addEventListener("click", function () {
    // Handle the cancel button click event here
    const modalBackgroundAdd = document.getElementById("modalBackgroundAdd_");
    const modalContentAdd = document.getElementById("modalContentAdd_");

    if (modalBackgroundAdd && modalContentAdd) {
        modalBackgroundAdd.style.display = "none";
        modalContentAdd.style.display = "none";
    }
    });

    // Function to check and display alerts for items with exp_date within 90 days
    function checkExpDateAlert() {
    console.log('Checking expiration date alerts...');
    console.log('alertsShown:', alertsShown);
    console.log('resultExpAlert:', resultExpAlert);
    
    var alertsShown = false;

    if (!alertsShown) {
        if (resultExpAlert.length > 0) {
            resultExpAlert.forEach(function (row) {
                var name = row.name;
                var exp_date = row.exp_date;
                var daysRemaining = (new Date(exp_date) - new Date(today)) / (24 * 60 * 60 * 1000); // Calculate days remaining
                alert('Alert: ' + name + ' expires in ' + daysRemaining + ' days on ' + exp_date + '.');
            });
            alertsShown = true;
        }
    }
}


// Function to check and display alerts for items with quantity below 200
function checkStockAlert() {
    if (resultStockAlert.length > 0) {
        resultStockAlert.forEach(function (row) {
            var name = row.name;
            var quantity = row.quantity;
            alert('Alert: ' + name + ' is in low stock (' + quantity + '). Please restock!');
        });
    }
}

checkExpDateAlert();
checkStockAlert();

        // Check and display alerts once a day
        setInterval(function () {
            checkExpDateAlert();
            checkStockAlert();
        }, 24 * 60 * 60 * 1000); // 24 hours
</script>
</body>
</html>