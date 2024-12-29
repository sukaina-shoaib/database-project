<?php
include("database/hospital_database.php");

// Fetch doctors and their details from the database
$doctor_query = "SELECT Doctor_ID, Name, Doctor_Days, Doctor_Time FROM Doctor";
$doctor_result = mysqli_query($conn, $doctor_query);

$message = "";
$new_appointment = [];

// Handle form submission for new patient
// Handle form submission for new patient
if (isset($_POST["submit"])) {
    $name = $_POST["name"];
    $dob = $_POST["date_of_birth"];
    $contact = $_POST["contact_number"];
    $address = $_POST["address"];
    $doctor_id = $_POST["doctor_id"];
    $appointment_day = $_POST["appointment_day"];
    $appointment_time = $_POST["appointment_time"];

    // Get doctor's fee
    $doctor_fee_query = "SELECT Amount FROM Doctor WHERE Doctor_ID = '$doctor_id'";
    $doctor_fee_result = mysqli_query($conn, $doctor_fee_query);
    $doctor_fee_row = mysqli_fetch_assoc($doctor_fee_result);
    $doctor_fee = $doctor_fee_row['Amount'];

    $query = "INSERT INTO Patient (Name, Date_of_Birth, Contact_Number, Address, Doctor_ID) 
              VALUES ('$name', '$dob', '$contact', '$address', '$doctor_id')";

    if (mysqli_query($conn, $query)) {
        $patient_id = mysqli_insert_id($conn);

        $appointment_query = "INSERT INTO Appointment (Patient_ID, Doctor_ID, Appointment_Day, Appointment_Time) 
                              VALUES ('$patient_id', '$doctor_id', '$appointment_day', '$appointment_time')";
        if (mysqli_query($conn, $appointment_query)) {
            $appointment_id = mysqli_insert_id($conn);

            // Insert bill record
            $bill_query = "INSERT INTO Bill (Patient_ID, Appointment_ID, Amount) 
                           VALUES ('$patient_id', '$appointment_id', '$doctor_fee')";
            mysqli_query($conn, $bill_query);

            header("Location: bill.php?appointment_id=$appointment_id");
            exit();
        } else {
            $message = "Error inserting appointment: " . mysqli_error($conn);
        }
    } else {
        $message = "Error inserting patient: " . mysqli_error($conn);
    }
}

// Handle form submission for existing patient
if (isset($_POST["submit_existing_patient"])) {
    $patient_id = $_POST["patient_id"];
    $doctor_id = $_POST["doctor_id"];
    $appointment_day = $_POST["appointment_day"];
    $appointment_time = $_POST["appointment_time"];

    // Get doctor's fee
    $doctor_fee_query = "SELECT Amount FROM Doctor WHERE Doctor_ID = '$doctor_id'";
    $doctor_fee_result = mysqli_query($conn, $doctor_fee_query);
    $doctor_fee_row = mysqli_fetch_assoc($doctor_fee_result);
    $doctor_fee = $doctor_fee_row['Amount'];

    $check_patient_query = "SELECT * FROM Patient WHERE Patient_ID = '$patient_id'";
    $patient_result = mysqli_query($conn, $check_patient_query);

    if (mysqli_num_rows($patient_result) > 0) {
        $appointment_query = "INSERT INTO Appointment (Patient_ID, Doctor_ID, Appointment_Day, Appointment_Time) 
                              VALUES ('$patient_id', '$doctor_id', '$appointment_day', '$appointment_time')";
        if (mysqli_query($conn, $appointment_query)) {
            $appointment_id = mysqli_insert_id($conn);

            // Insert bill record
            $bill_query = "INSERT INTO Bill (Patient_ID, Appointment_ID, Amount) 
                           VALUES ('$patient_id', '$appointment_id', '$doctor_fee')";
            mysqli_query($conn, $bill_query);

            header("Location: bill.php?appointment_id=$appointment_id");
            exit();
        } else {
            $message = "Error inserting appointment: " . mysqli_error($conn);
        }
    } else {
        $message = "Error: Patient with ID $patient_id not found.";
    }
}

// Fetch all appointments
$appointment_query = "SELECT a.Appointment_ID, a.Patient_ID, a.Appointment_Day, a.Appointment_Time, p.Name AS patient_name, d.Name AS doctor_name
                      FROM Appointment a
                      JOIN Patient p ON a.Patient_ID = p.Patient_ID
                      JOIN Doctor d ON a.Doctor_ID = d.Doctor_ID";
$appointments_result = mysqli_query($conn, $appointment_query);

