<?php
// Start session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['teacher_id'])) {
    header("Location: teachers_login.php");
    exit();
}

// Include database connection
include "db_conn.php";

// Initialize variables
$total_students = $total_subjects = $total_classes = 0;
$attendance_by_subject = [];

// Fetch total number of students
$stmt1 = $conn->prepare("SELECT COUNT(*) AS total_students FROM students");
if ($stmt1) {
    $stmt1->execute();
    $result1 = $stmt1->get_result();
    $total_students = $result1->fetch_assoc()['total_students'];
    $stmt1->close();
} else {
    echo "Error fetching total students.";
}

// Fetch total number of subjects
$stmt2 = $conn->prepare("SELECT COUNT(*) AS total_subjects FROM subjects");
if ($stmt2) {
    $stmt2->execute();
    $result2 = $stmt2->get_result();
    $total_subjects = $result2->fetch_assoc()['total_subjects'];
    $stmt2->close();
} else {
    echo "Error fetching total subjects.";
}

// Fetch total number of classes (if applicable)
// Example query, replace with actual table if exists

$stmt3 = $conn->prepare("SELECT COUNT(*) AS total_classes FROM students");
if ($stmt3) {
    $stmt3->execute();
    $result3 = $stmt3->get_result();
    $total_classes = $result3->fetch_assoc()['total_classes'];
    $stmt3->close();
} else {
    echo "Error fetching total classes.";
}


// Fetch attendance by subject
$stmt4 = $conn->prepare("
    SELECT s.subject_name, COUNT(a.id) AS total_attendance 
    FROM attendance a
    JOIN subjects s ON a.subject_id = s.subject_id
    GROUP BY s.subject_name
");
if ($stmt4) {
    $stmt4->execute();
    $attendance_results = $stmt4->get_result();
    while ($row = $attendance_results->fetch_assoc()) {
        $attendance_by_subject[] = $row;
    }
    $stmt4->close();
} else {
    echo "Error fetching attendance data.";
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <style>
        body {
            background-color: #f8f9fa;
        }
        .dashboard {
            margin-top: 50px;
        }
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }
        .card:hover {
            transform: translateY(-10px);
        }
        .card-title {
            font-size: 1.25rem;
            font-weight: bold;
            margin-bottom: 0;
        }
        .card-body {
            padding: 20px;
        }
        .icon {
            font-size: 40px;
            color: #007bff;
        }
        .navbar {
            background-color: #87ceeb;
            margin-bottom: 30px;
            font-family: Arial, Helvetica, sans-serif;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-light justify-content-center fs-3">
        Teacher's Dashboard
    </nav>

    <div class="container dashboard">
        <div class="row g-4">
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-users icon"></i>
                        <h5 class="card-title">Total Students</h5>
                        <p class="card-text"><?php echo htmlspecialchars($total_students); ?></p>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-book icon"></i>
                        <h5 class="card-title">Total Subjects</h5>
                        <p class="card-text"><?php echo htmlspecialchars($total_subjects); ?></p>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-calendar icon"></i>
                        <h5 class="card-title">Total Classes</h5>
                        <p class="card-text"><?php echo htmlspecialchars($total_classes); ?></p>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-chart-line icon"></i>
                        <h5 class="card-title">Attendance by Subject</h5>
                        <?php foreach ($attendance_by_subject as $subject) : ?>
                            <p class="card-text"><?php echo htmlspecialchars($subject['subject_name']) . ': ' . htmlspecialchars($subject['total_attendance']); ?></p>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
