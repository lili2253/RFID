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
    $email = $_POST['email'];
    $class = $_POST['class'];
    $gender = $_POST['gender'];
    $contact = $_POST['contact'];
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
                // Update the database with the new image
                $sql = "UPDATE `students` SET `name`='$name',`email`='$email',`class`='$class',`gender`='$gender',`contact`='$contact', `profile_image`='$newImageName' WHERE `rfid_uid` = '$rfid_uid'";
            }
        } else {
            echo "Invalid file type. Only JPG, JPEG, PNG, and GIF files are allowed.";
        }
    } else {
        // Update the database without changing the image
        $sql = "UPDATE `students` SET `name`='$name',`email`='$email',`class`='$class',`gender`='$gender',`contact`='$contact' WHERE `rfid_uid` = '$rfid_uid'";
    }

    $result = mysqli_query($conn, $sql);

    if ($result) {
        header("Location: index.php?msg=Data updated successfully");
    } else {
        echo "Failed: " . mysqli_error($conn);
    }
}

// Fetch existing data to display in the form
$sql = "SELECT * FROM `students` WHERE `rfid_uid` = '$rfid_uid' LIMIT 1";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <title>PHP CRUD Application</title>
</head>

<body>
    <nav class="navbar navbar-light justify-content-center fs-3 mb-5" style="background-color: #00ff5573;">
        PHP Complete Students Attendance CRUD Application with RFID card
    </nav>

    <div class="container">
        <div class="text-center mb-4">
            <h3>Edit Student Information</h3>
            <p class="text-muted">Click update after changing any information</p>
        </div>

        <div class="container d-flex justify-content-center">
            <form action="" method="post" enctype="multipart/form-data" style="width:50vw; min-width:300px;">
                <div class="row mb-3">
                    <div class="col">
                        <label class="form-label">Name:</label>
                        <input type="text" class="form-control" name="name" value="<?php echo $row['name'] ?>" required>
                    </div>

                    <div class="col">
                        <label class="form-label">Email:</label>
                        <input type="email" class="form-control" name="email" value="<?php echo $row['email'] ?>" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Class:</label>
                    <input type="text" class="form-control" name="class" value="<?php echo $row['class'] ?>" required>
                </div>

                <div class="form-group mb-3">
                    <label>Gender:</label>
                    &nbsp;
                    <input type="radio" class="form-check-input" name="gender" id="male" value="Male" <?php echo ($row["gender"] == 'Male') ? "checked" : ""; ?>>
                    <label for="male" class="form-input-label">Male</label>
                    &nbsp;
                    <input type="radio" class="form-check-input" name="gender" id="female" value="Female" <?php echo ($row["gender"] == 'Female') ? "checked" : ""; ?>>
                    <label for="female" class="form-input-label">Female</label>
                </div>

                <div class="mb-3">
                    <label class="form-label">Contact:</label>
                    <input type="text" class="form-control" name="contact" value="<?php echo $row['contact'] ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Profile Image:</label>
                    <input type="file" class="form-control" name="profile_image">
                    <img src="uploads/<?php echo $row['profile_image'] ?>" width="100" height="100" alt="Profile Image">
                </div>

                <div>
                    <button type="submit" class="btn btn-success" name="submit">Update</button>
                    <a href="index.php" class="btn btn-danger">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

</body>

</html>
