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

// Fetch attendance records with subject names
$query = "
    SELECT a.attendance_date, a.subject_id, a.status, s.subject_name 
    FROM attendance a 
    JOIN subjects s ON a.subject_id = s.subject_id 
    WHERE a.rfid_uid = ? 
    ORDER BY a.attendance_date DESC
";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $rfid_uid);
$stmt->execute();
$attendance_records = $stmt->get_result();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Records</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container {
            margin-top: 20px;
        }
        .table {
            background-color: #87ceeb;
        }
        h1 {
            color: #87ceeb;
            font-family: 'Times New Roman', Times, serif;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Attendance Records</h1>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Subject Name</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($attendance_records->num_rows > 0): ?>
                    <?php while ($row = $attendance_records->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['attendance_date']); ?></td>
                            <td><?php echo htmlspecialchars($row['subject_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['status']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3">No attendance records found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
