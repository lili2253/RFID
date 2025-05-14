<?php
include "db_conn.php";

// Check if 'id' exists in the URL
if (isset($_GET["id"])) {
    $rfid_uid = $_GET["id"];
} else {
    echo "No RFID UID provided.";
    exit();
}

if (isset($_POST["submit"])) {
    // Code to handle form submission
    $name = $_POST['name'];
    $class = $_POST['class'];
    $subject = $_POST['subject'];
    $status = $_POST['status'];
    $date = $_POST['date'];
    $image = $_FILES['profile_image'];

    $imageName = $image['name'];
    $imageTmpName = $image['tmp_name'];
    $imageSize = $image['size'];
    $imageError = $image['error'];
    $imageType = $image['type'];

    if ($imageError === 0) {
        $imageExtension = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($imageExtension, $allowedExtensions)) {
            $newImageName = uniqid('', true) . "." . $imageExtension;
            $imageDestination = 'uploads/' . $newImageName;

            if (move_uploaded_file($imageTmpName, $imageDestination)) {
                // Update the attendance record with the new image
                $sql = "UPDATE `attendance` SET `subject_id`='$subject', `status`='$status', `attendance_date`='$date', `profile_image`='$newImageName' WHERE `rfid_uid` = '$rfid_uid'";
            } else {
                echo "Failed to upload image.";
                exit();
            }
        } else {
            echo "Invalid file type. Only JPG, JPEG, PNG, and GIF files are allowed.";
            exit();
        }
    } else {
        // Update the attendance record without changing the image
        $sql = "UPDATE `attendance` SET `subject_id`='$subject', `status`='$status', `attendance_date`='$date' WHERE `rfid_uid` = '$rfid_uid'";
    }

    $result = mysqli_query($conn, $sql);

    if ($result) {
        // Update student information
        $sql_student = "UPDATE `students` SET `name`='$name', `class`='$class' WHERE `rfid_uid` = '$rfid_uid'";
        $result_student = mysqli_query($conn, $sql_student);

        if ($result_student) {
            header("Location: attendance_index.php?msg=Data updated successfully");
        } else {
            echo "Failed to update student information: " . mysqli_error($conn);
        }
    } else {
        echo "Failed to update attendance: " . mysqli_error($conn);
    }
}

// Fetch existing data to display in the form
$sql = "SELECT a.*, s.name, s.class FROM `attendance` a JOIN `students` s ON a.rfid_uid = s.rfid_uid WHERE a.rfid_uid = '$rfid_uid' LIMIT 1";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

// Set default value for profile image if not present
$profileImage = isset($row['profile_image']) ? $row['profile_image'] : '';
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>Edit Attendance Information</title>
</head>
<body>
    <nav class="navbar navbar-light justify-content-center fs-3 mb-5" style="background-color: #00ff5573;">
        PHP Complete Students Attendance CRUD Application with RFID card
    </nav>

    <div class="container">
        <div class="text-center mb-4">
            <h3>Edit Attendance Information</h3>
            <p class="text-muted">Click update after changing any information</p>
        </div>

        <div class="container d-flex justify-content-center">
            <form action="" method="post" enctype="multipart/form-data" style="width:50vw; min-width:300px;">
                <div class="mb-3">
                    <label class="form-label">RFID UID:</label>
                    <input type="text" class="form-control" name="rfid_uid" value="<?php echo htmlspecialchars($row['rfid_uid']) ?>" readonly>
                </div>

                <div class="mb-3">
                    <label class="form-label">Name:</label>
                    <input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($row['name']) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Class:</label>
                    <input type="text" class="form-control" name="class" value="<?php echo htmlspecialchars($row['class']) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Subject ID:</label>
                    <input type="number" class="form-control" name="subject" value="<?php echo htmlspecialchars($row['subject_id']) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Status:</label>
                    <input type="text" class="form-control" name="status" value="<?php echo htmlspecialchars($row['status']) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Date:</label>
                    <input type="date" class="form-control" name="date" value="<?php echo htmlspecialchars($row['attendance_date']) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Profile Image:</label>
                    <input type="file" class="form-control" name="profile_image">
                    <?php if ($profileImage) : ?>
                        <img src="uploads/<?php echo htmlspecialchars($profileImage) ?>" width="100" height="100" alt="Profile Image">
                    <?php endif; ?>
                </div>

                <div>
                    <button type="submit" class="btn btn-success" name="submit">Update</button>
                    <a href="attendance_index.php" class="btn btn-danger">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
