<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 500px;
            margin: 50px auto;
            background-color: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            margin-bottom: 5px;
        }
        input[type="text"],
        input[type="date"],
        select {
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        input[type="submit"] {
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .error {
            color: red;
            margin-bottom: 10px;
        }
    </style>
    <script>
        function validateForm() {
            var name = document.forms["registrationForm"]["name"].value;
            var classRecord = document.forms["registrationForm"]["class"].value;
            var dob = document.forms["registrationForm"]["dob"].value;
            if (name == "" || classRecord == "" || dob == "") {
                alert("All fields are required.");
                return false;
            }
        }
    </script>
</head>
<body>
    <div class="container">
        <h2>Student Registration</h2>

        <?php
        // Check if the form is submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Retrieve form data
            $name = $_POST["name"];
            $class = $_POST["class"];
            $gender = $_POST["gender"];
            $dob = $_POST["dob"];

            // Database connection details
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "student_records";

            // Create a connection
            $conn = new mysqli($servername, $username, $password, $dbname);

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Check if the class already exists
            $check_query = "SELECT * FROM `students` WHERE `Class` = '$class'";
            $result = $conn->query($check_query);

            if ($result->num_rows > 0) {
                // Class already exists, display an error message
                echo "<div class='error'>Error: Class '$class' already exists.</div>";
            } else {
                // Prepare and execute the SQL query to insert data into the 'students' table
                $sql = "INSERT INTO `students` (`Name`, `Class`, `Gender`, `D.O.B`) 
                        VALUES ('$name', '$class', '$gender', '$dob')";

                if ($conn->query($sql) === TRUE) {
                    // Registration successful
                    echo "Registration successful! Student added to the records.";
                    // Redirect back to index.html after 2 seconds
                    echo "<script>setTimeout(function(){window.location.href='index.html';},2000);</script>";
                } else {
                    // Error in registration
                    echo "<div class='error'>Error: " . $sql . "<br>" . $conn->error . "</div>";
                }
            }

            // Close the database connection
            $conn->close();
        }
        ?>

        <form name="registrationForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" onsubmit="return validateForm()">
            <label for="name">Name:</label>
            <input type="text" name="name" required><br>

            <label for="class">Class:</label>
            <input type="text" name="class" required><br>

            <label for="gender">Gender:</label>
            <select name="gender" required>
                <option value="M">Male</option>
                <option value="F">Female</option>
            </select><br>

            <label for="dob">Date of Birth:</label>
            <input type="date" name="dob" required><br>

            <input type="submit" value="Register">
        </form>
    </div>
</body>
</html>
