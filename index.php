<?php
// Database connection
$conn = new mysqli('10.0.0.4', 'vm1_user', 'your_password', 'asset_management');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Section 1: Store asset info
if (isset($_POST['submit'])) {
    $asset_tag = $_POST['asset_tag'];
    $serial_number = $_POST['serial_number'];
    $device_type = $_POST['device_type'];
    $make = $_POST['make'];
    $year = $_POST['year'];
    $model = $_POST['model'];
    $location = $_POST['location'];

    $sql = "INSERT INTO assets (asset_tag, serial_number, device_type, make, year, model, location) 
            VALUES ('$asset_tag', '$serial_number', '$device_type', '$make', '$year', '$model', '$location')";

    if ($conn->query($sql) === TRUE) {
        echo "Record stored successfully!";
    } else {
        echo "Error: " . $conn->error;
    }
}

// Section 2: Search asset info
if (isset($_POST['search'])) {
    $search_term = $_POST['search_term'];
    $search_type = $_POST['search_type'];

    if ($search_type == 'asset_tag') {
        $sql = "SELECT * FROM assets WHERE asset_tag='$search_term'";
    } else {
        $sql = "SELECT * FROM assets WHERE serial_number='$search_term'";
    }

    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "Asset Tag: " . $row["asset_tag"]. " - S/N: " . $row["serial_number"]. " - Type: " . $row["device_type"]. " - Make: " . $row["make"]. " - Year: " . $row["year"]. " - Model: " . $row["model"]. " - Location: " . $row["location"]. "<br>";
        }
    } else {
        echo "No results found!";
    }
}
?>

<!-- HTML Form for asset input -->
<h2>Enter Asset Information</h2>
<form method="POST">
    Asset Tag: <input type="text" name="asset_tag" required><br>
    Serial Number: <input type="text" name="serial_number" required><br>
    Device Type: <input type="text" name="device_type" required><br>
    Make: <input type="text" name="make" required><br>
    Year: <input type="number" name="year" required><br>
    Model: <input type="text" name="model" required><br>
    Location: <input type="text" name="location" required><br>
    <input type="submit" name="submit" value="Submit">
</form>

<!-- HTML Form for asset search -->
<h2>Search Asset Information</h2>
<form method="POST">
    Search by: 
    <select name="search_type">
        <option value="asset_tag">Asset Tag</option>
        <option value="serial_number">Serial Number</option>
    </select><br>
    Search Term: <input type="text" name="search_term" required><br>
    <input type="submit" name="search" value="Search">
</form>
