<?php
include "db_conn.php";

// Initialize $msg and $alertClass to avoid undefined variable warnings
$msg = "";
$alertClass = "";

if (isset($_GET['rfid_uid'])) {
    $rfid_uid = $_GET['rfid_uid'];
    $door_id = 2; // Assuming door_id is known and constant
    $access_status = "Granted";

    // Insert data into door_logs
    $query = "INSERT INTO door_logs (rfid_uid, door_id, access_time, access_status) VALUES (?, ?, NOW(), ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sis", $rfid_uid, $door_id, $access_status);

    if ($stmt->execute()) {
        $msg = "Access Log Created Successfully!";
        $alertClass = "alert-success";
    } else {
        $msg = "Failed to Create Access Log!";
        $alertClass = "alert-danger";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Student Doorlock Access</title>
</head>
<style>
.custom-btn {
    background-color: #007bff; 
    color: #fff;
    border: none;
    border-radius: 0.25rem; 
    padding: 0.5rem 1rem; 
    font-size: 1rem; 
}

.custom-btn:hover {
    background-color: #0056b3; /* Change to a darker color on hover */
    color: #fff;
}
h3{
  background-color: #87ceeb;
  font-family: Arial, Helvetica, sans-serif;
  background-size: 65px;
  margin-bottom: 30px;
  font-size: 35px;
}
.search{
  display: flex;
  align-items: center;
  width:500px;

}
.search input{
   flex: 1;
   margin-right: 10px; 
}
.search .btn{
  white-space: nowrap;
}
</style>
<body>
    <div class="container mt-5">
        <?php if ($msg): ?>
        <div class="alert <?php echo $alertClass; ?> text-center">
            <?php echo $msg; ?>
        </div>
        <?php endif; ?>

        <h3 class="text-center mb-5">Access Log</h3>

        <!-- Search Form -->
        <form method="GET" class="mb-3">
          <div class="search">
            <input type="text" name="search" class="form-control" placeholder="Search by student name or door name" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
            <button type="submit" class="btn btn-primary ">Search</button>
            </div>
        </form>
        

        <table class="table table-bordered">
            <tr>
                <th>RFID UID</th>
                <th>Student Name</th>
                <th>Door Name</th>
                <th>Access Time</th>
                <th>Access Status</th>
            </tr>
            <?php
            include "db_conn.php";

            // Retrieve search input if available
            $search = isset($_GET['search']) ? "%" . $_GET['search'] . "%" : "%";

            $query = "
                SELECT door_logs.rfid_uid, students.name AS student_name, doors.door_name, door_logs.access_time, door_logs.access_status
                FROM door_logs
                JOIN students ON door_logs.rfid_uid = students.rfid_uid
                JOIN doors ON door_logs.door_id = doors.door_id
                WHERE students.name LIKE ? OR doors.door_name LIKE ?
                ORDER BY door_logs.access_time DESC
            ";

            $stmt = $conn->prepare($query);
            $stmt->bind_param("ss", $search, $search);
            $stmt->execute();
            $result = $stmt->get_result();

            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['rfid_uid']) . "</td>";
                echo "<td>" . htmlspecialchars($row['student_name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['door_name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['access_time']) . "</td>";
                echo "<td>" . htmlspecialchars($row['access_status']) . "</td>";
                echo "</tr>";
            }

            $stmt->close();
            ?>
        </table>

        <a href="main_page_doorlock.php" class="btn custom-btn mt-2">Go to Main Page</a> <!-- Custom button class -->
    </div>
</body>
</html>
