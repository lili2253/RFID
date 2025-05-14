<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $role = $_POST['role'];

    if ($role == 'student') {
        header("Location: students_login.php");
    } elseif ($role == 'teacher') {
        header("Location: teachers_login.php");
    } else {
        // Handle unexpected values
        echo "Invalid role selected.";
    }
    exit();
} else {
    // Handle the case where the form wasn't submitted properly
    echo "Please select a role.";
}
?>
