<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['teacher_id'])) {
    header("Location: teachers_login.php");
    exit();
}

// Include database connection
include "db_conn.php";

$teacher_id = $_SESSION['teacher_id'];
$teacher_name = $_SESSION['teacher_name'];
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

    <title>Teacher Dashboard</title>
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
        .sidebar a:hover {
            background-color: #575d63;
            color: white;
        }
        .sidebar .active {
            background-color: #343a40;
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
        <h4>Teacher's Dashboard</h4>
        <a href="home.php" class="active mt-5" target="contentFrame"><i class="fas fa-home"></i> Dashboard</a>
        <a href="index.php" target="contentFrame"><i class="fas fa-users"></i> Manage Students</a>
        <a href="attendance_index.php" target="contentFrame"><i class="fas fa-check-circle"></i> Manage Attendance</a>
        <a href="teachers_report.php" target="contentFrame"><i class="fa-chart-line"></i> Reports</a>
        <a href="change_password.php" target="contentFrame"><i class="fas fa-key"></i> Change Password</a>
        <a href="#" onclick="confirmLogout()"><i class="fas fa-sign-out-alt"></i> Logout</a>

<script>
function confirmLogout() {
    if (confirm("Are you sure you want to log out?")) {
        window.location.href = "teachers_logout.php";
    }
}
</script>

    </div>

    <div class="content">
        <nav class="navbar navbar-light justify-content-center fs-3">
            Welcome, <?php echo htmlspecialchars($teacher_name); ?>
        </nav>

        <iframe name="contentFrame" class="iframe-content" src="home.php"></iframe>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
