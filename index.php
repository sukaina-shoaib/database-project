<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hospital Management Dashboard</title>
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f6f9;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Navbar Style */
        nav {
            background-color: #2c3e50;
            padding: 15px 0;
            color: #fff;
        }

        nav .container {
            width: 90%;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        nav h1 {
            font-size: 24px;
        }

        nav ul {
            list-style: none;
            display: flex;
            gap: 20px;
        }

        nav ul li a {
            text-decoration: none;
            color: #ecf0f1;
            font-size: 16px;
        }

        nav ul li a:hover {
            color: #3498db;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            padding: 30px 0;
            background-color: #ecf0f1;
        }

        .container {
            width: 90%;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 30px;
        }

        .card {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .card h3 {
            font-size: 22px;
            margin-bottom: 15px;
        }

        .card p {
            font-size: 18px;
            color: #7f8c8d;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 20px;
            background-color: #3498db;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #2980b9;
        }

        /* Footer */
        .footer {
            background-color: #34495e;
            color: #fff;
            text-align: center;
            padding: 20px;
        }

        .footer p {
            font-size: 14px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            nav .container {
                flex-direction: column;
                align-items: flex-start;
            }

            .container {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .card {
                padding: 15px;
            }

            .card h3 {
                font-size: 20px;
            }

            .card p {
                font-size: 16px;
            }

            .btn {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav>
        <div class="container">
            <h1>Hospital Dashboard</h1>
            <ul>
                <li><a href="#">Home</a></li>
                <li><a href="doctor.php">Doctors</a></li>
                <li><a href="#">Appointments</a></li>
                <li><a href="#">Patients</a></li>
                <li><a href="#">Logout</a></li>
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container">
            <!-- Doctor Card -->
            <div class="card">
                <h3>Doctors</h3>
                <p>Manage and view doctor profiles</p>
                <a href="doctor.php" class="btn">View Doctors</a>
            </div>

            <!-- Patients Card -->
            <div class="card">
                <h3>Patients</h3>
                <p>Manage patient records and details</p>
                <a href="patients.php" class="btn">View Patients</a>
            </div>

            <!-- Appointments Card -->
            <div class="card">
                <h3>Appointments</h3>
                <p>Schedule and manage appointments</p>
                <a href="appointments.php" class="btn">View Appointments</a>
            </div>

            <!-- Billing Card -->
            <!-- <div class="card">
                <h3>Billing</h3>
                <p>Manage payments and billing details</p>
                <a href="billing.php" class="btn">View Billing</a>
            </div> -->
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>&copy; 2024 Hospital Management System. All Rights Reserved.</p>
    </div>

</body>
</html>
