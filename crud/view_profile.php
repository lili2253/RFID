<?php
include "db_conn.php";

if (isset($_GET['rfid_uid'])) {
    $rfid_uid = mysqli_real_escape_string($conn, $_GET['rfid_uid']);

    $sql = "SELECT students.rfid_uid, students.name, students.class, subjects.subject_name, attendance.status, attendance.attendance_date, students.profile_image
            FROM students
            JOIN attendance ON students.rfid_uid = attendance.rfid_uid
            JOIN subjects ON attendance.subject_id = subjects.subject_id
            WHERE students.rfid_uid = '$rfid_uid'";
    
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $student = mysqli_fetch_assoc($result);
    } else {
        echo "<div class='alert alert-danger'>No record found.</div>";
        exit;
    }
} else {
    echo "<div class='alert alert-danger'>Invalid request.</div>";
    exit;
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

  <title>Student Profile</title>
</head>

<body>
  <div class="container mt-5">
    <h2>Student Profile</h2>
    <div class="card mb-3" style="max-width: 540px;">
      <div class="row g-0">
        <div class="col-md-4">
          <img src="uploads/<?php echo htmlspecialchars($student['profile_image']); ?>" class="img-fluid rounded-start" alt="Profile Image">
        </div>
        <div class="col-md-8">
          <div class="card-body">
            <h5 class="card-title"><?php echo htmlspecialchars($student['name']); ?></h5>
            <p class="card-text"><strong>RFID:</strong> <?php echo htmlspecialchars($student['rfid_uid']); ?></p>
            <p class="card-text"><strong>Class:</strong> <?php echo htmlspecialchars($student['class']); ?></p>
            <p class="card-text"><strong>Subject:</strong> <?php echo htmlspecialchars($student['subject_name']); ?></p>
            <a href="attendance_index.php" class="btn btn-primary">Back to Home</a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
