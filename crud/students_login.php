<?php
// Database connection
include "db_conn.php";

$message = ''; // Initialize the message variable
$message_type = ''; // Initialize the message type variable

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Fetch form data
    $email = $_POST['email'] ?? null;
    $password = $_POST['password'] ?? null;

    // Check if any required field is empty
    if (empty($email) || empty($password)) {
        $message = "All fields are required.";
        $message_type = 'error';
    } else {
        // Check if the email exists in the students table
        $stmt1 = $conn->prepare("SELECT rfid_uid, name FROM students WHERE email = ?");
        $stmt1->bind_param("s", $email);
        $stmt1->execute();
        $result = $stmt1->get_result();
    
        if ($result->num_rows > 0) {
            // Email exists; proceed with login
            $student = $result->fetch_assoc();
            $rfid_uid = $student['rfid_uid'];
            $student_name = $student['name'];
    
            // Check if the student is already registered for login
            $stmt2 = $conn->prepare("SELECT password FROM student_logins WHERE rfid_uid = ?");
            $stmt2->bind_param("i", $rfid_uid);
            $stmt2->execute();
            $login_result = $stmt2->get_result();
    
            if ($login_result->num_rows > 0) {
                // Student found in student_logins; verify password
                $login_data = $login_result->fetch_assoc();
                if (password_verify($password, $login_data['password'])) {
                    // Set session variables
                    session_start();
                    $_SESSION['rfid_uid'] = $rfid_uid;
                    $_SESSION['name'] = $student_name;

                    // Redirect to the dashboard
                    header("Location: students_ui.php");
                    exit();
                } else {
                    $message = "Incorrect password.";
                    $message_type = 'error';
                }
            } else {
                // Student exists but is not registered for login
                $message = "This account is not registered for login.";
                $message_type = 'error';
            }
    
            $stmt2->close();
        } else {
            // No student found with that email
            $message = "No account found with the provided email.";
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
    <title>Student Login</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container mt-5 justify-content-center">
        <h2 class="text-center">Student Login</h2>
        <div class="row justify-content-center mt-4">
            <div class="col-md-8">
                <?php
                // Display alert box if there's a message
                if (!empty($message)) {
                    $alert_class = $message_type === 'success' ? 'alert-success' : 'alert-danger';
                    echo '<div class="alert alert-dismissible ' . $alert_class . '">' . htmlspecialchars($message) . '</div>';
                }
                ?>
                <form action="students_login.php" method="POST">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email:</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                   
                    <div class="mb-3">
                        <label for="password" class="form-label">Password:</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary mt-1 mb-1">Log in</button>
                    </div>
                </form>
                <p align="center">Don't have an account? <a href="students_register.php" class="text-warning" style="font-weight:600; text-decoration:none;">Register here</a></p>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
