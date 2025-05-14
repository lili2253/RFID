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

// Fetch subjects for the student
$subjects_query = "SELECT s.subject_id, s.subject_name 
                   FROM subjects s
                   JOIN attendance a ON s.subject_id = a.subject_id
                   WHERE a.rfid_uid = ?";
$stmt = $conn->prepare($subjects_query);
$stmt->bind_param("s", $rfid_uid);
$stmt->execute();
$subjects_result = $stmt->get_result();

// Fetch existing reports
$reports_query = "SELECT r.subject_id, s.subject_name, r.report_text
                  FROM reports r
                  JOIN subjects s ON r.subject_id = s.subject_id
                  WHERE r.rfid_uid = ?";
$stmt_reports = $conn->prepare($reports_query);
$stmt_reports->bind_param("s", $rfid_uid);
$stmt_reports->execute();
$reports_result = $stmt_reports->get_result();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['subject_id'], $_POST['report_text'])) {
    $subject_id = $_POST['subject_id'];
    $report_text = $_POST['report_text'];

    // Fetch the teacher_id based on the selected subject_id
    $teacher_query = "SELECT teacher_id FROM subjects WHERE subject_id = ?";
    $stmt_teacher = $conn->prepare($teacher_query);
    $stmt_teacher->bind_param("i", $subject_id);
    $stmt_teacher->execute();
    $stmt_teacher->bind_result($teacher_id);
    $stmt_teacher->fetch();
    $stmt_teacher->close();

    // Insert feedback into the reports table with teacher_id
    $insert_query = "INSERT INTO reports (rfid_uid, subject_id, teacher_id, report_text) VALUES (?, ?, ?, ?)";
    $stmt_insert = $conn->prepare($insert_query);
    $stmt_insert->bind_param("siis", $rfid_uid, $subject_id, $teacher_id, $report_text);

    if ($stmt_insert->execute()) {
        $feedback_message = "Feedback submitted successfully.";
    } else {
        $feedback_message = "Failed to submit feedback.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Feedback</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <style>
        h1{
            font-family: Arial, Helvetica, sans-serif;
            font-size:35px;
            color:#87ceeb;

        }
        h2{
            font-family:Arial, Helvetica, sans-serif;
            font-size:25px;
            color: #87ceeb;
        }
        .container {
            margin-top: 30px;
        }
        .card {
            margin-bottom: 20px;
        }
        .feedback-form {
            margin-bottom: 30px;
        }
    </style>
</head>
<body>

<div class="container mt-1 mb-2">
    <h1>Student Feedback</h1>
    
    <?php if (isset($feedback_message)): ?>
        <div class="alert alert-info" role="alert">
            <?php echo htmlspecialchars($feedback_message); ?>
        </div>
    <?php endif; ?>

    <div class="feedback-form">
        <form method="POST" action="">
            <div class="mb-3">
                <label for="subject_id" class="form-label">Subject</label>
                <select id="subject_id" name="subject_id" class="form-select" required>
                    <?php while ($subject = $subjects_result->fetch_assoc()): ?>
                        <option value="<?php echo $subject['subject_id']; ?>">
                            <?php echo htmlspecialchars($subject['subject_name']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="report_text" class="form-label">Feedback</label>
                <textarea id="report_text" name="report_text" class="form-control" rows="4" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Submit Feedback</button>
        </form>
    </div>

    <div class="existing-reports">
        <h2>Existing Feedback Lists</h2>
        <?php if ($reports_result->num_rows > 0): ?>
            <?php while ($report = $reports_result->fetch_assoc()): ?>
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($report['subject_name']); ?></h5>
                        <p class="card-text"><?php echo nl2br(htmlspecialchars($report['report_text'])); ?></p>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No feedback reports found.</p>
        <?php endif; ?>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
