<?php
session_start();
include "db_conn.php";

// Initialize attempt count if not already set
if (!isset($_SESSION['attempt_count'])) {
    $_SESSION['attempt_count'] = 0;
}

// Check if the attempt limit is reached
if ($_SESSION['attempt_count'] >= 5) {
    echo "<script>alert('You have reached the maximum number of attempts. Please try again later.'); window.location.href = 'main_page_doorlock.php';</script>";
    exit();
}

// Your existing code for processing form submissions
if (isset($_POST["submit"])) {
    $rfid_uid = $_POST['rfid_uid'];

    // Check if the RFID UID exists in the students table
    $query = "SELECT * FROM students WHERE rfid_uid = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $rfid_uid);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // RFID UID exists
        $_SESSION['attempt_count'] = 0; // Reset attempt count on successful access
        echo "<script>
                if (confirm('The tag exists. Access Granted. Click OK to proceed or Cancel to exit.')) {
                    window.location.href = 'student_doorlock.php?rfid_uid={$rfid_uid}';
                } else {
                    window.location.href = 'AD_doorlock.php';
                }
              </script>";
    } else {
        // RFID UID does not exist
        $_SESSION['attempt_count']++; // Increment attempt count
        echo "<script>
                if (confirm('Access Denied! This tag cannot proceed. Click OK to exit.')) {
                    window.location.href = 'AD_doorlock.php';
                }
              </script>";
    }
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

    <title>PHP CRUD Application</title>
</head>
<style>
    nav{
        font-family:'Times New Roman', Times, serif;
    }
    h3{
        font-family:Arial, Helvetica, sans-serif;
    }
</style>
<body>
    <nav class="navbar navbar-light justify-content-center fs-3 mb-5" style="background-color: #87ceeb;">
        Access or Denied with RFID card
    </nav>

    <div class="container">
        <?php if (isset($msg)) : ?>
            <div class="alert <?php echo $alertClass; ?> text-center">
                <?php echo $msg; ?>
            </div>
        <?php endif; ?>

        <div class="text-center mb-4">
            <h3>Doorlock Access</h3>
        </div>

        <div class="container d-flex justify-content-center">
            <form action="AD_doorlock.php" method="post" enctype="multipart/form-data" style="width:50vw; min-width:300px;">
                <div class="mb-3">
                    <label class="form-label">RFID UID:</label>
                    <input type="text" id="rfid_uid" class="form-control" name="rfid_uid" placeholder="Waiting for RFID scan..." required readonly>
                </div>

                <div>
                <button type="submit" name="submit" class="btn btn-success">Submit</button>
                <a href="main_page_doorlock.php" class="btn btn-danger">Cancel</a>
                </div>

            </form>
        </div>
    </div>

    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function fetchRFID() {
                fetch('rfidContainer.php')
                    .then(response => response.text())
                    .then(data => {
                        let rfidData = data.trim();
                        if (rfidData) {
                            document.getElementById('rfid_uid').value = rfidData;
                        } else {
                            document.getElementById('rfid_uid').placeholder = "Waiting for RFID scan...";
                        }
                    });
            }

            // Fetch the RFID UID every 5 seconds
            setInterval(fetchRFID, 5000);
        });
    </script>
</body>
</html>
