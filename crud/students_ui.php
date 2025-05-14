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
$student_name = $_SESSION['name'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <title>Student Dashboard</title>
    <style>
        body {
            background-color: #f8f9fa;
        }
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            width: 240px;
            background-color: #343a40;
            padding-top: 20px;
            color: white;
            overflow-y: auto;
        }
        .sidebar a {
            padding: 15px;
            text-decoration: none;
            font-size: 18px;
            color: white;
            display: block;
        }
        .sidebar a:hover, .sidebar .active {
            background-color: #575d63;
        }
        .content {
            margin-left: 240px; /* Margin for the sidebar width */
            padding: 20px;
        }
        .iframe-content {
            width: 100%;
            height: 80vh; /* Set the height as per your need */
            border: none;
        }
        .navbar {
            background-color: #f8f9fa;
            margin-bottom: 30px;
            font-family: 'Times New Roman', Times, serif;
        }
    </style>
</head>
<body>

    <div class="sidebar">
        <h4 class="text-center">Student's Dashboard</h4>
        <a href="student_home.php" class="active mt-5" target="contentFrame"><i class="fas fa-home"></i> Dashboard</a>
        <a href="student_attendance_index.php" target="contentFrame"><i class="fas fa-check-circle"></i>Attendance Records</a>
        <a href="students_report.php" target="contentFrame"><i class="fa-chart-line"></i> Reports</a>
        <a href="student_changepassword.php" target="contentFrame"><i class="fas fa-key"></i> Change Password</a>
        <a href="#" onclick="confirmLogout()"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <div class="content">
        <nav class="navbar navbar-light justify-content-center fs-3">
            Welcome, <?php echo htmlspecialchars($student_name); ?>
        </nav>

        <iframe name="contentFrame" class="iframe-content" src="student_home.php"></iframe>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function confirmLogout() {
            if (confirm("Are you sure you want to log out?")) {
                window.location.href = "students_logout.php";
            }
        }
    </script>
</body>
</html>
