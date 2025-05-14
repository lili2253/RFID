<?php
include "db_conn.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Webpage Design</title>
    <link rel="stylesheet" href="style.css">
    <style>
        p{
            text-align: justify;
        }
        /* Add your styles here */
    </style>
</head>
<body>
    <div class="main">
        <div class="navbar">
            <div class="icon">
                <h2 class="logo">4CTGirls</h2>
            </div>
            <div class="menu">
                <ul>
                    <li><a href="main_home_page.php">Home</a></li>
                    <li><a href="main_page_attendance.php">Attendance System</a></li>
                    <li><a href="main_page_doorlock.php">Doorlock System</a></li>
                </ul>
            </div>
        </div> 
        <div class="content">
            <h1>IoT Based <br><span>Sustainable Campus</span> <br> </h1>
            <p class="par">Our project is to develop an integrated attendance and door lock system in the campus<br>that enhances security and streamlines daily operations for educational institutions <br> and create an efficient and secure system that 
                seamlessly manages access control. </p>
            <button class="cn" onclick="showMessage()">JOIN US</button>

            <div id="messageBox" class="message-box">
                <p>Thank you for joining us!</p>
                <button onclick="hideMessage()">Close</button>
            </div>
        </div>
    </div>
    <script src="https://unpkg.com/ionicons@5.4.0/dist/ionicons.js"></script>
    <script>
        function showMessage() {
            document.getElementById('messageBox').style.display = 'block';
            document.getElementById('messageBox').classList.add('show');
        }

        function hideMessage() {
            document.getElementById('messageBox').classList.remove('show');
            setTimeout(() => {
                document.getElementById('messageBox').style.display = 'none';
            }, 300); // Match the duration of the animation
        }
    </script>
</body>
</html>
