<?php
include("database/hospital_database.php");


$doctor_query = "SELECT Doctor_ID, Name FROM Doctor";
$doctor_result = mysqli_query($conn, $doctor_query);

if (!$doctor_result) {
    die("Error fetching doctors: " . mysqli_error($conn));
}

if (isset($_POST["submit"])) {
    $name = $_POST["name"];
    $dob = $_POST["date_of_birth"];
    $contact = $_POST["contact_number"];
    $address = $_POST["address"];
    $doctor_id = $_POST["doctor_id"]; // Get selected doctor ID

    $query = "INSERT INTO Patient (Name, Date_of_Birth, Contact_Number, Address, Doctor_ID) 
              VALUES ('$name', '$dob', '$contact', '$address', '$doctor_id')";

    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Patient data inserted successfully');</script>";
    } else {
        echo "Error: " . $query . "<br>" . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Patient Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .btn-group {
            margin-bottom: 20px;
        }
        .form-container {
            display: none;
            margin-top: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input, textarea, button {
            width: 20%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            cursor: pointer;
            background-color: #007BFF;
            color: white;
            border: none;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style> 
</head>
<body>
    <div class="btn-group">
        <button id="btn">New Patient</button>
    </div>

    <div class="form-container" id="form-container">
        <form action="form.php" method="POST">
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" placeholder="Enter full name" required>
            </div>
            <div class="form-group">
                <label for="dob">Date of Birth</label>
                <input type="date" id="dob" name="date_of_birth" required>
            </div>
            <div class="form-group">
                <label for="contact">Contact Number</label>
                <input type="tel" id="contact" name="contact_number" placeholder="Enter contact number" required>
            </div>
            <div class="form-group">
                <label for="address">Address</label>
                <textarea id="address" name="address" placeholder="Enter address" rows="4" required></textarea>
            </div>
            <div class="form-group">
                <label for="doctor_id">Doctor ID</label>
                <input type="text" id="doctor_id" name="doctor_id" placeholder="Enter doctor ID" required>
            </div>
            <button type="submit" name="submit">Submit</button>
        </form>
    </div>

    <script>
        // JavaScript to toggle form visibility
        document.getElementById('btn').addEventListener('click', function () {
            const formContainer = document.getElementById('form-container');
            if (formContainer.style.display === 'none' || formContainer.style.display === '') {
                formContainer.style.display = 'block';
            } else {
                formContainer.style.display = 'none';
            }
        });
    </script>
</body>
</html>