// Prepare doctor data for JavaScript
$doctor_data = [];
if (mysqli_num_rows($doctor_result) > 0) {
    while ($row = mysqli_fetch_assoc($doctor_result)) {
        $doctor_data[$row['Doctor_ID']] = [
            'name' => $row['Name'],
            'days' => $row['Doctor_Days'],
            'time' => $row['Doctor_Time']
        ];
    }
}
?>
<!-- HTML section remains unchanged -->


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Management</title>
    <link rel="stylesheet" href="formstyle.css" >
   
    <script>
        let doctorData = <?php echo json_encode($doctor_data); ?>;

        function showForm(formId) {
            document.getElementById('registration-form').style.display = formId === 'new' ? 'block' : 'none';
            document.getElementById('existing-patient-form').style.display = formId === 'existing' ? 'block' : 'none';
        }

        function handleDoctorSelection(formId) {
            const doctorId = document.getElementById('doctor_id_' + formId).value;
            const appointmentDetails = document.getElementById('appointment-details_' + formId);

            if (doctorId && doctorData[doctorId]) {
                const days = doctorData[doctorId]['days'];
                const time = doctorData[doctorId]['time'];
                appointmentDetails.innerHTML = `
                    <label>Select Day</label>
                    <select name="appointment_day" required>
                        ${days.split(',').map(day => `<option value="${day}">${day}</option>`).join('')}
                    </select>
                    <label>Time</label>
                    <input type="text" name="appointment_time" value="${time}" readonly>
                `;
            } else {
                appointmentDetails.innerHTML = '';
            }
        }

        // Function to dynamically add a new record to the table
        function addNewAppointmentToTable(appointment) {
            const tableBody = document.querySelector('table tbody');
            const newRow = document.createElement('tr');
            newRow.innerHTML = `
                <td>${appointment.Patient_ID}</td>
                <td>${appointment.patient_name}</td>
                <td>${appointment.doctor_name}</td>
                <td>${appointment.Appointment_Day}</td>
                <td>${appointment.Appointment_Time}</td>
            `;
            tableBody.appendChild(newRow);
        }

        // After page load, check if there's a new appointment and add it to the table
        window.onload = function() {
            const newAppointment = <?php echo json_encode($new_appointment); ?>;
            if (newAppointment) {
                addNewAppointmentToTable(newAppointment);
            }
        };
    </script>
</head>
<body style="background-image: url('images/form.png');  background-repeat: no-repeat; ">



    <!-- <h2>Patient Management</h2> -->
    <?php if (!empty($message)) echo "<p style='color: green;'>$message</p>"; ?>

    <div class="container">
<div class="logo">
  <img src="images/logo.png" alt=""  width="50" >
  </div>
<nav>
  <ul>
  <li><a href="form.php" >Home</a></li>
  <li><a href="doctor.php" target="_blank">Services</a></li>
  <li><a href="doctor.php" target="_blank">doctors</a></li>
  <li><a href="login.html" >logout</a></li>
  </ul>
  </nav>
</div>
<hr />

    <button onclick="showForm('new')">New Patient</button>
    <button onclick="showForm('existing')">Existing Patient</button>

    <div id="registration-form">
        <h3>New Patient Form</h3>
        <form method="POST" >
            <br>Name:
            <br>
            <input type="text" name="name" placeholder="Enter Name" required>
            <br>Date Of Birth<input type="date" name="date_of_birth" required>
            <br>Contact Number
            <input type="tel" name="contact_number" placeholder="Enter Contact Number" required>
            <!-- <input type="date" name="date_of_birth" required> -->
             <br>Address:-
            <textarea name="address" placeholder="Enter your Address" rows="3" required></textarea>
            <select id="doctor_id_new" name="doctor_id" onchange="handleDoctorSelection('new')" required>
                <option value="">Select Doctor</option>
                <?php foreach ($doctor_data as $id => $data) echo "<option value='$id'>{$data['name']}</option>"; ?>
            </select>
            <div id="appointment-details_new"></div>
            <button  type="submit" name="submit" href="bill.php" ><a target="_blank">Book an Appointment</a></button>
        </form>
    </div>

    <div id="existing-patient-form">
        <h3>Existing Patient Form</h3>
        <form method="POST">
        <br>patient id:- <br>
            <input type="text" name="patient_id" placeholder="Patient ID" required>
           
            <br> select doctor :- <br>
            <select id="doctor_id_existing" name="doctor_id" onchange="handleDoctorSelection('existing')" required>
                <option value="">Select Doctor</option>
                <?php foreach ($doctor_data as $id => $data) echo "<option value='$id'>{$data['name']}</option>"; ?>
            </select>
            <div id="appointment-details_existing"></div>
            <button type="submit" name="submit_existing_patient" href="bill.php">Book Appointment</button>
        </form>
    </div>

    <br><br><h3 style="color:aliceblue">Appointments</h3>
    <table>
    <thead>
        <tr>
            <th>Patient ID</th>
            <th>Patient Name</th>
            <th>Doctor Name</th>
            <th>Day</th>
            <th>Time</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = mysqli_fetch_assoc($appointments_result)) { ?>
            <tr id="row-<?php echo $row['Appointment_ID']; ?>">
                <td><?php echo $row['Patient_ID']; ?></td>
                <td><?php echo $row['patient_name']; ?></td>
                <td><?php echo $row['doctor_name']; ?></td>
                <td><?php echo $row['Appointment_Day']; ?></td>
                <td><?php echo $row['Appointment_Time']; ?></td>
                <td>
                    <button onclick="deleteAppointment(<?php echo $row['Appointment_ID']; ?>)">Delete</button>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<script>
    function deleteAppointment(appointmentId) {
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "delete_appointment.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onload = function () {
                if (xhr.status === 200) {
                    // Remove the row from the table
                    document.getElementById("row-" + appointmentId).remove();
                    
                } else {
                    alert("Error deleting appointment.");
                }
            };
            xhr.send("appointment_id=" + appointmentId);
        }
    

    // Open bill in a new tab
    document.querySelectorAll('button[name="submit"], button[name="submit_existing_patient"]').forEach(btn => {
        btn.addEventListener('click', function () {
            const form = this.closest('form');
            form.target = '_blank';
        });
    });
</script>
