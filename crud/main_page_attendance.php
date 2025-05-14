<!DOCTYPE html>
<html lang="en">
<head>
    <title>Webpage Design</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Body styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 0;
        }

        /* Main container */
       /* .main {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        /* Navbar styling */
       /* .navbar {
            width: 100%;
            background: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 10px;
            box-sizing: border-box;
        }

        .menu ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            justify-content: center;
        }

        .menu ul li {
            margin: 0 15px;
        }

        .menu ul li a {
            text-decoration: none;
            color: #333;
            font-weight: bold;
            transition: color 0.3s ease;
        }

        .menu ul li a:hover {
            color: #ff7200;
        }

        /* Content section styling */
        .content {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        /* Container for form elements */
        .form-container {
            background: #e0e5ec; 
            border-radius: 20px;
            box-shadow: 8px 8px 15px #a3b1c6, -8px -8px 15px #ffffff;
            padding: 30px;
            max-width: 400px;
            width: 100%;
            margin-top: 20px;
            transition: box-shadow 0.3s ease;
            display: flex;
            flex-direction: column;
            align-items: center; /* Center horizontally */
            justify-content: center; /* Center vertically */
        }

        .form-container:hover {
            box-shadow: 12px 12px 20px #a3b1c6, -12px -12px 20px #ffffff;
        }

        /* Form styling */
        form {
            display: flex;
            flex-direction: column;
            align-items: center; /* Center horizontally */
            width: 100%;
        }

        /* Styling for form groups */
        .form-group {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        input[type="radio"] {
            margin-right: 10px;
            accent-color: #ff7200; /* Accent color for the radio buttons */
        }

        label {
            font-size: 20px;
            color: #000;
            font-family: 'Times New Roman', Times, serif;
        }

        /* Button styling */
        button.cn {
            background-color: #ff7200;
            color: #fff;
            border: none;
            padding: 12px 20px;
            border-radius: 25px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
            margin-top: 20px;
            text-align: center;
        }

        button.cn:hover {
            background-color: #e98888;
            transform: scale(1.05);
        }

        button.cn:focus {
            outline: none;
        }

        /* Heading and paragraph styling */
        h1 {
            font-size: 24px;
            margin-bottom: 10px;
        }

        p.par {
            font-size: 18px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="main">
        <div class="navbar">
            <div class="icon">
                <h2 class="logo">4CtGirls</h2>
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
            <h1>Welcome from<br><span>Attendance System</span><br></h1>
            <p class="par">Firstly, choose your role:</p>

            <div class="form-container">
               <form action="role_selection.php" method="POST">
                    <div class="form-group">
                        <input type="radio" id="student" name="role" value="student" required>
                        <label for="student">Student</label>
                    </div>
                    <div class="form-group">
                        <input type="radio" id="teacher" name="role" value="teacher" required>
                        <label for="teacher">Teacher</label>
                    </div>
                    <button class="cn" type="submit">Join Us</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/ionicons@5.4.0/dist/ionicons.js"></script>
</body>
</html>
