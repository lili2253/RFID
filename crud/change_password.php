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
$teacher_name = $_SESSION['teacher_name'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Fetch the current hashed password from the database
    $query = "SELECT password FROM teachers_login WHERE teacher_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $teacher_id);
    $stmt->execute();
    $stmt->bind_result($stored_hashed_password);
    $stmt->fetch();
    $stmt->close();

    // Verify that the current password matches the stored hashed password
    if (!password_verify($current_password, $stored_hashed_password)) {
        $error = "The current password is incorrect.";
    } elseif ($new_password !== $confirm_password) {
        $error = "The new passwords do not match.";
    } else {
        // Hash the new password before storing it
        $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // Update the password in the database
        $update_query = "UPDATE teachers_login SET password = ? WHERE teacher_id = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param("si", $new_hashed_password, $teacher_id);

        if ($update_stmt->execute()) {
            $success = "Password successfully changed.";
        } else {
            $error = "An error occurred while updating the password.";
        }

        $update_stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<style>
   h2 {
    font-family: 'Times New Roman', Times, serif;
    text-align: center;
}

.container {
    width: 450px;
    padding: 20px;
    background-color: #87ceeb;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
    margin-top: 50px;
    margin-left: auto;
    margin-right: auto; /* Center the container horizontally */
}

.btn {
    display: block;
    width: 120px;
    margin: 0 auto; /* Center the button horizontally */
    text-align: center;
}

</style>
<body>
    <div class="container">
        <h2>Change Password</h2>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php elseif (isset($success)): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <form action="change_password.php" method="POST">
            <div class="mb-3">
                <label for="current_password" class="form-label">Current Password</label>
                <input type="password" class="form-control" id="current_password" name="current_password" required>
            </div>
            <div class="mb-3">
                <label for="new_password" class="form-label">New Password</label>
                <input type="password" class="form-control" id="new_password" name="new_password" required>
            </div>
            <div class="mb-3">
                <label for="confirm_password" class="form-label">Confirm New Password</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
            </div>
            <button type="submit" class="btn btn-primary">Confirm</button>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
