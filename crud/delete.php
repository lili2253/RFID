<?php
include "db_conn.php";

// Check if 'rfid_uid' is set in the URL
if (isset($_GET["id"])) {
  $rfid_uid = $_GET["id"];

    // Sanitize the rfid_uid
    $rfid_uid = mysqli_real_escape_string($conn, $rfid_uid);

    // Prepare the SQL statement to prevent SQL injection
    $stmt = $conn->prepare("DELETE FROM students WHERE rfid_uid = ?");
    $stmt->bind_param("s", $rfid_uid);

    // Execute the statement and check if the deletion was successful
    if ($stmt->execute()) {
        header("Location: index.php?msg=Data deleted successfully");
        exit(); // Make sure to stop script execution after a redirect
    } else {
        echo "Failed to delete record: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
} else {
    echo "No RFID UID provided.";
}

// Close the database connection
$conn->close();
?>
