<?php
// Database connection
include "db_conn.php";

$message = ''; // Initialize the message variable
$message_type = ''; // Initialize the message type variable

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Fetch form data
    $name = $_POST['name'] ?? null;
    $email = $_POST['email'] ?? null;
    $class = $_POST['class'] ?? null; // Renamed from 'department' to 'class' for consistency
    $password = $_POST['password'] ?? null;
    $confirm_password = $_POST['confirm_password'] ?? null;

    // Check if any required field is empty
    if (empty($name) || empty($email) || empty($class) || empty($password) || empty($confirm_password)) {
        $message = "All fields are required.";
        $message_type = 'error';
    } elseif ($password !== $confirm_password) {
        $message = "Passwords do not match.";
        $message_type = 'error';
    } else {
        // Check if the email exists in the students table
        $stmt1 = $conn->prepare("SELECT rfid_uid FROM students WHERE email = ?");
        $stmt1->bind_param("s", $email);
        $stmt1->execute();
        $result = $stmt1->get_result();
    
        if ($result->num_rows > 0) {
            // Email exists; proceed with registration
            $student = $result->fetch_assoc();
            $rfid_uid = $student['rfid_uid'];
    
            // Debugging statement
            error_log("rfid_uid retrieved from students table: " . $rfid_uid);
    
            // Check if the student is already registered
            $stmt2 = $conn->prepare("SELECT rfid_uid FROM student_logins WHERE rfid_uid = ?");
            $stmt2->bind_param("s", $rfid_uid); // Note the change to "s" for VARCHAR
            $stmt2->execute();
            $login_result = $stmt2->get_result();
    
            if ($login_result->num_rows > 0) {
                // Student is already registered; show a message
                $message = "Your account already exists.";
                $message_type = 'error';
            } else {
                // Register the student by inserting into student_logins table
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $stmt3 = $conn->prepare("INSERT INTO student_logins (rfid_uid, password) VALUES (?, ?)");
                $stmt3->bind_param("ss", $rfid_uid, $hashed_password); // Note the change to "ss" for VARCHAR
                
                if ($stmt3->execute()) {
                    $message = "Registration successful!";
                    $message_type = 'success';
                } else {
                    // Debugging statement
                    error_log("Error executing statement: " . $stmt3->error);
                    $message = "Error: " . $stmt3->error;
                    $message_type = 'error';
                }
    
                $stmt3->close();
            }
    
            $stmt2->close();
        } else {
            // No student found with that email
            $message = "Sorry, you cannot register. No student found with the provided email.";
            $message_type = 'error';
        }
    
        $stmt1->close();
    }

    // Close connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Student Registration</h2>
        <div class="row justify-content-center mt-4">
            <div class="col-md-8">
                <?php
                // Display alert box if there's a message
                if (!empty($message)) {
                    $alert_class = $message_type === 'success' ? 'alert-success' : 'alert-danger';
                    echo '<div class="alert alert-dismissible ' . $alert_class . '">' . htmlspecialchars($message) . '</div>';
                }
                ?>
                <form action="students_register.php" method="POST">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name:</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email:</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="class" class="form-label">Class:</label>
                        <input type="text" class="form-control" id="class" name="class" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password:</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Confirm Password:</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">Register</button>
                    </div>
                </form>
                <p class="text-center mt-3">I have already an account! <a href="students_login.php" class="text-warning" style="font-weight:600; text-decoration:none;">Login Now</a></p>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
