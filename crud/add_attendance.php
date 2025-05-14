<?php
include "db_conn.php";

if (isset($_POST["submit"])) {
    $rfid_uid = $_POST['rfid_uid'];
    $subject_id = $_POST['subject_id'];
    $attendance_date = $_POST['attendance_date'];
    $status = $_POST['status'];

    // Prepare the SQL statement to insert attendance data
    $stmt = $conn->prepare("INSERT INTO attendance (rfid_uid, subject_id, attendance_date, status) VALUES (?, ?, ?, ?)");

    if ($stmt === false) {
        die('Prepare failed: ' . $conn->error);
    }

    $stmt->bind_param("siss", $rfid_uid, $subject_id, $attendance_date, $status);

    // Execute the statement and check if the insertion was successful
    if ($stmt->execute()) {
        header("Location: index.php?msg=Attendance record created successfully");
        exit(); // Make sure to stop script execution after a redirect
    } else {
        echo "Failed to create attendance record: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <title>Attendance System</title>
</head>
<body>
    <nav class="navbar navbar-light justify-content-center fs-3 mb-5" style="background-color: #00ff5573;">
        PHP Complete Students Attendance CRUD Application with RFID card
    </nav>

    <div class="container">
        <div class="text-center mb-4">
            <h3>Add Attendance Record</h3>
            <p class="text-muted">Complete the form below to add a new attendance record</p>
        </div>

        <div class="container d-flex justify-content-center">
            <form action="" method="post" enctype="multipart/form-data" style="width:50vw; min-width:300px;">
                <div class="mb-3">
                    <label class="form-label">RFID UID:</label>
                    <input type="text" class="form-control" name="rfid_uid" placeholder="Enter RFID UID" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Subject ID:</label>
                    <input type="number" class="form-control" name="subject_id" placeholder="Enter Subject ID" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Attendance Date:</label>
                    <input type="date" class="form-control" name="attendance_date" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Status:</label>
                    <select class="form-select" name="status" required>
                        <option value="Present">Present</option>
                        <option value="Absent">Absent</option>
                        <option value="Late">Late</option>
                    </select>
                </div>

                <div>
                    <button type="submit" class="btn btn-success" name="submit">Save</button>
                    <a href="index.php" class="btn btn-danger">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
