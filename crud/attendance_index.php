<?php
include "db_conn.php";
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

  <title>Attendance Display Home Page</title>
</head>

<body>
<nav class="navbar navbar-light justify-content-center fs-3 mb-5" style="background-color: #87ceeb; font-family: Arial, Helvetica, sans-serif; font-size: 20px;">
    PHP Complete Students Attendance CRUD Application with RFID card
</nav>


  <div class="container">
    <?php
    if (isset($_GET["msg"])) {
      $msg = $_GET["msg"];
      echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
      ' . htmlspecialchars($msg) . '
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
    }
    ?>
    <a href="add_attendance.php" class="btn btn-dark mb-3">Attendance Mark</a>
    
    <!-- Search Form -->
    <div class="col-md-7">
      <form action="" method="GET">
        <div class="input-group mb-3">
          <input type="text" name="search" required value="<?php if(isset($_GET['search'])){echo htmlspecialchars($_GET['search']); } ?>" class="form-control" placeholder="Search data">
          <button type="submit" class="btn btn-primary">Search</button>
        </div>
      </form>
    </div>

    <?php
    if(isset($_GET['search'])) {
      $filtervalues = mysqli_real_escape_string($conn, $_GET['search']);
      $query = "SELECT students.rfid_uid, students.name, students.class, subjects.subject_name, attendance.status, attendance.attendance_date, students.profile_image
                FROM students
                JOIN attendance ON students.rfid_uid = attendance.rfid_uid
                JOIN subjects ON attendance.subject_id = subjects.subject_id
                WHERE CONCAT(students.name, subjects.subject_name) LIKE '%$filtervalues%' ";
      $query_run = mysqli_query($conn, $query);

      if(mysqli_num_rows($query_run) > 0) {
        ?>
        <table class="table table-hover text-center">
          <thead class="table-dark">
            <tr>
              <th scope="col">RFID_UID</th>
              <th scope="col">Name</th>
              <th scope="col">Class</th>
              <th scope="col">Subject</th>
              <th scope="col">Status</th>
              <th scope="col">Date</th>
              <th scope="col">Profile</th>
              <th scope="col">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php
            while ($row = mysqli_fetch_assoc($query_run)) {
              ?>
              <tr>
                <td><?php echo htmlspecialchars($row["rfid_uid"]) ?></td>
                <td><?php echo htmlspecialchars($row["name"]) ?></td>
                <td><?php echo htmlspecialchars($row["class"]) ?></td>
                <td><?php echo htmlspecialchars($row["subject_name"]) ?></td>
                <td><?php echo htmlspecialchars($row["status"]) ?></td>
                <td><?php echo htmlspecialchars($row["attendance_date"]) ?></td>
                <td>
                  <img src="uploads/<?php echo htmlspecialchars($row["profile_image"]); ?>" alt="Profile Image" style="width: 50px; height: 50px; object-fit: cover;">
                </td>
                <td>
                  <a href="edit_attendance.php?id=<?php echo urlencode($row["rfid_uid"]) ?>" class="link-dark"><i class="fa-solid fa-pen-to-square fs-5 me-3"></i></a>
                  <a href="#" onclick="confirmDelete('delete.php?id=<?php echo urlencode($row["rfid_uid"]) ?>'); return false;" class="link-dark"><i class="fa-solid fa-trash fs-5"></i></a>
                </td>
              </tr>
              <?php
            }
            ?>
          </tbody>
        </table>
        <?php
      } else {
        echo "<div class='alert alert-danger'>No records found.</div>";
      }
    } else {
      // Default data display if search is not initiated
      $sql = "SELECT students.rfid_uid, students.name, students.class, subjects.subject_name, attendance.status, attendance.attendance_date, students.profile_image
              FROM students
              JOIN attendance ON students.rfid_uid = attendance.rfid_uid
              JOIN subjects ON attendance.subject_id = subjects.subject_id";
      $result = mysqli_query($conn, $sql);

      if (mysqli_num_rows($result) > 0) {
        ?>
        <table class="table table-hover text-center">
          <thead class="table-dark">
            <tr>
              <th scope="col">RFID_UID</th>
              <th scope="col">Name</th>
              <th scope="col">Class</th>
              <th scope="col">Subject</th>
              <th scope="col">Status</th>
              <th scope="col">Date</th>
              <th scope="col">Profile</th>
              <th scope="col">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php
            while ($row = mysqli_fetch_assoc($result)) {
              ?>
              <tr>
                <td><?php echo htmlspecialchars($row["rfid_uid"]) ?></td>
                <td><?php echo htmlspecialchars($row["name"]) ?></td>
                <td><?php echo htmlspecialchars($row["class"]) ?></td>
                <td><?php echo htmlspecialchars($row["subject_name"]) ?></td>
                <td><?php echo htmlspecialchars($row["status"]) ?></td>
                <td><?php echo htmlspecialchars($row["attendance_date"]) ?></td>
                <td>
                  <a href="view_profile.php?rfid_uid=<?php echo urlencode($row['rfid_uid']); ?>">
                   <img src="uploads/<?php echo htmlspecialchars($row['profile_image']); ?>" alt="Profile Image" style="width: 50px; height: 50px; object-fit: cover;">
                  </a>
                </td>

                <td>
                  <a href="edit_attendance.php?id=<?php echo urlencode($row["rfid_uid"]) ?>" class="link-dark"><i class="fa-solid fa-pen-to-square fs-5 me-3"></i></a>
                  <a href="#" onclick="confirmDelete('delete.php?id=<?php echo urlencode($row["rfid_uid"]) ?>'); return false;" class="link-dark"><i class="fa-solid fa-trash fs-5"></i></a>
                </td>
              </tr>
              <?php
            }
            ?>
          </tbody>
        </table>
        <?php
      } else {
        echo "<div class='alert alert-danger'>No records found.</div>";
      }
    }
    ?>
  </div>

  <!-- Bootstrap -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

  <script>
  function confirmDelete(url) {
      if (confirm("Are you sure to delete this record?")) {
          window.location.href = url; // Redirect to the delete URL if confirmed
      }
  }
  </script>

</body>

</html>
