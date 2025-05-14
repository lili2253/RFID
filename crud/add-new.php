<?php
include "db_conn.php";

if (isset($_POST["submit"])) {
    $rfid_uid = $_POST['rfid_uid'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $class = $_POST['class'];
    $gender = $_POST['gender'];
    $contact = $_POST['contact'];
    
    // Handle file upload
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
               // Prepare the SQL statement to prevent SQL injection
               $stmt = $conn->prepare("INSERT INTO students (rfid_uid, name, email, class, gender, contact, profile_image) VALUES (?, ?, ?, ?, ?, ?, ?)");
               
               if ($stmt === false) {
                   die('Prepare failed: ' . $conn->error);
               }
           
               $stmt->bind_param("sssssss", $rfid_uid, $name, $email, $class, $gender, $contact, $newImageName);
           
               // Execute the statement and check if the insertion was successful
               if ($stmt->execute()) {
                   header("Location: index.php?msg=New record created successfully");
                   exit(); // Make sure to stop script execution after a redirect
               } else {
                   // Handle execution failure
                   echo "Failed to create record: " . $stmt->error;
               }
           
               // Close the statement
               $stmt->close();
           } else {
               // Handle file upload failure
               echo "Failed to upload image. Please check directory permissions or path.";
           }
        } else {
            echo "Unsupported image format.";
        }
    } else {
        echo "Error uploading file.";
    }
} else {
    echo "Invalid form submission.";
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
<body>
    <nav class="navbar navbar-light justify-content-center fs-3 mb-5" style="background-color: #00ff5573;">
        PHP Complete Students Attendance CRUD Application with RFID card
    </nav>

    <div class="container">
        <div class="text-center mb-4">
            <h3>Add New Student</h3>
            <p class="text-muted">Complete the form below to add a new user</p>
        </div>

        <div class="container d-flex justify-content-center">
            <form action="add-new.php" method="post" enctype="multipart/form-data" style="width:50vw; min-width:300px;">
                <div class="mb-3">
                    <label class="form-label">RFID UID:</label>
                    <input type="text" id="rfid_uid" class="form-control" name="rfid_uid" placeholder="Waiting for RFID scan..." required readonly>
                </div>

                <div class="mb-3">
                    <label class="form-label">Name:</label>
                    <input type="text" class="form-control" name="name" placeholder="Enter the student name" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email:</label>
                    <input type="email" class="form-control" name="email" placeholder="Enter student's email" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Class:</label>
                    <input type="text" class="form-control" name="class" placeholder="Student's Class" required>
                </div>

                <div class="form-group mb-3">
                    <label>Gender:</label>
                    &nbsp;
                    <input type="radio" class="form-check-input" name="gender" id="male" value="Male" required>
                    <label for="male" class="form-input-label">Male</label>
                    &nbsp;
                    <input type="radio" class="form-check-input" name="gender" id="female" value="Female" required>
                    <label for="female" class="form-input-label">Female</label>
                    &nbsp;
                    <input type="radio" class="form-check-input" name="gender" id="other" value="Other" required>
                    <label for="other" class="form-input-label">Other</label>
                </div>

                <div class="mb-3">
                    <label class="form-label">Contact:</label>
                    <input type="text" class="form-control" name="contact" placeholder="Student's Contact Number" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Profile Image:</label>
                    <input type="file" class="form-control" name="profile_image" accept="image/*">
                </div>

                <div>
                    <button type="submit" class="btn btn-success" name="submit">Save</button>
                    <a href="index.php" class="btn btn-danger">Cancel</a>
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
