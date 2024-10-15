<?php
// Database connection details
$servername = "vm3db";
$username = "gabriellu";
$password = "Kelly@ite001~~";
$dbname = "asset_management";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$feedback = '';
$searchResult = '';

// Handle form submission to insert asset data
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $asset_tag = $_POST['asset_tag'];
    $serial_number = $_POST['serial_number'];
    $device_type = $_POST['device_type'];
    $make = $_POST['make'];
    $year = $_POST['year'];
    $model = $_POST['model'];
    $location = $_POST['location'];

    // SQL query to insert data
    $sql = "INSERT INTO assets (asset_tag, serial_number, device_type, make, year, model, location)
            VALUES ('$asset_tag', '$serial_number', '$device_type', '$make', '$year', '$model', '$location')";

    if ($conn->query($sql) === TRUE) {
        $feedback = "Asset information stored successfully.";
    } else {
        $feedback = "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Handle search query
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search'])) {
    $search_value = $_POST['search_value'];
    $search_type = $_POST['search_type'];

    // Determine search query based on user choice (asset_tag or serial_number)
    if ($search_type == "asset_tag") {
        $sql = "SELECT * FROM assets WHERE asset_tag = '$search_value'";
    } else {
        $sql = "SELECT * FROM assets WHERE serial_number = '$search_value'";
    }

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Output search result
        while ($row = $result->fetch_assoc()) {
            $searchResult .= "Asset Tag: " . $row["asset_tag"] . "<br>" .
                             "Serial Number: " . $row["serial_number"] . "<br>" .
                             "Device Type: " . $row["device_type"] . "<br>" .
                             "Make: " . $row["make"] . "<br>" .
                             "Year: " . $row["year"] . "<br>" .
                             "Model: " . $row["model"] . "<br>" .
                             "Location: " . $row["location"] . "<br><br>";
        }
    } else {
        $searchResult = "No results found.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asset Management</title>
    <style>
        body {
            background-image: url('https://midtermstorage.blob.core.windows.net/midtermcontainer/website/wallpaper.jpg');
            background-size: cover; /* Make the image cover the entire background */
            background-position: center; /* Center the image */
            color: white; /* Change text color for better visibility */
            font-family: Arial, sans-serif; /* Font style */
        }

        h1, h2 {
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.7); /* Add a shadow for readability */
        }

        form {
            background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent background for form */
            padding: 20px; /* Padding for form */
            border-radius: 5px; /* Rounded corners */
            margin-bottom: 20px; /* Space below the form */
        }
    </style>
</head>
<body>

<h1>Asset Management System</h1>

<h2>Section 1: Add Asset Information</h2>
<form method="post" action="">
    Asset Tag (Primary Key): <input type="text" name="asset_tag" required><br>
    Serial Number: <input type="text" name="serial_number" required><br>
    Device Type: <input type="text" name="device_type" required><br>
    Make: <input type="text" name="make" required><br>
    Year: <input type="text" name="year" required><br>
    Model: <input type="text" name="model" required><br>
    Location: <input type="text" name="location" required><br>
    <input type="submit" name="submit" value="Submit">
</form>

<p><?php echo $feedback; ?></p>

<hr>

<h2>Section 2: Search Asset Information</h2>
<form method="post" action="">
    Search by: 
    <select name="search_type">
        <option value="asset_tag">Asset Tag</option>
        <option value="serial_number">Serial Number</option>
    </select><br>
    Enter Value: <input type="text" name="search_value" required><br>
    <input type="submit" name="search" value="Search">
</form>

<p><?php echo $searchResult; ?></p>

</body>
</html>
