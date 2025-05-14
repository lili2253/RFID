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

// Fetch feedback from the database
$stmt = $conn->prepare("
    SELECT r.report_id, s.name AS student_name, sub.subject_name, r.report_text, r.created_at 
    FROM reports r
    JOIN students s ON r.rfid_uid = s.rfid_uid
    JOIN subjects sub ON r.subject_id = sub.subject_id
    WHERE r.teacher_id = ?
    ORDER BY r.created_at DESC
");

if ($stmt === false) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("i", $teacher_id);

if (!$stmt->execute()) {
    die("Execute failed: " . $stmt->error);
}

$result = $stmt->get_result();

if ($result === false) {
    die("Get result failed: " . $stmt->error);
}

$feedbacks = [];
while ($row = $result->fetch_assoc()) {
    $feedbacks[] = $row;
}
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Reports</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        h1 {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 35px;
            color: #87ceeb;
        }
        h2 {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 25px;
            color: #87ceeb;
        }
        .container {
            margin-top: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            text-align: center;
            padding: 12px;
            border: 1px solid #ddd;
        }
        thead {
            background-color: #87ceeb;
            color: #fff;
        }
        tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tbody tr:nth-child(odd) {
            background-color: #ffffff;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2>Student Feedback Reports</h2>
        <?php if (count($feedbacks) > 0): ?>
            <table class="table table-bordered mt-3">
                <thead>
                    <tr>
                        <th>Report ID</th>
                        <th>Student Name</th>
                        <th>Subject</th>
                        <th>Feedback</th>
                        <th>Date Submitted</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($feedbacks as $feedback): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($feedback['report_id']); ?></td>
                            <td><?php echo htmlspecialchars($feedback['student_name']); ?></td>
                            <td><?php echo htmlspecialchars($feedback['subject_name']); ?></td>
                            <td><?php echo htmlspecialchars($feedback['report_text']); ?></td>
                            <td><?php echo htmlspecialchars($feedback['created_at']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No feedback reports available.</p>
        <?php endif; ?>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
