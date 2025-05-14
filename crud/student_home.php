<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['rfid_uid'])) {
    header("Location: students_login.php");
    exit();
}

// Include database connection
include "db_conn.php";

$rfid_uid = $_SESSION['rfid_uid'];

// Fetch student data
$query = "SELECT * FROM students WHERE rfid_uid = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $rfid_uid);
$stmt->execute();
$student_data = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Fetch attendance data
$attendance_query = "SELECT COUNT(*) AS attended_classes FROM attendance WHERE rfid_uid = ?";
$stmt = $conn->prepare($attendance_query);
$stmt->bind_param("s", $rfid_uid);
$stmt->execute();
$attendance_result = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Get the count of attended classes
$attended_classes = $attendance_result['attended_classes'];

// Get the total number of classes
$total_classes_query = "SELECT COUNT(DISTINCT subject_id) AS total_classes FROM attendance WHERE rfid_uid = ?";
$stmt = $conn->prepare($total_classes_query);
$stmt->bind_param("s", $rfid_uid);
$stmt->execute();
$total_classes_result = $stmt->get_result()->fetch_assoc();
$stmt->close();

$total_classes = $total_classes_result['total_classes'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card {
            margin-bottom: 20px;
            background-color: #87ceeb;
        }
        h1 {
            color: #87ceeb;
            font-family: Arial, Helvetica, sans-serif;
            font-size: large;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Welcome to Your Dashboard, <?php echo htmlspecialchars($student_data['name']); ?></h1>

        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Current Attendance</h5>
                        <p class="card-text">
                            You have attended <?php echo htmlspecialchars($attended_classes); ?> classes out of <?php echo htmlspecialchars($total_classes); ?>.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Upcoming Deadlines</h5>
                        <ul>
                            <!-- You can dynamically generate this list from a database -->
                            <li>Assignment 1 - Due Date: September 10, 2024</li>
                            <li>Project 1 - Due Date: September 15, 2024</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Recent Reports</h5>
                        <p class="card-text">
                            Check your <a href="students_report.php">recent reports</a> to see any feedback or updates from your teachers.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
